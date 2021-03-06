<?php
/**
 * The model file of tag module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     tag
 * @version     $Id: model.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
class tagModel extends model
{
    /**
     * Get tag list.
     * 
     * @param  string $tags 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($tags, $orderBy, $pager)
    {
        return $this->dao->select('*')
            ->from(TABLE_TAG)
            ->beginIf(!empty($tags))->where('tag')->in($tags)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }
    
    /**
     * Save tags.
     * 
     * @param  string    $tags 
     * @access public
     * @return void
     */
    public function save($tags)
    {
        $tags =  array_unique(explode(',', $tags));

        foreach($tags as $tag)
        {
            if(trim($tag) == '') continue;
            $rank  = $this->countRank($tag);
            $count = $this->dao->select('count(*) as count')->from(TABLE_TAG)->where('tag')->eq($tag)->fetch('count');

            if($count == 0)
            {
                $this->dao->insert(TABLE_TAG)->data(array('tag' => $tag, 'rank' => $rank))->exec();
            }
            else
            {
                $this->dao->update(TABLE_TAG)->set('rank')->eq($rank)->where('tag')->eq($tag)->exec();
            }
        }

        if(!dao::isError()) return true;
        return dao::geterror();
    }

    /**
     * Count rank of one tag.
     * 
     * @param  string    $tag 
     * @access public
     * @return int
     */
    public function countRank($tag)
    {
        $rank = $this->dao->select('count(*) as count')->from(TABLE_ARTICLE)->where("concat(',', keywords, ',')")->like("%,{$tag},%")->fetch('count');
        //$rank += $this->dao->select('count(*) as count')->from(TABLE_BOOK)->where("concat(',', keywords, ',')")->like("%,{$tag},%")->fetch('count');
        return $rank;
    }
}
