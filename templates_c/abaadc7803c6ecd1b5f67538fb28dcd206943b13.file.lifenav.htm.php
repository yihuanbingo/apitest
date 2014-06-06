<?php /* Smarty version Smarty-3.1.7, created on 2014-06-03 18:56:54
         compiled from "./templates/lifenav.htm" */ ?>
<?php /*%%SmartyHeaderCode:1991806687538bdfe34d7767-60890969%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'abaadc7803c6ecd1b5f67538fb28dcd206943b13' => 
    array (
      0 => './templates/lifenav.htm',
      1 => 1401792976,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1991806687538bdfe34d7767-60890969',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_538bdfe3512d9',
  'variables' => 
  array (
    'lifenav_cat_name' => 0,
    'lifenav_cat_id' => 0,
    'list' => 0,
    'l' => 0,
    'sc' => 0,
    'k' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_538bdfe3512d9')) {function content_538bdfe3512d9($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="gheader">
  <a href="lifenav.html"><div class="gtitle">生活百事通</div></a>
  <?php if (!empty($_smarty_tpl->tpl_vars['lifenav_cat_name']->value)){?>
   <div class="float_l" style="font-size:14px;line-height:45px">&ensp;- <?php echo $_smarty_tpl->tpl_vars['lifenav_cat_name']->value;?>
</div>
  <?php }?> 
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">

<?php if ($_smarty_tpl->tpl_vars['lifenav_cat_id']->value==0){?>
 <?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
   <div style="font-size:18px;padding:5px 5px 0;"><?php echo $_smarty_tpl->tpl_vars['l']->value['cat_name'];?>
</div>
   <?php  $_smarty_tpl->tpl_vars['sc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['l']->value['sub_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sc']->key => $_smarty_tpl->tpl_vars['sc']->value){
$_smarty_tpl->tpl_vars['sc']->_loop = true;
?>
    <div style="width:33%;float:left;">
	  <div style="width:95%;margin:5px auto;">
	   <a href="lifenav.html?lifenav_cat_id=<?php echo $_smarty_tpl->tpl_vars['sc']->value['cat_id'];?>
">
	     <img src="/templates/images/lifenav/<?php echo $_smarty_tpl->tpl_vars['sc']->value['cat_id'];?>
.jpg" width="100%" >
	   </a> 
	  </div>
	</div>
   <?php } ?>
   <div class="clear"></div>
 <?php } ?>
<?php }else{ ?>
 <?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
   <div style="border-bottom:1px solid #ddd;padding:5px;line-height:150%;font-size:15px">
   <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['l']->value['content']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
     <span class="color999 float_l" style="width:28%"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
：</span>
	 <span class="float_l" style="width:72%"><?php if (strstr($_smarty_tpl->tpl_vars['k']->value,'电话')){?><a href="tel:<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" class="blue"><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
<?php }?></span>
	 <div class="clear"></div>
   <?php } ?> 
   </div>
 <?php } ?>
 <div style="padding:0 5px">
  <?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
	
 </div>
<?php }?>

</div>
</div>
</div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>