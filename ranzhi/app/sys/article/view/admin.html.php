<?php
/**
 * The admin view file of article of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id: admin.html.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'><strong><i class="icon-list-ul"></i> <?php echo $lang->article->list;?></strong></div>
  <table class='table table-hover table-striped tablesorter'>
    <thead>
      <tr>
        <?php $vars = "type=$type&categoryID=$categoryID&corderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <th style='width: 60px' class='text-center'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->article->id);?></th>
        <th style='width: 80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->article->status);?></th>
        <th class='text-center'><?php commonModel::printOrderLink('title',     $orderBy, $vars, $lang->article->title);?></th>
        <th style='width: 100px' class='text-center'><?php commonModel::printOrderLink('category', $orderBy, $vars, $lang->article->category);?></th>
        <th style='width: 160px' class='text-center'><?php commonModel::printOrderLink('createdDate', $orderBy, $vars, $lang->article->createdDate);?></th>
        <th style='width: 60px' class='text-center'><?php commonModel::printOrderLink('views', $orderBy, $vars, $lang->article->views);?></th>
        <th style='width: 150px' class='text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $maxOrder = 0; foreach($articles as $article):?>
      <tr>
        <td class='text-center'><?php echo $article->id;?></td>
        <td><?php echo $article->status == 'draft' ? '<span class="text-info"><i class="icon-pencil"></i> ' . $lang->article->statusList[$article->status] .'</span>' : '<span class="text-success"><i class="icon-ok-sign"></i> ' . $lang->article->statusList[$article->status] . '</span>';?></td>
        <td><?php echo $article->title;?></td>
        <td class='text-center'><?php foreach($article->categories as $category) echo $category->name . ' ';?></td>
        <td class='text-center'><?php echo $article->createdDate;?></td>
        <td class='text-center'><?php echo $article->views;?></td>
        <td class='text-center'>
          <?php
          echo html::a($this->createLink('article', 'edit', "articleID=$article->id&type=$article->type"), $lang->edit);
          echo html::a($this->createLink('file', 'browse', "objectType=article&objectID=$article->id"), $lang->article->files, "data-toggle='modal'");
          echo html::a($this->createLink('article', 'delete', "articleID=$article->id"), $lang->delete, 'class="deleter"');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot><tr><td colspan='7'><?php $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
