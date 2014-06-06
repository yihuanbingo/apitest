<?php /* Smarty version Smarty-3.1.7, created on 2014-05-29 15:50:57
         compiled from "./templates/bind.htm" */ ?>
<?php /*%%SmartyHeaderCode:1080908349536d95d2657523-20227772%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be4136f7dcec7fdb48d1ee3b29ce91afa7f244ee' => 
    array (
      0 => './templates/bind.htm',
      1 => 1401349855,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1080908349536d95d2657523-20227772',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_536d95d269473',
  'variables' => 
  array (
    'act' => 0,
    'keywords' => 0,
    'res' => 0,
    'r' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536d95d269473')) {function content_536d95d269473($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="gheader">
  <?php if ($_smarty_tpl->tpl_vars['act']->value=='householder'){?><a href="bind.html?act=householder"><div class="gtitle">绑定物业</div></a><?php }else{ ?>
  <a href="bind.html"><div class="gtitle">绑定小区</div></a><?php }?>
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">


<?php if ($_smarty_tpl->tpl_vars['act']->value=='community'){?>
<form style="margin:10px auto 0;" action="bind.html" method="post" enctype="multipart/form-data" id="bind">
<table width="100%" cellspacing="10px">
<tr>
 <td class="input"><input type="text" name="keywords" class="input" style="border:0" placeholder="请输入所在小区名称" value="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
"></td>
<tr>
 <td align="center">
    <input type="hidden" name="act" value="search" >
	<input type="hidden" name="type" value="community"> 
    <a href="javascript:void(0);" onclick="javascript:AjaxSubmit('bind',true);"><div class="submit">搜索小区</div></a>
 </td>
</tr>
</table>
</form>
 <div class="clear"></div>
 <?php if (!empty($_smarty_tpl->tpl_vars['keywords']->value)){?>
  <div style="padding:0 10px 10px;">
  <?php if (empty($_smarty_tpl->tpl_vars['res']->value)){?> 
    Sorry，没有找到 <span class="red"><?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
</span> 相关的小区！
  <?php }else{ ?>
  <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['res']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['r']->key;
?>
   <a href="javascript:void(0);" onclick="javascript:setCommunityBind(<?php echo $_smarty_tpl->tpl_vars['r']->value['community_id'];?>
);">
   <div class="srp" style="border:1px solid #ddd;<?php if ($_smarty_tpl->tpl_vars['k']->value>0){?>border-top:0<?php }?>">
    <div class="titleContent">
	  <?php echo $_smarty_tpl->tpl_vars['r']->value['community_name'];?>

	</div>
   </div>
   </a>
  <?php } ?>
  <?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  <script type="text/javascript">
   /* 绑定到小区 */
   function setCommunityBind(community_id)
   {
      if(confirm("确认绑定到该小区？若已绑定小区，该操作会解绑原来的小区，并重新绑定！"))
	  {
	     $.post("bind.html", {
		 act:'act_community', community_id:community_id },
    function(data)
    {
	   var data = json_decode(data);
	   if(data.error==0)
	   {
	     alertMsg(data.data);
		 if(data.href)
		 {
		     setTimeout(function(){
			 window.location.href=data.href},1500);
		 }
		 else
		 {
	         setTimeout("window.location.reload();",1500);
	     }
	   }
	   if(data.error==1)
	   {
		 confirm(data.data);   
	   }
    });
	  }   
   }
  </script>
  <?php }?>
  </div>
 <?php }?>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='householder'){?>
<form style="margin:10px auto 0;" action="bind.html" method="post" enctype="multipart/form-data" id="bind">
<table width="100%" cellspacing="10px">
<tr>
 <td class="input">
  <input type="text" name="keywords" class="input" style="border:0" placeholder="请输入物业绑定码" value="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
 </td>
</tr> 
<tr>
 <td class="color999">您的<span class="red">绑定码</span>为业主留在物业中心的<font class="red">手机号后6位</font></td>
</tr>
<tr>
 <td align="center">
    <input type="hidden" name="act" value="search" >
	<input type="hidden" name="type" value="householder"> 
    <a href="javascript:void(0);" onclick="javascript:AjaxSubmit('bind',true);"><div class="submit">提交绑定</div></a>
 </td>
</tr>
</table>
</form>
 <div class="clear"></div>
 <?php if (!empty($_smarty_tpl->tpl_vars['keywords']->value)){?>
  <div style="padding:0 10px 10px;">
  <?php if (empty($_smarty_tpl->tpl_vars['res']->value)){?> 
    Sorry，没有找到 <span class="red"><?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
</span> 相关的单元房号！
  <?php }else{ ?>
  <script type="text/javascript">
   /* 绑定到小区 */
   function setHouseholderBind(householder_id,house_number)
   {
      if(confirm("房号："+house_number+"，确认绑定到该单元房号？若已绑定过房号，该操作会解绑原来的房号，并重新绑定！"))
	  {
	     $.post("bind.html", {
		 act:'act_householder', householder_id:householder_id },
    function(data)
    {
	   var data = json_decode(data);
	   if(data.error==0)
	   {
	     alertMsg(data.data);
		 if(data.href)
		 {
		     setTimeout(function(){
			 window.location.href=data.href},1500);
		 }
		 else
		 {
	         setTimeout("window.location.reload();",1500);
	     }
	   }
	   if(data.error==1)
	   {
		 confirm(data.data);   
	   }
    });
	  }   
   }
  </script>
    请选择对应单元房号进行绑定：
	<div class="clear height10"></div>
  <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['res']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['r']->key;
?>
   <a href="javascript:void(0);" onclick="javascript:setHouseholderBind(<?php echo $_smarty_tpl->tpl_vars['r']->value['householder_id'];?>
,'<?php echo $_smarty_tpl->tpl_vars['r']->value['house_number'];?>
');">
   <div class="srp" style="border:1px solid #ddd;<?php if ($_smarty_tpl->tpl_vars['k']->value>0){?>border-top:0<?php }?>">
    <div class="titleContent">
	  <?php echo $_smarty_tpl->tpl_vars['r']->value['house_number'];?>

	</div>
   </div>
   </a>
   <?php if (count($_smarty_tpl->tpl_vars['res']->value)==1){?>
    <script type="text/javascript">
	  var householder_id = <?php echo $_smarty_tpl->tpl_vars['r']->value['householder_id'];?>
;
	  var house_number = '<?php echo $_smarty_tpl->tpl_vars['r']->value['house_number'];?>
';
	  setHouseholderBind(householder_id,house_number);
	</script>
  <?php }?>
  <?php } ?>
  <?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  <?php }?>
  </div>
 <?php }?>
<?php }?>


</div>
</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>