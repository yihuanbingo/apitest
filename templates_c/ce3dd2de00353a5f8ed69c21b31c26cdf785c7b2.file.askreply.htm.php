<?php /* Smarty version Smarty-3.1.7, created on 2014-05-02 15:54:06
         compiled from "./templates/askreply.htm" */ ?>
<?php /*%%SmartyHeaderCode:112066239353634ee44ebfa9-49217989%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce3dd2de00353a5f8ed69c21b31c26cdf785c7b2' => 
    array (
      0 => './templates/askreply.htm',
      1 => 1399017241,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '112066239353634ee44ebfa9-49217989',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_53634ee451abd',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53634ee451abd')) {function content_53634ee451abd($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta charset="utf-8">
<script type="text/javascript" src="templates/js/jquery-1.8.0.min.js"></script>
</head>
<body id="top">

<link href="http://css.iwetao.net/templates/yuyue/css/yuyue.css" rel="stylesheet" type="text/css">

<div class="qiandaobanner"> 
   <img src="http://css.iwetao.net/templates/yuyue/images/head_pic.jpg">
</div>

<div class="cardexplain" style="padding-bottom:10px">
	<!--普通用户登录时显示-->
	<ul class="round">
	  <li>
		<a href="#"  ><span>我的报修申请<em class="ok">0</em></span></a>
	  </li>
	</ul>
	<!--后台可控制是否显示-->
	<ul class="round">
	  <li>
		<h2>报修说明</h2>
	    <div class="text">
		  您可以在这里输入您要报修的内容，我们会在2个工作日内进行处理，请保持手机通畅。若是紧急报修，请拨打紧急报修电话：028 - 86078388
		</div>
	  </li>
	</ul>

    <!--后台可控制是否显示-->
	<ul class="round">
		<li class="tel"><a href="tel:86078388"><span>快捷拔号： 028 - 86078388</span></a></li>
	</ul>

    <!--粉丝填写过的信息的，直接就显示名字电话，粉丝没有填写过信息的话，这里就直接留空让粉丝填写-->
	<ul class="round">
		<form action="javascript:;" method="post">
		<li class="title mb"><span class="none">请认真填写表单</span></li>
		<li class="nob">
		<table width="100%" cellspacing="10px">
		 <tr>
		   <td><input type="text" name="contact_user" placeholder="请输入姓名" style="border:1px solid #ccc;border-radius:5px;width:100%;height:30px;line-height:30px;text-indent:5px;font-size:16px;color:#666"></td>
		 </tr>
		 <tr>
		   <td><input type="text" name="contact" placeholder="请输入联系电话" style="border:1px solid #ccc;border-radius:5px;width:100%;height:30px;line-height:30px;text-indent:5px;font-size:16px;color:#666"></td>
		 </tr>
		 <tr>
		   <td>
		    <textarea name="detail" placeholder="请输入报修内容" style="width:100%;height:80px;border-radius:5px;border:1px solid #ccc;line-height:30px;text-indent:5px;font-size:16px;color:#666"></textarea>
		   </td>
		 </tr>
		</table>
		</li>
		</form>
    </ul>
	
	<div class="footReturn">
		<input id="showcard" class="submit" value="确认提交报修申请" type="button" > 
	</div>
</div>
<?php }} ?>