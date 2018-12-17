<?php
/* Smarty version 3.1.32, created on 2018-09-25 16:10:38
  from 'F:\PHP Develop\2018\09-25phpblogd2\products1\Application\View\Admin\products_list.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5ba9ed7e2dd8f7_87489902',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '39f978a6e0a89766ca9a45581cf3425d0ad9862d' => 
    array (
      0 => 'F:\\PHP Develop\\2018\\09-25phpblogd2\\products1\\Application\\View\\Admin\\products_list.html',
      1 => 1537522654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ba9ed7e2dd8f7_87489902 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<table border='1' width='980' bordercolor='#000000' align='center'>
	<tr>
		<th>编号</th>
		<th>商品名称</th>
		<th>规格</th>
		<th>价格</th>
		<th>库存量</th>
		<th>删除</th>
	</tr>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'rows');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['rows']->value) {
?>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['rows']->value['proID'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['rows']->value['proname'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['rows']->value['proguige'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['rows']->value['proprice'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['rows']->value['proamount'];?>
</td>
		<td> <a href="index.php?p=Admin&c=Products&a=del&proid=<?php echo $_smarty_tpl->tpl_vars['rows']->value['proID'];?>
">删除</a> </td>
	</tr>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</table>
</body>
</html><?php }
}
