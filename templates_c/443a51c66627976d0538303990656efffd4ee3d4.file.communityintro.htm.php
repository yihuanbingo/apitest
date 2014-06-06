<?php /* Smarty version Smarty-3.1.7, created on 2014-05-29 15:51:48
         compiled from "./templates/communityintro.htm" */ ?>
<?php /*%%SmartyHeaderCode:541644859536de314dfcec2-19512111%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '443a51c66627976d0538303990656efffd4ee3d4' => 
    array (
      0 => './templates/communityintro.htm',
      1 => 1401349906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '541644859536de314dfcec2-19512111',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_536de314e2fa5',
  'variables' => 
  array (
    'act' => 0,
    'community_name' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536de314e2fa5')) {function content_536de314e2fa5($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if ($_smarty_tpl->tpl_vars['act']->value=='default'){?>
<div class="gheader">
  <div class="gtitle">选择小区</div>
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">


<div style="padding:10px;">
   <a href="communityintro.html?act=intro">
   <div class="srp" style="border:1px solid #ddd;">
    <div class="titleContent">
	  <?php echo $_smarty_tpl->tpl_vars['community_name']->value;?>
介绍
	</div>
   </div>
   </a>
   
   <a href="bind.html?redirectUrl=communityintro.html">
   <div class="srp" style="border:1px solid #ddd;border-top:0">
    <div class="titleContent">
	  重新绑定
	</div>
   </div>
   </a>
</div>

</div>
</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='intro'){?>
<div class="onepageview">
 <h2><?php echo $_smarty_tpl->tpl_vars['row']->value['community_name'];?>
介绍</h2>
 <hr>
 <div class="updatetime"><?php echo $_smarty_tpl->tpl_vars['row']->value['verify_time'];?>
</div>
 <div class="content">
  <?php echo $_smarty_tpl->tpl_vars['row']->value['intro'];?>
  
 </div><br>
</div>
<?php }?><?php }} ?>