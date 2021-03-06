<?php
/**
 * The user module english file of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 2438 2015-01-12 09:42:58Z daitingting $
 * @link        http://www.ranzhico.com
 */
$lang->user->common    = 'User';

$lang->user->id        = 'ID';
$lang->user->account   = 'Account';
$lang->user->super     = 'Admin';
$lang->user->password  = 'Password';
$lang->user->password2 = 'Repeat it';
$lang->user->realname  = 'Name';
$lang->user->nickname  = 'Nickname';
$lang->user->dept      = 'Dept';
$lang->user->role      = 'Role';    
$lang->user->avatar    = 'Avatar';
$lang->user->birthyear = 'Birthyear';
$lang->user->birthday  = 'Birthday';
$lang->user->gender    = 'Gendar';
$lang->user->email     = 'Email';
$lang->user->msn       = 'MSN';
$lang->user->qq        = 'QQ';
$lang->user->yahoo     = 'Y!';
$lang->user->gtalk     = 'GTalk';
$lang->user->wangwang  = 'Wangwang';
$lang->user->mobile    = 'Mobile';
$lang->user->phone     = 'Phone';
$lang->user->dept      = 'Department';
$lang->user->address   = 'Address';
$lang->user->zipcode   = 'Zipcode';
$lang->user->join      = 'Join date';
$lang->user->visits    = 'Visits';
$lang->user->ip        = 'Last ip address';
$lang->user->last      = 'Last login';
$lang->user->allowTime = 'Allow time';
$lang->user->status    = 'Status';
$lang->user->alert     = 'Your account has been forbidden';

$lang->user->admin           = 'Admin';
$lang->user->list            = 'User list';
$lang->user->colleague       = 'Colleague list';
$lang->user->view            = "User info";
$lang->user->create          = "Add a user";
$lang->user->edit            = "Edit user";
$lang->user->changePassword  = "Change password";
$lang->user->recoverPassword = "recover password";
$lang->user->newPassword     = "New password";
$lang->user->update          = "Edit user";
$lang->user->delete          = "Delete user";
$lang->user->browse          = "Borwse";
$lang->user->deny            = "Access denied";
$lang->user->confirmDelete   = "Are you sure to delete this user?";
$lang->user->confirmActivate = "Are you sure to activate this user?";
$lang->user->relogin         = "Relogin";
$lang->user->asGuest         = "Visits as guest";
$lang->user->goback          = "Go back";
$lang->user->allUsers        = 'All users';
$lang->user->submit          = "Submit";
$lang->user->forbid          = 'Forbid';
$lang->user->active          = 'Active';
$lang->user->setReferer      = 'Set referer';
$lang->user->vcard           = 'Vcard';
$lang->user->uploadAvatar    = 'Upload avatar';
$lang->user->cropAvatar      = 'Crop avatar';
$lang->user->cropAvatarTip   = 'Drag border to crop avatar';

$lang->user->profile     = 'Profile';
$lang->user->editProfile = 'Edit profile';
$lang->user->thread      = 'My threads';
$lang->user->reply       = 'My replies';
$lang->user->message     = 'My message';

$lang->user->inputUserName       = 'Please input username';
$lang->user->inputColleague      = "Please input colleauge's name";
$lang->user->inputAccountOrEmail = 'Please input account or Email';
$lang->user->inputPassword       = 'Please input password';
$lang->user->searchUser          = 'Search';

$lang->user->errorDeny     = "Sorry, you don't have the permission to access <b>%s</b>'s<b>%s</b>. Please contact the administrator.<br/> This page will jump to homepage after 5 seconds";
$lang->user->loginFailed   = "Login failed, please check you account and password.";
$lang->user->locked        = "Failed too much, please login again after ten minutes";
$lang->user->lockedForEver = "User has been forbidden for ever.";
$lang->user->forbidSuccess = 'Successfully forbid.';
$lang->user->actionFail    = 'Failed action';
$lang->user->uploadSuccess = 'Successfully uploaded';

$lang->user->forbidUser = 'Manage user';
$lang->user->operate    = 'Operate';

$lang->user->genderList = $lang->genderList;

$lang->user->basicInfo   = 'Basic Info';
$lang->user->contactInfo = 'Contact Info';

$lang->user->statusList = new stdclass();
$lang->user->statusList->locked    = "<label class='label label-danger'>Locked</label>";
$lang->user->statusList->forbidden = "<label class='label label-danger'>Forbidden</label>";
$lang->user->statusList->normal    = "<label class='label label-success'>Normal</label>";

$lang->user->notice = new stdclass();
$lang->user->notice->password = 'Numbers and letters, at least six';

$lang->user->login  = new stdclass();
$lang->user->login->common  = "Login";
$lang->user->login->welcome = 'Welcome';
$lang->user->login->why     = 'Login, and use more feature.';

$lang->user->control = new stdclass();
$lang->user->control->common      = 'User dashboard';
$lang->user->control->welcome     = 'Welcome, <strong>%s</strong>';
$lang->user->control->lblPassword = "Keep empty, will not change it.";

$lang->user->control->menus[10] = '<i class="icon-large icon-user"></i> Profile <i class="icon-chevron-right"></i>|user|profile';
$lang->user->control->menus[20] = '<i class="icon-large icon-edit"></i> Edit <i class="icon-chevron-right"></i>|user|edit';
//$lang->user->control->menus[28] = '<i class="icon-large icon-comments-alt"></i> Messages <i class="icon-chevron-right"></i>|user|message';
$lang->user->control->menus[30] = '<i class="icon-large icon-share"></i> Threads <i class="icon-chevron-right"></i>|user|thread';
$lang->user->control->menus[40] = '<i class="icon-large icon-mail-reply-all"></i> Replies <i class="icon-chevron-right"></i>|user|reply';

$lang->user->colleagueMenu = 'Colleague';

$lang->dept = new stdclass();  
$lang->dept->common     = 'Dept';
$lang->dept->name       = 'Dept Name';
$lang->dept->alias      = 'Dept Alias';
$lang->dept->edit       = 'Edit Dept';
$lang->dept->parent     = 'Parent';
$lang->dept->children   = 'Children';
$lang->dept->desc       = 'Description';
$lang->dept->keywords   = 'Keywords';
$lang->dept->moderators = 'Moderator';
  
$lang->user->roleList['']           = ''; 
$lang->user->roleList['dev']        = 'Developer';
$lang->user->roleList['pm']         = 'Project Manager';
$lang->user->roleList['market']     = 'Marketing';
$lang->user->roleList['sale']       = 'Sale';
$lang->user->roleList['hr']         = 'HR';
$lang->user->roleList['office']     = 'Office';
$lang->user->roleList['service']    = 'Service';
$lang->user->roleList['support']    = 'Support';
$lang->user->roleList['marketmgr']  = 'Marketing Manager';
$lang->user->roleList['salemgr']    = 'Sale Manager';
$lang->user->roleList['hrmgr']      = 'HR Manager';
$lang->user->roleList['adminmgr']   = 'Office Manager';
$lang->user->roleList['servicemgr'] = 'Service Manager';
$lang->user->roleList['supportmgr'] = 'Support Manager';
$lang->user->roleList['top']        = 'Top manager';
$lang->user->roleList['others']     = 'Others';

$lang->user->mailContent = <<<EOT
<html>
<head>
<style type='text/css'>
body{margin:0; padding:0;}
div{padding-left:30px;}
</style>
</head>
<body>
<div style='padding-top:20px;height:60px;background:#fafafa;border-bottom:1px solid #ddd;font-size:18px;font-weight:bold'> 密码修改 </div>
<div style='margin-top:20px;'>
<p>
Hello, %s 
<br>
Please click the link to change your password:
<br>
<a href='%s' target='_blank'>%s</a>
</p>
<p>Reset Key: %s</p>
</div>
<div style='height:20px;border-bottom:1px solid #ddd;'></div>
<div style='margin:20px 0 0 0 ;'>
System letter, please do not reply
</div>
</body>
</html>
EOT;
