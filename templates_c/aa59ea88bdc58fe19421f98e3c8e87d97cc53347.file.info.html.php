<?php /* Smarty version Smarty-3.1.7, created on 2014-06-06 17:21:35
         compiled from "./templates/info.html" */ ?>
<?php /*%%SmartyHeaderCode:12117194335387f6eaab6959-64342616%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa59ea88bdc58fe19421f98e3c8e87d97cc53347' => 
    array (
      0 => './templates/info.html',
      1 => 1402046487,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12117194335387f6eaab6959-64342616',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5387f6eab2f87',
  'variables' => 
  array (
    'info_id' => 0,
    'infolist' => 0,
    'l' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5387f6eab2f87')) {function content_5387f6eab2f87($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if ($_smarty_tpl->tpl_vars['info_id']->value==0){?>
<!-- 公告列表 -->
<div class="gheader">
  <a href="info.html"><div class="gtitle">居家指南</div></a>
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">

 <div style="padding:0 5px">
  <?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['infolist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
  <div style="padding:5px 5px 0px 5px">
   <a href="info.html?info_id=<?php echo $_smarty_tpl->tpl_vars['l']->value['info_id'];?>
">
   <div style="width:100%;border-bottom:1px solid #ddd;padding: 0 0 0;">
    <div style="line-height:20px;margin-top:5px">
	  <p style="width:100%;float:left;font-size:18px;margin-top:0;padding:0 0 5px 0"><?php echo $_smarty_tpl->tpl_vars['l']->value['title'];?>
</p>
      
      <?php if (!empty($_smarty_tpl->tpl_vars['l']->value['first_pic'])){?>
	   <p><img src="<?php echo $_smarty_tpl->tpl_vars['l']->value['first_pic'];?>
" style="max-height:130px;"></p>
	  <?php }?>
       <p class="color999" style="line-height:130%;padding:20px 0 0 0;"><?php echo $_smarty_tpl->tpl_vars['l']->value['content'];?>
</p>
       <p style="text-align: right;padding:5px 0 0 0" class="color999">—<?php echo $_smarty_tpl->tpl_vars['l']->value['add_time'];?>
—</p>
	</div>
	
   </div>
   </a>
  </div>
  <?php } ?>
 <?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
	
 </div>
 </div>
</div>
</div>
 <?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
<!-- 公告详情 -->
<div class="onepageview">
 <h2><?php echo $_smarty_tpl->tpl_vars['row']->value['title'];?>
</h2>
 <hr>
 <div class="updatetime"><?php echo $_smarty_tpl->tpl_vars['row']->value['add_time'];?>
</div>
 <div class="content">
  <?php echo $_smarty_tpl->tpl_vars['row']->value['content'];?>
  
 </div>
<?php }?>
<?php }} ?>