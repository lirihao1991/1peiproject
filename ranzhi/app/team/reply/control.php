<?php
/**
 * The control file of reply module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     reply
 * @version     $Id: control.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
class reply extends control
{
    /**
     * Reply a thread.
     * 
     * @param  int      $threadID 
     * @access public
     * @return void
     */
    public function post($threadID)
    {
        if($this->app->user->account == 'guest') die(js::locate($this->createLink('user', 'login')));

        if($_POST)
        {
            $replyID = $this->reply->post($threadID);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('thread', 'locate', "threadID=$threadID&replyID=$replyID")));
        }
    }

    /**
     * Manage replies.
     * 
     * @access public
     * @return void
     */
    public function admin($orderBy = 'createdDate_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager   = new pager($recTotal, $recPerPage, $pageID);
        $replies = $this->reply->getList($orderBy, $pager);

        $this->lang->menuGroups->reply = 'forum';

        $this->view->title   = $this->lang->reply->admin;
        $this->view->replies = $replies;
        $this->view->pager   = $pager;
        $this->display(); 
    }

    /**
     * Edit a reply.
     * 
     * @param string $replyID 
     * @access public
     * @return void
     */
    public function edit($replyID)
    {
        if($this->app->user->account == 'guest') die(js::locate($this->createLink('user', 'login')));

        /* Judge current user has priviledge to edit the reply or not. */
        $reply = $this->reply->getByID($replyID);
        if(!$reply) die(js::locate('back'));

        $thread = $this->loadModel('thread')->getByID($reply->thread);
        if(!$this->thread->canManage($thread->board, $reply->author)) die(js::locate('back'));
        
        $this->thread->setEditor($thread->board, 'edit');
        
        if($_POST)
        {
            $this->reply->update($replyID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('thread', 'view', "threaID=$thread->id")));
        }

        $this->view->title  = $this->lang->reply->edit . $this->lang->colon . $thread->title;
        $this->view->reply  = $reply;
        $this->view->thread = $thread;
        $this->view->board  = $this->loadModel('tree')->getById($thread->board);
        $this->view->boards = $this->loadModel('forum')->getBoards();

        $this->display();
    }

    /**
     * Delete a reply.
     * 
     * @param  int      $replyID 
     * @access public
     * @return void
     */
    public function delete($replyID)
    {
        $reply = $this->reply->getByID($replyID);
        if(!$reply) $this->send(array('result' => 'fail', 'message' => 'Not found'));

        $thread = $this->loadModel('thread')->getByID($reply->thread);
        if(!$this->thread->canManage($thread->board)) $this->send(array('result' => 'fail'));

        if(RUN_MODE == 'admin')
        {
            $locate = helper::createLink('reply', 'admin');
        }
        else
        {
            $locate = helper::createLink('thread', 'view', "threadID=$reply->thread");
        }
        if($this->reply->delete($replyID)) $this->send(array('result' => 'success', 'locate' => $locate));

        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * Delete a file.
     * 
     * @param  int    $replyID 
     * @param  int    $fileID 
     * @access public
     * @return void
     */
    public function deleteFile($replyID, $fileID)
    {
        if($this->app->user->account == 'guest') $this->send(array('result'=>'fail', 'message'=> 'guest'));

        $reply = $this->reply->getByID($replyID);
        if(!$reply) $this->send(array('result'=>'fail', 'message'=> 'data error'));

        $thread = $this->loadModel('thread')->getByID($reply->thread);
        if(!$thread) $this->send(array('result'=>'fail', 'message'=> 'data error'));

        /* Judge current user has priviledge to edit the reply or not. */
        if($this->thread->canManage($thread->board, $reply->author))
        {
            if($this->loadModel('file')->delete($fileID)) $this->send(array('result'=>'success'));
        }
        $this->send(array('result'=>'fail', 'message'=> 'error'));
    }
}
