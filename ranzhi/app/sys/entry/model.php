<?php
/**
 * The model file of entry module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     entry 
 * @version     $Id: model.php 2422 2015-01-12 05:56:31Z chujilu $
 * @link        http://www.ranzhico.com
 */
class entryModel extends model
{
    /**
     * Get all entries. 
     * 
     * @access public
     * @return array
     */
    public function getEntries()
    {
        $entries = $this->dao->select('*')->from(TABLE_ENTRY)->orderBy('`order`')->fetchAll();

        /* Remove entry if no rights and fix logo path. */
        $newEntries = array();
        foreach($entries as $entry)
        {
            if($entry->logo != '' && substr($entry->logo, 0, 1) != '/') $entry->logo = $this->config->webRoot . $entry->logo;
            if(commonModel::hasAppPriv($entry->code)) $newEntries[] = $entry; 
        }
        $entries = $newEntries;

        /* Add custom settings. */
        $customApp = isset($this->config->personal->common->customApp) ? json_decode($this->config->personal->common->customApp->value) : new stdclass();
        foreach($entries as $entry)
        {
            if(isset($customApp->{$entry->id}))
            {
                if(isset($customApp->{$entry->id}->order))   $entry->order = $customApp->{$entry->id}->order;
                if(isset($customApp->{$entry->id}->visible)) $entry->visible = $customApp->{$entry->id}->visible;
            }
        }

        usort($entries, 'commonModel::sortEntryByOrder');

        return $entries;
    }

    /**
     * Get entry by id.
     * 
     * @param  int    $entryID
     * @access public
     * @return object 
     */
    public function getById($entryID)
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('id')->eq($entryID)->fetch();
    }

    /**
     * Get entry by code.
     * 
     * @param  string $code 
     * @access public
     * @return object 
     */
    public function getByCode($code)
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('code')->eq($code)->fetch(); 
    }

    /**
     * Create entry. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $maxOrder = $this->dao->select('`order`')->from(TABLE_ENTRY)->orderBy('order_desc')->limit(1)->fetch('order');

        $entry = fixer::input('post')
            ->setDefault('ip', '*')
            ->setDefault('visible', 0)
            ->setDefault('buildin', 0)
            ->setDefault('integration', 0)
            ->setDefault('order', $maxOrder + 10)
            ->setIF($this->post->allip, 'ip', '*')
            ->remove('allip')
            ->stripTags('login,logout,block', $this->config->allowedTags->admin)
            ->get();
        if($this->post->chanzhi) 
        {
            $entry->logout = $entry->login . "?m=ranzhi&f=logout";
            $entry->block  = $entry->login . "?m=ranzhi&f=block";
            $entry->login .= "?m=ranzhi&f=login";
        }

        if($entry->size == 'custom') $entry->size = helper::jsonEncode(array('width' => (int)$entry->width, 'height' => (int)$entry->height));

        $this->dao->insert(TABLE_ENTRY)
            ->data($entry, $skip = 'width,height,files,chanzhi,groups')
            ->autoCheck()
            ->batchCheck($this->config->entry->require->create, 'notempty')
            ->check('code', 'unique')
            ->check('code', 'code')
            ->exec();

        if(dao::isError()) return false;

        $entryID = $this->dao->lastInsertID();

        /* Insert app privilage. */
        $groups = $this->post->groups;
        if($groups != false && !empty($groups))
        {
            $priv = new stdclass();
            $priv->module = 'apppriv';
            $priv->method = $this->post->code;
            foreach($this->post->groups as $group)
            {
                $priv->group = $group;
                $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
            }
        }

        return $entryID;
    }

    /**
     * Update entry.
     * 
     * @param  int    $code 
     * @access public
     * @return void
     */
    public function update($code)
    {
        $oldEntry = $this->getByCode($code);

        $entry = fixer::input('post')
            ->setDefault('ip', '*')
            ->setDefault('visible', 0)
            ->setDefault('buildin', 0)
            ->setDefault('integration', 0)
            ->setIF($this->post->allip, 'ip', '*')
            ->remove('allip')
            ->stripTags('login,logout,block', $this->config->allowedTags->admin)
            ->get();

        if($entry->size == 'custom') $entry->size = helper::jsonEncode(array('width' => (int)$entry->width, 'height' => (int)$entry->height));
        if(!isset($entry->visible)) $entry->visible = 0;
        unset($entry->logo);

        $this->dao->update(TABLE_ENTRY)->data($entry, $skip = 'width,height,files')
            ->autoCheck()
            ->batchCheck($this->config->entry->require->edit, 'notempty')
            ->where('code')->eq($code)
            ->exec();
        return $oldEntry->id;
    }

    /**
     * Delete entry. 
     * 
     * @param  string $code 
     * @access public
     * @return void
     */
    public function delete($code, $table = null)
    { 
        $entry = $this->getByCode($code);

        $this->deleteLogo($entry->id);
        $this->dao->delete()->from(TABLE_ENTRY)->where('code')->eq($code)->exec();

        return !dao::isError();
    }

    /**
     * Get key of entry. 
     * 
     * @param  string $entry 
     * @access public
     * @return object 
     */
    public function getAppKey($entry)
    {
        return $this->config->entry->$entry->key;
    }
    /**
     * Create a key.
     * 
     * @access public
     * @return string 
     */
    public function createKey()
    {
        return md5(rand());
    }

    /**
     * Get all departments.
     * 
     * @access public
     * @return object 
     */
    public function getAllDepts()
    {
        return $this->dao->select('*')->from(TABLE_DEPT)->fetchAll();
    }

    /**
     * Get all users. 
     * 
     * @access public
     * @return object 
     */
    public function getAllUsers()
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * Update entry logo. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function updateLogo($entryID)
    {
        /* if no files then return. */
        if(empty($_FILES)) return true;

        /* Delete logo img. */
        $this->deleteLogo($entryID);

        /* Save logo img. */
        $fileTitle = $this->file->saveUpload('entryLogo', $entryID);
        if(!dao::isError())
        {
            $file = $this->file->getByID(key($fileTitle));

            $logoPath = $this->file->webPath . $file->pathname;
            $this->dao->update(TABLE_ENTRY)->set('logo')->eq($logoPath)->where('id')->eq($entryID)->exec();
        }
    }

    /**
     * Delete entry logo.
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function deleteLogo($entryID)
    {
        $files = $this->loadModel('file')->getByObject('entryLogo', $entryID);

        foreach($files as $file) $this->file->delete($file->id);
    }

    /**
     * Get blocks by API.
     * 
     * @param  object    $entry 
     * @access public
     * @return array
     */
    public function getBlocksByAPI($entry)
    {
        $http = $this->app->loadClass('http');

        if(empty($entry)) return array();
        $parseUrl   = parse_url($entry->block);
        $blockQuery = "mode=getblocklist&hash={$entry->key}&lang=" . $this->app->getClientLang();
        $parseUrl['query'] = empty($parseUrl['query']) ? $blockQuery : $parseUrl['query'] . '&' . $blockQuery;

        $link = '';
        if(!isset($parseUrl['scheme'])) 
        {
            $link  = commonModel::getSysURL() . $parseUrl['path'];
            $link .= '?' . $parseUrl['query'];
        }
        else
        {
            $link .= $parseUrl['scheme'] . '://' . $parseUrl['host'];
            if(isset($parseUrl['port'])) $link .= ':' . $parseUrl['port']; 
            if(isset($parseUrl['path'])) $link .= $parseUrl['path']; 
            $link .= '?' . $parseUrl['query'];
        }

        $blocks = $http->get($link);
        return json_decode($blocks, true);
    }

    /**
     * Get block params.
     * 
     * @param  object $entry 
     * @param  int    $blockID 
     * @access public
     * @return json
     */
    public function getBlockParams($entry, $blockID)
    {
        $http = $this->app->loadClass('http');

        if(empty($entry)) return array();
        $parseUrl  = parse_url($entry->block);
        $formQuery = "mode=getblockform&blockid=$blockID&hash={$entry->key}&lang=" . $this->app->getClientLang();
        $parseUrl['query'] = empty($parseUrl['query']) ? $formQuery : $parseUrl['query'] . '&' . $formQuery;

        $link = '';
        if(!isset($parseUrl['scheme'])) 
        {
            $link  = commonModel::getSysURL() . $parseUrl['path'];
            $link .= '?' . $parseUrl['query'];
        }
        else
        {
            $link .= $parseUrl['scheme'] . '://' . $parseUrl['host'];
            if(isset($parseUrl['port'])) $link .= ':' . $parseUrl['port']; 
            if(isset($parseUrl['path'])) $link .= $parseUrl['path']; 
            $link .= '?' . $parseUrl['query'];
        }
        $params = $http->get($link);

        return json_decode($params, true);
    }

    /**
     * Get entries of json.
     * 
     * @access public
     * @return void
     */
    public function getJSONEntries()
    {
        $entries    = $this->getEntries();
        $allEntries = array();

        foreach($entries as $entry)
        {

            $sso  = helper::createLink('entry', 'visit', "entryID=$entry->id");
            $logo = !empty($entry->logo) ? $entry->logo : '';
            $size = !empty($entry->size) ? ($entry->size != 'max' ? $entry->size : "'$entry->size'") : "'max'";
            $menu = $entry->visible ? 'all' : 'list';
            /* add web root if logo not start with /  */
            if($logo != '' && substr($logo, 0, 1) != '/') $logo = $this->config->webRoot . $logo;
            
            if(!isset($entry->control))  $entry->control = '';
            if(!isset($entry->position)) $entry->position = '';
            unset($tmpEntry);
            $tmpEntry['id']       = $entry->id;
            $tmpEntry['name']     = $entry->name;
            $tmpEntry['code']     = $entry->code;
            $tmpEntry['url']      = $sso;
            $tmpEntry['open']     = $entry->open;
            $tmpEntry['desc']     = $entry->name;
            $tmpEntry['size']     = $size;
            $tmpEntry['icon']     = $logo;
            $tmpEntry['control']  = $entry->control;
            $tmpEntry['position'] = $entry->position;
            $tmpEntry['menu']     = $menu;
            $tmpEntry['display']  = 'fixed';
            $tmpEntry['abbr']     = $entry->abbr;
            $tmpEntry['order']    = $entry->order;
            $tmpEntry['sys']      = $entry->buildin;
            $allEntries[] = $tmpEntry;
        }
        return json_encode($allEntries);
    }
}
