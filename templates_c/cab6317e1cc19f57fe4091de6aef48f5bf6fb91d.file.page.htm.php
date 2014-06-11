<?php /* Smarty version Smarty-3.1.7, created on 2014-06-10 13:51:26
         compiled from "./templates/library/page.htm" */ ?>
<?php /*%%SmartyHeaderCode:15258391355370762a035014-97844379%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cab6317e1cc19f57fe4091de6aef48f5bf6fb91d' => 
    array (
      0 => './templates/library/page.htm',
      1 => 1402378595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15258391355370762a035014-97844379',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5370762a09bf6',
  'variables' => 
  array (
    'pageF' => 0,
    'pageP' => 0,
    'pageNow' => 0,
    'pageAll' => 0,
    'pageArr' => 0,
    'p' => 0,
    'pageN' => 0,
    'pageL' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5370762a09bf6')) {function content_5370762a09bf6($_smarty_tpl) {?><div class="clear"></div>
<div class="pagenav-wrapper">
<div class="pagenav-content">   
<div class="pagenav">       
<div class="p-first <?php if (!$_smarty_tpl->tpl_vars['pageF']->value){?>p-gray<?php }?>"><a <?php if ($_smarty_tpl->tpl_vars['pageF']->value){?>href="<?php echo $_smarty_tpl->tpl_vars['pageF']->value;?>
"<?php }?>>首页</a></div>       
<div class="p-prev <?php if (!$_smarty_tpl->tpl_vars['pageP']->value){?>p-gray<?php }?>"><a <?php if ($_smarty_tpl->tpl_vars['pageP']->value){?>href="<?php echo $_smarty_tpl->tpl_vars['pageP']->value;?>
"<?php }?>>上一页</a></div>       
<div class="pagenav-cur"><div class="pagenav-text"> <span><?php echo $_smarty_tpl->tpl_vars['pageNow']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['pageAll']->value;?>
</span> <i></i></div>           
<select class="pagenav-select" onchange="window.location.href=this.value">             
<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageArr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
$_smarty_tpl->tpl_vars['p']->_loop = true;
?>
<option <?php if ($_smarty_tpl->tpl_vars['p']->value['num']==$_smarty_tpl->tpl_vars['pageNow']->value){?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['p']->value['uri'];?>
">第<?php echo $_smarty_tpl->tpl_vars['p']->value['num'];?>
页</option>
<?php } ?>                                                                           
</select>       
</div>       
<div class="p-next <?php if (!$_smarty_tpl->tpl_vars['pageN']->value){?>p-gray<?php }?>"><a <?php if ($_smarty_tpl->tpl_vars['pageN']->value){?>href="<?php echo $_smarty_tpl->tpl_vars['pageN']->value;?>
"<?php }?>>下一页</a></div>       
<div class="p-end <?php if (!$_smarty_tpl->tpl_vars['pageL']->value){?>p-gray<?php }?>"><a <?php if ($_smarty_tpl->tpl_vars['pageL']->value){?>href="<?php echo $_smarty_tpl->tpl_vars['pageL']->value;?>
"<?php }?>>末页</a></div>   
</div>
</div>
</div><?php }} ?>