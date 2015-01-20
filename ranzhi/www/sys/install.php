<?php
/**
 * The install router file of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     RanZhi
 * @version     $Id: install.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
error_reporting(E_ALL);
session_start();
define('RUN_MODE', 'install');

/* Load the framework. */
include '../../framework/router.class.php';
include '../../framework/control.class.php';
include '../../framework/model.class.php';
include '../../framework/helper.class.php';

/* Instance the app. */
$app = router::createApp('sys');

/* Check installed or not. */
if(!isset($_SESSION['installing']) and isset($config->installed) and $config->installed) die(header('location: index.php'));

/* Reset the config params to make sure the install program will be lauched. */
$config->set('requestType', 'GET');
$config->set('default.module', 'install');
$app->setDebug();

/* During the installation, if the database params is setted, auto connect the db. */
if(isset($config->installed) and $config->installed) $app->connectDB();

$app->parseRequest();
$app->loadModule();
