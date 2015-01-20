<?php
/**
 * The config file of tree module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     tree 
 * @version     $Id: config.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
$config->tree->require = new stdclass();
$config->tree->require->edit = 'name';

$config->tree->editor = new stdclass();
$config->tree->editor->edit = array('id' => 'desc', 'tools' => 'simple');

$config->tree->menuGroup = new stdclass();
$config->tree->menuGroup->setting  = ',forum,blog,area,industry,in,out,dept,';
$config->tree->menuGroup->category = ',article,announce,product,';
