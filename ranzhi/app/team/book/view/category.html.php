<?php
/**
 * The book category view file of tree module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     tree
 * @version     $Id: category.html.php 2164 2014-12-22 01:32:10Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.admin.html.php';?>
<?php 
js::set('root', $root);
js::set('book', $book);
js::set('type', $type);
?>
<?php echo $categoryBox;?>
</div>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/footer.html.php';?>
