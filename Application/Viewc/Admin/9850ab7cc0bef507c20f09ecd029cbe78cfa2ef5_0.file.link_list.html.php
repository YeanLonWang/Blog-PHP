<?php
/* Smarty version 3.1.32, created on 2018-09-29 08:53:32
  from 'F:\PHP Develop\2018\09-28phpblogd4\products1\Application\View\Admin\link_list.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5baecd0c546d76_81843512',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9850ab7cc0bef507c20f09ecd029cbe78cfa2ef5' => 
    array (
      0 => 'F:\\PHP Develop\\2018\\09-28phpblogd4\\products1\\Application\\View\\Admin\\link_list.html',
      1 => 1538182326,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5baecd0c546d76_81843512 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
	*{
		font-size:14px;
	}
	table,td,th{
		border:#CCC solid 1px;
	}
	</style>
</head>
<body>
<a href="index.php?p=Admin&c=Link&a=add">添加链接地址</a>
<table border="1" width="560">
<tr>
	<th>编号</th> <th>链接名</th> <th>链接地址</th> <th>修改</th> <th>删除</th>
</tr>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'rows');
$_smarty_tpl->tpl_vars['rows']->iteration = 0;
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['rows']->value) {
$_smarty_tpl->tpl_vars['rows']->iteration++;
$__foreach_rows_0_saved = $_smarty_tpl->tpl_vars['rows'];
?>
	<tr>
    	<td><?php echo $_smarty_tpl->tpl_vars['rows']->iteration;?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['rows']->value['link_name'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['rows']->value['link_url'];?>
</td>
        <td><a href="index.php?p=Admin&c=Link&a=alter&link_id=<?php echo $_smarty_tpl->tpl_vars['rows']->value['link_id'];?>
">修改</a></td>
        <td><a href="index.php?p=Admin&c=Link&a=del&link_id=<?php echo $_smarty_tpl->tpl_vars['rows']->value['link_id'];?>
">删除</a></td>
    </tr>
<?php
$_smarty_tpl->tpl_vars['rows'] = $__foreach_rows_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</body>
</html><?php }
}
