<?php
/**
 * The article category zh-tw file of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id: zh-tw.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
$lang->article->common      = '文章維護';
$lang->article->createDraft = '保存草稿';

$lang->article->id          = '編號';
$lang->article->category    = '類目';
$lang->article->categories  = '類目';
$lang->article->title       = '標題';
$lang->article->alias       = '別名';
$lang->article->content     = '內容';
$lang->article->original    = '來源';
$lang->article->copySite    = '來源網站';
$lang->article->copyURL     = '來源URL';
$lang->article->keywords    = '關鍵字';
$lang->article->summary     = '摘要';
$lang->article->author      = '作者';
$lang->article->editor      = '編輯';
$lang->article->createdDate = '添加時間';
$lang->article->editedDate  = '編輯時間';
$lang->article->status      = '狀態';
$lang->article->type        = '類型';
$lang->article->views       = '閲讀';
$lang->article->stick       = '置頂級別';
$lang->article->order       = '排序';

$lang->article->list        = '文章列表';
$lang->article->admin       = '維護文章';
$lang->article->create      = '發佈文章';
$lang->article->edit        = '編輯文章';
$lang->article->files       = '附件';

if(!isset($lang->blog)) $lang->blog = new stdclass();
$lang->blog->admin  = '維護博客';
$lang->blog->list   = '博客列表';
$lang->blog->create = '發佈博客';
$lang->blog->edit   = '編輯博客';

if(!isset($lang->announce)) $lang->announce = new stdclass();
$lang->announce->admin  = '維護公告';
$lang->announce->list   = '公告列表';
$lang->announce->create = '發佈公告';
$lang->announce->edit   = '編輯公告';

$lang->page = new stdclass();
$lang->page->admin  = '維護單頁';
$lang->page->list   = '單頁列表';
$lang->page->create = '添加單頁';
$lang->page->edit   = '編輯單頁';

$lang->article->originalList[1] = '原創';
$lang->article->originalList[0] = '轉貼';

$lang->article->statusList['draft']  = '草稿';
$lang->article->statusList['normal'] = '正常';

$lang->article->confirmDelete = '您確定刪除該文章嗎？';

$lang->article->lblAddedDate = '<strong>添加時間：</strong> %s &nbsp;&nbsp;';
$lang->article->lblAuthor    = "<strong>作者：</strong> %s &nbsp;&nbsp;";
$lang->article->lblSource    = '<strong>來源：</strong>';
$lang->article->lblViews     = ' <strong>閲讀：</strong>%s';
$lang->article->lblEditor    = '<i>最後編輯：%s 于 %s</i>';

$lang->article->prev      = '上一篇';
$lang->article->next      = '下一篇';
$lang->article->none      = '沒有了';
$lang->article->directory = '返回目錄';
$lang->article->back2Top  = '返回頂部';

$lang->article->note = new stdclass();
$lang->article->note->createdDate = '可以延遲到選定的時間發佈。';
