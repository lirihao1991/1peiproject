<?php
/**
 * The model file of upgrade module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: model.php 2443 2015-01-13 02:42:54Z daitingting $
 * @link        http://www.ranzhico.com
 */
?>
<?php
class upgradeModel extends model
{
    /**
     * Errors.
     * 
     * @static
     * @var array 
     * @access public
     */
    static $errors = array();

    /**
     * The execute method. According to the $fromVersion call related methods.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        switch($fromVersion)
        {
            case '1_0_beta':
                $this->execSQL($this->getUpgradeFile('1.0.beta'));
                $this->createCashEntry();
            case '1_1_beta':
                $this->execSQL($this->getUpgradeFile('1.1.beta'));
                $this->createTeamEntry();
            case '1_2_beta':
                $this->execSQL($this->getUpgradeFile('1.2.beta'));
                $this->transformBlock();
                $this->changeBuildinName();
                $this->computeContactInfo();
            case '1_3_beta':
                $this->execSQL($this->getUpgradeFile('1.3.beta'));
                $this->setCompanyContent();
            case '1_4_beta':
                $this->upgradeContractName();
                $this->upgradeProjectMember();
                $this->safeDropColumns(TABLE_PROJECT, 'master,member');

                /* Exec sqls must after fix data. */
                $this->execSQL($this->getUpgradeFile('1.4.beta'));
            case '1_5_beta':
                $this->execSQL($this->getUpgradeFile('1.5.beta'));
                $this->upgradeEntryLogo();
                $this->upgradeReturnRecords();
                $this->upgradeDeliveryRecords();
                $this->addSearchPriv();
            case '1_6':
                $this->execSQL($this->getUpgradeFile('1.6'));
                $this->addPrivs();

            default: if(!$this->isError()) $this->loadModel('setting')->updateVersion($this->config->version);
        }

        $this->deletePatch();
    }

    /**
     * Create the confirm contents.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return string
     */
    public function getConfirm($fromVersion)
    {
        $confirmContent = '';
        switch($fromVersion)
        {
            case '1_0_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            case '1_1_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.1.beta'));
            case '1_2_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.2.beta'));
            case '1_3_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.3.beta'));
            case '1_4_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.4.beta'));
            case '1_5_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.5.beta'));
            case '1_6'     : $confirmContent .= file_get_contents($this->getUpgradeFile('1.6'));
        }
        return $confirmContent;
    }

    /**
     * Delete the patch record.
     * 
     * @access public
     * @return void
     */
    public function deletePatch()
    {
        return true;
        $this->dao->delete()->from(TABLE_EXTENSION)->where('type')->eq('patch')->exec();
    }

    /**
     * Get the upgrade sql file.
     * 
     * @param  string $version 
     * @access public
     * @return string
     */
    public function getUpgradeFile($version)
    {
        return $this->app->getBasepath() . 'db' . DS . 'upgrade' . $version . '.sql';
    }

    /**
     * Execute a sql.
     * 
     * @param  string  $sqlFile 
     * @access public
     * @return void
     */
    public function execSQL($sqlFile)
    {
        $mysqlVersion = $this->loadModel('install')->getMysqlVersion();

        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line) 
        {
            $line       = trim($line);
            $sqls[$key] = $line;
            if(strpos($line, '--') !== false or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
            }

            try
            {
                $this->dbh->exec($sql);
            }
            catch (PDOException $e) 
            {
                self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /**
     * Judge any error occers.
     * 
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty(self::$errors);
    }

    /**
     * Get errors during the upgrading.
     * 
     * @access public
     * @return array
     */
    public function getError()
    {
        $errors = self::$errors;
        self::$errors = array();
        return $errors;
    }

    /**
     * create cash entry.
     * 
     * @access public
     * @return void
     */
    public function createCashEntry()
    {
        $entry = new stdclass();

        $entry->name     = 'cash';
        $entry->code     = 'cash';
        $entry->open     = 'iframe';
        $entry->order    = 2;
        $entry->ip       = '*';
        $entry->key      = '438d85f2c2b04372662c63ebfb1c4c2f';
        $entry->logo     = $this->config->webRoot . 'theme/default/images/ips/app-cash.png';
        $entry->login    = '../cash';
        $entry->ip       = '*';
        $entry->control  = 'simple';
        $entry->visible  = 1;
        $entry->size     = 'max';
        $entry->position = 'default';

        $block = REQUESTTYPE == 'GET' ? 'cash/index.php?m=block&f=index' : 'cash/block-index.html';
        $entry->block = $this->config->webRoot . $block;

        $this->dao->insert(TABLE_ENTRY)->data($entry)->exec();
    }

    /**
     * create team entry.
     * 
     * @access public
     * @return void
     */
    public function createTeamEntry()
    {
        $entry = new stdclass();

        $entry->name     = 'team';
        $entry->code     = 'team';
        $entry->open     = 'iframe';
        $entry->order    = 4;
        $entry->ip       = '*';
        $entry->key      = '6c46d9fe76a1afa1cd61f946f1072d1e';
        $entry->logo     = $this->config->webRoot . 'theme/default/images/ips/app-team.png';
        $entry->login    = '../team';
        $entry->ip       = '*';
        $entry->control  = 'simple';
        $entry->size     = 'max';
        $entry->position = 'default';

        $block = REQUESTTYPE == 'GET' ? 'team/index.php?m=block&f=index' : 'team/block-index.html';
        $entry->block = $this->config->webRoot . $block;

        $this->dao->insert(TABLE_ENTRY)->data($entry)->exec();
    }

    /**
     * Transform block from config to block table.
     * 
     * @access public
     * @return bool
     */
    public function transformBlock()
    {
        $blocks  = $this->dao->select('*')->from(TABLE_CONFIG)->where('section')->eq('block')->andWhere('module')->eq('index')->fetchAll('id');
        $entries = $this->dao->select('id,code')->from(TABLE_ENTRY)->fetchPairs('id', 'code');

        foreach($blocks as $block)
        {
            if(empty($block->owner)) continue;
            $block->value = json_decode($block->value);

            $source  = '';
            $blockID = $block->value->type;
            if($block->value->type == 'system')
            {
                if($block->app == 'sys' and isset($block->value->entryID) and !isset($entries[$block->value->entryID])) continue;
                $source  = $block->app == 'sys' ? $entries[$block->value->entryID] : $block->app;
                $blockID = $block->value->blockID;
            }

            if($blockID == 'html') $block->value->params = helper::jsonEncode(array('html' => $block->value->html));
            if(!isset($block->value->params)) $block->value->params = array();

            $data = new stdclass();
            $data->account = $block->owner;
            $data->app     = $block->app;
            $data->title   = $block->value->name;
            $data->source  = $source;
            $data->block   = $blockID;
            $data->params  = helper::jsonEncode($block->value->params);
            $data->order   = str_replace('b', '', $block->key);
            $data->grid    = 3;

            $this->dao->replace(TABLE_BLOCK)->data($data)->exec();
        }
        if(dao::isError()) return false;

        $this->dao->delete()->from(TABLE_CONFIG)->where('section')->eq('block')->andWhere('module')->eq('index')->exec();
        return true;
    }

    /**
     * Change buildin entry name.
     * 
     * @access public
     * @return bool
     */
    public function changeBuildinName()
    {
        $this->app->loadLang('install', 'sys');

        foreach($this->lang->install->buildinEntry as $code => $name)
        {
            $this->dao->update(TABLE_ENTRY)
                ->set('name')->eq($name['name'])
                ->set('abbr')->eq($name['abbr'])
                ->set('buildin')->eq(1)
                ->set('integration')->eq(1)
                ->set('visible')->eq(1)
                ->where('code')->eq($code)
                ->exec();
        }

        if(dao::isError()) return false;
        return true;
    }

    /**
     * Compute contacteddate and contactedby fields.
     * 
     * @access public
     * @return void
     */
    public function computeContactInfo()
    {
        $orders    = $this->dao->select('id')->from(TABLE_ORDER)->fetchAll();
        $customers = $this->dao->select('id')->from(TABLE_CUSTOMER)->fetchAll();
        $contracts = $this->dao->select('id')->from(TABLE_CONTRACT)->fetchAll();
        $contacts  = $this->dao->select('id')->from(TABLE_CONTACT)->fetchAll();

        foreach($orders as $order)
        {
            $lastContact = $this->dao->select('actor as contactedBy, date as contactedDate')->from(TABLE_ACTION)
                ->where('action')->eq('record')
                ->andWhere('objectType')->eq('order')
                ->andWhere('objectID')->eq($order->id)
                ->orderBY('date_desc')
                ->limit(1)
                ->fetch();
            if($lastContact) $this->dao->update(TABLE_ORDER)->data($lastContact)->where('id')->eq($order->id)->exec();
        }

        foreach($customers as $customer)
        {
            $lastContact = $this->dao->select('actor as contactedBy, date as contactedDate')->from(TABLE_ACTION)
                ->where('action')->eq('record')
                ->andWhere('customer')->eq($customer->id)
                ->orderBY('date_desc')
                ->limit(1)
                ->fetch();
            if($lastContact) $this->dao->update(TABLE_CUSTOMER)->data($lastContact)->where('id')->eq($customer->id)->exec();
        }

        foreach($contacts as $contact)
        {
            $lastContact = $this->dao->select('actor as contactedBy, date as contactedDate')->from(TABLE_ACTION)
                ->where('action')->eq('record')
                ->andWhere('contact')->eq($contact->id)
                ->orderBY('date_desc')
                ->limit(1)
                ->fetch();
            if($lastContact) $this->dao->update(TABLE_CONTACT)->data($lastContact)->where('id')->eq($contact->id)->exec();
        }

        foreach($contracts as $contract)
        {
            $lastContact = $this->dao->select('actor as contactedBy, date as contactedDate')->from(TABLE_ACTION)
                ->where('action')->eq('record')
                ->andWhere('objectType')->eq('contract')
                ->andWhere('objectID')->eq($contract->id)
                ->orderBY('date_desc')
                ->limit(1)
                ->fetch();
            if($lastContact) $this->dao->update(TABLE_CONTRACT)->data($lastContact)->where('id')->eq($contract->id)->exec();
        }

        return !dao::isError();

    }

    /**
     * Set content of company when upgrade from 1.3.beta.
     * 
     * @access public
     * @return void
     */
    public function setCompanyContent()
    {
        if(empty($this->config->company->content) and $this->config->company->desc)
        {
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($this->config->company->desc)->where('`key`')->eq('content')->andWhere('section')->eq('company')->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('`key`')->eq('desc')->andWhere('section')->eq('company')->exec();
        }
        return !dao::isError();
    }

    /**
     * Set name of contract when upgrade from 1.4.beta.
     * 
     * @access public
     * @return void
     */
    public function upgradeContractName()
    {
        $contracts = $this->dao->select('*')->from(TABLE_CONTRACT)->fetchAll();

        foreach($contracts as $contract)
        {
            $name = preg_replace('/^\[(\d+)\]/', '', $contract->name);
            $this->dao->update(TABLE_CONTRACT)->set('name')->eq($name)->where('id')->eq($contract->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Update project member.
     * 
     * @access public
     * @return void
     */
    public function upgradeProjectMember()
    {
        $projects = $this->loadModel('project', 'oa')->getList();
        foreach($projects as $project)
        {
            $member = new stdclass();
            $member->type = 'project';
            $member->id   = $project->id;
 
            /* Move master to team table. */
            if(!empty($project->master))
            {
                $member->account = $project->master;
                $member->role    = 'role';
                $this->dao->replace(TABLE_TEAM)->data($member)->exec();
            }

            /* Move members to team table. */
            if(!empty($project->member))
            {
                $members = explode(',', $project->member);
                $member->role = 'member';
                foreach($members as $account)
                {
                    if($account == $project->master) continue;
                    if(!validater::checkAccount($account)) continue;

                    $member->account = $account;
                    $this->dao->replace(TABLE_TEAM)->data($member)->exec();
                }
            }

            return true;
        }
    }

    /**
     * Change system application logo path to relative path.
     * 
     * @access public
     * @return void
     */
    public function upgradeEntryLogo()
    {
        $entryList = array('crm', 'cash', 'oa', 'team');
        foreach($entryList as $entry)
        {
            $entryObj = $this->dao->select('*')->from(TABLE_ENTRY)->where('code')->eq($entry)->fetch();
            $path     = substr($entryObj->logo, strpos($entryObj->logo, 'theme'));
            $this->dao->update(TABLE_ENTRY)->set('logo')->eq($path)->where('code')->eq($entry)->exec();
        }
    }

    /**
     * Update return records.
     * 
     * @access public
     * @return bool
     */
    public function upgradeReturnRecords()
    {
        $contracts = $this->dao->select('*')->from(TABLE_CONTRACT)->where('`return`')->eq('done')->fetchAll();
        if(empty($contracts)) return false;

        foreach($contracts as $contract)
        {
            $data = new stdclass();
            $data->contract     = $contract->id;
            $data->amount       = $contract->amount;
            $data->returnedBy   = $contract->returnedBy;
            $data->returnedDate = $contract->returnedDate;

            $this->dao->insert(TABLE_PLAN)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Update delivery records.
     * 
     * @access public
     * @return bool
     */
    public function upgradeDeliveryRecords()
    {
        $contracts = $this->dao->select('*')->from(TABLE_CONTRACT)->where('`delivery`')->eq('done')->fetchAll();
        if(empty($contracts)) return false;

        foreach($contracts as $contract)
        {
            $data = new stdclass();
            $data->contract      = $contract->id;
            $data->deliveredBy   = $contract->deliveredBy;
            $data->deliveredDate = $contract->deliveredDate;

            $this->dao->insert(TABLE_DELIVERY)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Add search priv when upgrade 1.5.beta.
     * 
     * @access public
     * @return void
     */
    public function addSearchPriv()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->fetchAll('id');
        foreach($groups as $group)
        {
            $priv = new stdclass();
            $priv->group  = $group->id;
            $priv->module = 'search';
            $priv->method = 'buildForm';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'buildQuery';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'saveQuery';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'deleteQuery';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
        }

        return !dao::isError();
    }

    /**
     * Safe drop columns.
     * 
     * @param string $table 
     * @param string $columns 
     * @access public
     * @return bool
     */
    public function safeDropColumns($table, $columns)
    {
        if($columns == '') return false;

        $fieldsOBJ = $this->dao->query('desc ' . TABLE_PROJECT);
        while($field = $fieldsOBJ->fetch())
        {
            $fields[$field->Field] = $field->Field;
        }

        $columns = explode(',', $columns);
        foreach($columns as $column)
        {
            if($column == '') continue;
            if(isset($fields[$column]))
            {
                $this->dao->query("ALTER TABLE $table DROP $column;");
            }
        }

        return true;
    }

    /**
     * Add app priv when upgrade from 1.6.
     * 
     * @access public
     * @return void
     */
    public function addPrivs()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->fetchAll('id');

        foreach($groups as $group)
        {
            if($group->id == 1)
            {
                $privs = array('balance', 'depositor', 'order', 'product', 'project', 'schema', 'setting', 'task', 'trade');

                $modules['balance']   = array('browse', 'create', 'delete', 'edit');
                $modules['depositor'] = array('activate', 'browse', 'check', 'create', 'delete', 'edit', 'forbid', 'savebalance');
                $modules['order']     = array('delete');
                $modules['product']   = array('view');
                $modules['project']   = array('activate', 'suspend');
                $modules['schema']    = array('browse', 'create', 'delete', 'edit', 'view');
                $modules['setting']   = array('lang', 'reset');
                $modules['task']      = array('kanban', 'outline', 'start');
                $modules['trade']     = array('batchCreate', 'batchEdit', 'browse', 'create', 'delete', 'detail', 'edit', 'import', 'showimport', 'transfer');

                foreach($privs as $module)
                {
                    $priv = new stdclass();
                    $priv->group  = 1;
                    $priv->module = $module;

                    foreach($modules[$module] as $method)
                    {
                        $priv->method = $method;
                        $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
                    }
                }
            }

            if($group->id == 2)
            {
                $priv = new stdclass();
                $priv->group  = 2;
                $priv->module = 'depositor';
                $priv->method = 'delete';
                $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

                $priv->method = 'savabalance';
                $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
            }

            if($group->id == 3)
            {
                $priv = new stdclass();
                $priv->group  = 3;
                $priv->module = 'project';

                $methods = array('activate', 'finish', 'index', 'suspend');
                foreach($methods as $method)
                {
                    $priv->method = $method;
                    $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
                }
            }

            $priv = new stdclass();
            $priv->group  = $group->id;
            $priv->module = 'apppriv';
            $priv->method = 'crm';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'cash';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'oa';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'team';
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
        }

        return !dao::isError();
    }
}
