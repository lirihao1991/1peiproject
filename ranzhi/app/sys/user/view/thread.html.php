<?php
/**
 * The thread view file of user module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user 
 * @version     $Id: thread.html.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='page-user-control'>
  <div class='row'>
    <?php include './side.html.php';?>
    <div class='col-md-10'>
      <div class='panel'>
        <div class='panel-heading'><strong><i class='icon-share'></i> <?php echo $lang->user->thread;?></strong></div>
        <table class='table table-hover'>
          <thead>
            <tr class='text-center'>
              <th><?php echo $lang->thread->title;?></th>
              <th><?php echo $lang->thread->postedDate;?></th>
              <th><?php echo $lang->thread->views;?></th>
              <th><?php echo $lang->thread->replies;?></th>
              <th colspan='2'><?php echo $lang->thread->lastReply;?></th>
            </tr>  
          </thead>
          <tbody>
            <?php foreach($threads as $thread):?>
            <tr class='text-center'>
              <td class='text-left'><?php echo html::a($this->createLink('thread', 'view', "id=$thread->id"), $thread->title, "target='_blank'");?></td>
              <td style='width: 120px'><?php echo substr($thread->createdDate, 2, -3);?></td>
              <td style='width: 50px'><?php echo $thread->views;?></td>
              <td style='width: 50px'><?php echo $thread->replies;?></td>
              <td style='width: 200px' class='text-left'><?php if($thread->replies) echo substr($thread->repliedDate, 2, -3) . ' ' . $thread->repliedBy;?></td>  
            </tr>  
            <?php endforeach;?>
          </tbody>
          <tfoot><tr><td colspan='5'><?php $pager->show('right', 'short');?></td></tr></tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
