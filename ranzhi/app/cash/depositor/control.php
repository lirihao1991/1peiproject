<?php
/**
 * The control file of depositor module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     depositor
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class depositor extends control
{
    /** 
     * The index page, locate to the browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * Browse depositor.
     * 
     * @param string $orderBy     the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->trades       = $this->depositor->getTradesAmount();
        $this->view->balances     = $this->loadModel('balance')->getLatest();
        $this->view->title        = $this->lang->depositor->browse;
        $this->view->depositors   = $this->depositor->getList($orderBy, $pager);
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->display();
    }   

    /**
     * Create a depositor.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $depositorID = $this->depositor->create(); 
            if(dao::isError())$this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('depositor', $depositorID, 'Created', '');

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title        = $this->lang->depositor->create;
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->display();
    }

    /**
     * Edit a depositor.
     * 
     * @param  int    $depositorID 
     * @access public
     * @return void
     */
    public function edit($depositorID)
    {
        if($_POST)
        {
            $changes = $this->depositor->update($depositorID);
            if(dao::isError())$this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('depositor', $depositorID, 'Edited', '');
                $this->action->logHistory($actionID, $changes);
            }
            
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title        = $this->lang->depositor->edit;
        $this->view->depositor    = $this->depositor->getByID($depositorID);
        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();

        $this->display();
    }

    /**
     * Forbid a depositor.
     * 
     * @param  int    $depositorID 
     * @access public
     * @return void
     */
    public function forbid($depositorID)
    {
        if($_POST)
        {
            $this->depositor->forbid($depositorID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('depositor', $depositorID, 'Forbidden', $this->post->comment);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title       = $this->lang->depositor->forbid;
        $this->view->depositorID = $depositorID;
        $this->display();
    }

    /**
     * Activate a depositor.
     * 
     * @param  int    $depositorID 
     * @access public
     * @return void
     */
    public function activate($depositorID)
    {
        if($_POST)
        {
            $this->depositor->activate($depositorID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('depositor', $depositorID, 'Activated', $this->post->comment);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title       = $this->lang->depositor->activate;
        $this->view->depositorID = $depositorID;
        $this->display();
    }

    /** 
     * Check depositors.
     * 
     * @param  int    $depositorID 
     * @access public
     * @return void
     */
    public function check($depositorID = 0)
    {
        unset($this->lang->depositor->menu);
        $this->lang->menuGroups->depositor = 'check';

        $selected = array($depositorID);
        
        if($_POST)
        {
            $selected = (array) $this->post->depositor;
            if(in_array('all', $selected)) $selected = array();
            $this->view->results    = $this->depositor->check($selected, $this->post->start, $this->post->end);
        }

        $this->view->start         = $this->post->start;
        $this->view->end           = $this->post->end;
        $this->view->title         = $this->lang->depositor->check;
        $this->view->selected      = $selected;
        $this->view->depositorList = $this->depositor->getPairs();
        $this->view->dateOptions   = (array) $this->loadModel('balance')->getDateOptions();
        $this->view->currencyList  = $this->loadModel('common', 'sys')->getCurrencyList();

        $this->display();
    } 

    /**
     * Save checked result as balance.
     * 
     * @param  int      $depositor 
     * @param  float    $money 
     * @param  int      $date 
     * @access public
     * @return void
     */
    public function saveBalance($depositor, $money, $date)
    {
        $depositor = $this->loadModel('depositor')->getByID($depositor);
        $balance = new stdclass();
        $balance->depositor   = $depositor->id;
        $balance->currency    = $depositor->currency;
        $balance->createdBy   = $this->app->user->account;
        $balance->createdDate = helper::now();
        $balance->money       = $money;
        $balance->date        = date(DT_DATE1, $date);

        $this->loadModel('balance')->create($balance);
        if(dao::isError())$this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('depositor', $this->post->depositor, 'CreatedBalance', $this->post->date . ':'  . $this->post->money . $this->post->currency);

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
    }

    /**
     * Delete a depositor.
     * 
     * @param  int      $depositorID 
     * @access public
     * @return void
     */
    public function delete($depositorID)
    {
        if($this->depositor->delete($depositorID)) $this->send(array('result' => 'success'));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
