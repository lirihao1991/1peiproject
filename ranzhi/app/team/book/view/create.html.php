<?php
/**
 * The create book view file of book of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Tingting Dai<daitingting@xirangit.com>
 * @package     book
 * @version     $Id: create.html.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.admin.html.php'; ?>
<div class='panel'>
  <div class='panel-heading'><strong><i class='icon-plust'></i> <?php echo $lang->book->createBook;?></strong></div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post' class='form-inline'>
      <table class='table table-form'>
        <tr>
          <th style='width: 100px'><?php echo $lang->book->author;?></th>
          <td><?php echo html::input('author', $app->user->realname, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><span><?php echo $lang->book->title;?></span></th>
          <td class='required'><?php echo html::input('title', '', 'class=form-control');?></td>
        </tr>
        <tr>
          <th><span><?php echo $lang->book->alias;?></span></th>
          <td class='required'>
            <div class='input-group'>
              <span class='input-group-addon'>http://<?php echo $this->server->http_host . $config->webRoot?>book/</span>
              <?php echo html::input('alias', '', "class='form-control' placeholder='{$lang->alias}'");?>
              <span class='input-group-addon'>.html</span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->book->keywords;?></th>
          <td><?php echo html::input('keywords', '', 'class=form-control');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->book->summary;?></th>
          <td><?php echo html::textarea('summary', '', "class='form-control' rows='3'");?></td>
        </tr>
        <tr>
          <th></th><td><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>


<?php include '../../common/view/footer.html.php';?>
