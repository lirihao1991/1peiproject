<?php
/**
 * The upgrade module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $$
 * @link        http://www.ranzhico.com
 */
$lang->upgrade = new stdclass();
$lang->upgrade->common  = '升级';

$lang->upgrade->result  = '升级结果';
$lang->upgrade->fail    = '升级失败';
$lang->upgrade->success = '升级成功';
$lang->upgrade->tohome  = '返回首页';

$lang->upgrade->index         = '检查是否可以执行升级程序';
$lang->upgrade->backup        = '备份数据';
$lang->upgrade->selectVersion = '确认升级之前的版本';
$lang->upgrade->confirm       = '确认要执行的SQL语句';
$lang->upgrade->execute       = '确认执行';
$lang->upgrade->next          = '下一步';
$lang->upgrade->redeploy      = '请重新部署app文件夹后继续';
$lang->upgrade->redeployDesc  = "<h5>因为代码结构调整,需要重新部署app目录。</h5><div class='text-important'>操作方法:删除旧的app目录，再从新的安装包里面复制app文件夹。</div>";

$lang->upgrade->backupData = <<<EOT
<pre>
<strong>使用phpMyAdmin或者mysqldump命令备份数据库。</strong>
<code class='red'>$ mysqldump -u %s</span> -p%s %s > ranzhi.sql</code>
</pre>
EOT;

$lang->upgrade->versionNote = "务必选择正确的版本，否则会造成数据丢失。";

$lang->upgrade->fromVersions['1_0_beta'] = '1.0.beta';
$lang->upgrade->fromVersions['1_1_beta'] = '1.1.beta';
$lang->upgrade->fromVersions['1_2_beta'] = '1.2.beta';
$lang->upgrade->fromVersions['1_3_beta'] = '1.3.beta';
$lang->upgrade->fromVersions['1_4_beta'] = '1.4.beta';
$lang->upgrade->fromVersions['1_5_beta'] = '1.5.beta';
$lang->upgrade->fromVersions['1_6']      = '1.6';
