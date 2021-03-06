<?php
/**
 * The deactivate view file of package module of RanZhi.
 *
 * @copyright   Copyright 2009-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     package
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<div class='alert alert-success'>
  <i class='icon-ok-sign'></i>
  <div class='content'>
    <h3><?php echo $title;?></h3>
    <?php if($removeCommands):?>
    <p><strong><?php echo $lang->package->unremovedFiles;?></strong></p>
    <p><?php echo join($removeCommands, '<br />');?></p>
    <?php endif;?>
    <div class='text-center'><?php echo html::a(inlink('browse', 'type=deactivated'), $lang->package->viewDeactivated, "class='btn'");?></div>
  </div>
</div>
<?php include '../../common/view/footer.modal.html.php';?>
