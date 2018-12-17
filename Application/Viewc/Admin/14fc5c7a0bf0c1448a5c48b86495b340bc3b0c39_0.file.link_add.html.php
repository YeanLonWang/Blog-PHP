<?php
/* Smarty version 3.1.32, created on 2018-09-29 08:30:17
  from 'F:\PHP Develop\2018\09-28phpblogd4\products1\Application\View\Admin\link_add.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5baec79964b371_61133553',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14fc5c7a0bf0c1448a5c48b86495b340bc3b0c39' => 
    array (
      0 => 'F:\\PHP Develop\\2018\\09-28phpblogd4\\products1\\Application\\View\\Admin\\link_add.html',
      1 => 1538180829,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5baec79964b371_61133553 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
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


	<form id="form1" name="form1" method="post" action="">
  <table width="500" border="1">
    <tr>
      <th colspan="2">添加链接</th>
    </tr>
    <tr>
      <td>名称：</td>
      <td><input type="text" name="link_name" id="link_name" /></td>
    </tr>
    <tr>
      <td>地址：</td>
      <td><input type="text" name="link_url" id="link_url" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" value="提交" /></td>
    </tr>
  </table>
</form>
</body>
</html><?php }
}
