<?php
/**
 * The edit view file of task module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php $this->loadModel('project')->setMenu($projects, $projectID);?>
<div class='with-menu page-content'>
  <form method='post' id='ajaxForm' enctype='multipart/form-data'>
    <div class='row'>
      <div class='col-md-8'>
        <div class='panel'>
          <div class='panel-heading'><strong><?php echo $lang->task->edit;?></strong></div>
          <div class='panel-body'>
            <table class='table table-form table-data'>
              <tr>
                <th class='w-80px'><?php echo $lang->task->name?></th>
                <td><?php echo html::input('name', $task->name, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->desc?></th>
                <td><?php echo html::textarea('desc', $task->desc, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->files;?></th>
                <td><?php echo $this->fetch('file', 'buildForm')?></td>
              </tr>
            </table>
          </div>
        </div>
        <?php echo $this->fetch('action', 'history', "objectType=task&objectID={$task->id}");?>
        <?php echo html::hidden('referer', $this->server->http_referer);?>
        <div class='page-actions'>
          <?php
          $browseLink = $this->session->taskList ? $this->session->taskList : inlink('browse', "project=$task->project");
          echo html::submitButton() . html::a($browseLink, $lang->goback, "class='btn btn-default'");
          ?>
        </div>
      </div>
      <div class='col-md-4'>
        <div class='panel'>
          <div class='panel-heading'><strong><?php echo $lang->task->basicInfo;?></strong></div>
          <div class='panel-body'>
            <table class='table table-info'>
              <tr>
                <th class='w-80px'><?php echo $lang->task->project;?></th>
                <td><?php echo html::select('project', $projects, $task->project, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->assignedTo;?></th>
                <td><?php echo html::select('assignedTo', $members, $task->assignedTo, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->status;?></th>
                <td><?php echo html::select('status', $lang->task->statusList, $task->status, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->pri;?></th>
                <td><?php echo html::select('pri', $lang->task->priList, $task->pri, "class='form-control'")?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->deadline;?></th>
                <td><?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->estimate;?></th>
                <td><?php echo html::input('estimate', $task->estimate, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->consumed;?></th>
                <td><?php echo html::input('consumed', $task->consumed, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->left;?></th>
                <td><?php echo html::input('left', $task->left, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->mailto;?></th>
                <td><?php echo html::select('mailto[]', $users, $task->mailto, "class='form-control chosen' multiple data-placeholder='{$lang->task->mailtoPlaceholder}'");?></td>
              </tr>
            </table>
          </div>
        </div>
        <div class='panel'>
          <div class='panel-heading'><strong><?php echo $lang->task->life?></strong></div>
          <div class='panel-body'>
            <table class='table table-info'>
              <tr>
                <th class='w-80px'><?php echo $lang->task->createdBy;?></th>
                <td><?php echo zget($users, $task->createdBy, $task->createdBy)?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedBy;?></th>
                <td><?php echo html::select('finishedBy', $users, $task->finishedBy, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedDate;?></th>
                <td><?php echo html::input('finishedDate', $task->finishedDate, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledBy;?></th>
                <td><?php echo html::select('canceledBy', $users, $task->canceledBy, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledDate;?></th>
                <td><?php echo html::input('canceledDate', $task->canceledDate, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedBy;?></th>
                <td><?php echo html::select('closedBy', $users, $task->closedBy, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedDate;?></th>
                <td><?php echo html::input('closedDate', $task->closedDate, "class='form-control form-date'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedReason;?></th>
                <td><?php echo html::select('closedReason', $lang->task->reasonList, $task->closedReason, "class='form-control'");?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
