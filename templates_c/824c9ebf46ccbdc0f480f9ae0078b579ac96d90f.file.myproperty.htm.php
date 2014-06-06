<?php /* Smarty version Smarty-3.1.7, created on 2014-06-03 19:02:57
         compiled from "./templates/myproperty.htm" */ ?>
<?php /*%%SmartyHeaderCode:14469623285372f75c67b655-38043123%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '824c9ebf46ccbdc0f480f9ae0078b579ac96d90f' => 
    array (
      0 => './templates/myproperty.htm',
      1 => 1401793001,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14469623285372f75c67b655-38043123',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5372f75c6a6b7',
  'variables' => 
  array (
    'act' => 0,
    'house_number' => 0,
    'phone' => 0,
    'log' => 0,
    'l' => 0,
    'row' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5372f75c6a6b7')) {function content_5372f75c6a6b7($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>




<div class="gheader">
  <a href="myproperty.html"><div class="gtitle">我的物业</div></a> 
  <div class="float_l" style="font-size:14px;line-height:45px">  - 
  <?php if ($_smarty_tpl->tpl_vars['act']->value=='default'){?><?php echo $_smarty_tpl->tpl_vars['house_number']->value;?>

			<?php }elseif($_smarty_tpl->tpl_vars['act']->value=='property'){?>物业费
			<?php }elseif($_smarty_tpl->tpl_vars['act']->value=='express'){?>代收快递
			<?php }elseif($_smarty_tpl->tpl_vars['act']->value=='park'){?>停车费
			<?php }?>
  </div>			
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">

<?php if ($_smarty_tpl->tpl_vars['act']->value=='default'){?>
<div style="padding:10px;">
   <a href="myproperty.html?act=property">
   <div class="srp" style="border:1px solid #ddd;">
    <div class="titleContent">
	  物业费查询
	</div>
   </div>
   </a>
   
   <a href="myproperty.html?act=express">
   <div class="srp" style="border:1px solid #ddd;border-top:0">
    <div class="titleContent">
	  代收快递
	</div>
   </div>
   </a>
   <a href="myproperty.html?act=park">
   <div class="srp" style="border:1px solid #ddd;border-top:0">
    <div class="titleContent">
	  停车费查询
	</div>
   </div>
   </a>
   <div class="clear height10"></div>
   <a href="bind.html?act=householder&redirectUrl=myproperty.html"><div class="submit">重新绑定物业</div></a>
</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='express'){?>
 <!--代收快递-->
 <form style="margin:10px auto 0;" action="myproperty.html" method="post" enctype="multipart/form-data" id="myproperty">
<table width="100%" cellspacing="10px">
<tr>
 <td class="input"><input type="text" name="phone" class="input" style="border:0" placeholder="请输入收件人电话进行搜索" value="<?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
"></td>
<tr>
 <td align="center">
    <input type="hidden" name="act" value="search" >
    <a href="javascript:void(0);" onclick="javascript:AjaxSubmit('myproperty',true);"><div class="submit">搜索快递</div></a>
 </td>
</tr>
</table>
</form>

  <?php if (!empty($_smarty_tpl->tpl_vars['phone']->value)){?>
   <div class="cardexplain">
    <?php if (!empty($_smarty_tpl->tpl_vars['log']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['log']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
	<ul class="round">
	  <li class="title">
		<span>
		  代收时间：<?php echo $_smarty_tpl->tpl_vars['l']->value['update_time'];?>

		  <em class="no"><?php if ($_smarty_tpl->tpl_vars['l']->value['status']==0){?>待领取<?php }?><?php if ($_smarty_tpl->tpl_vars['l']->value['status']==1){?>已领取<?php }?></em>
		</span>	
	  </li>
	  <li>
	   <div class="text">
				<p>联系人：<?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</p>				
				<p>联系电话：<?php echo $_smarty_tpl->tpl_vars['l']->value['phone'];?>
</p>								
			    <p>快递公司：<?php echo $_smarty_tpl->tpl_vars['l']->value['express_name'];?>
</p>
				<p>快递单号：<?php echo $_smarty_tpl->tpl_vars['l']->value['express_sn'];?>
</p>
				<?php if ($_smarty_tpl->tpl_vars['l']->value['status']==0){?>							
	             <div class="footReturn">
				 <input  class="submit" value="设为已领取" type="button" onclick="javascript:setExpressReceived(<?php echo $_smarty_tpl->tpl_vars['l']->value['log_id'];?>
);"/>
  <script type="text/javascript">  
   /* 撤销报修 */
   function setExpressReceived(log_id)
   {
      if(confirm("确认将该快递设为已领取吗？"))
	  {
	     $.post("myproperty.html", {
		 act:'setExpressReceived', log_id:log_id },
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
				 </div>
	            <?php }?>
	   </div>
	  </li>
    </ul>
	<?php } ?>
	<?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

   <?php }else{ ?>
     <p style="text-align:center">没有找到 <font class="red"><?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
</font> 相关的代收快递哦！</p>
   <?php }?>
  </div>
  <?php }?>
<?php }?>

  <style type="text/css">
   table.property td{
   height: 35px;
   line-height:35px;
   padding-left: 10px;
   font-size:16px;
   }
  </style>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='property'){?>
 <div class="cardexplain">
  <?php if (!empty($_smarty_tpl->tpl_vars['row']->value['log_id'])){?>
  <table cellspacing="1" cellpadding="10" width="100%" bgcolor="#ffffff" class="property">
   <tr bgcolor="#ddeef2">
    <td>单元房号：</td><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['house_number']->value;?>
</td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td>物业费状态：</td><td colspan="2"><?php if ($_smarty_tpl->tpl_vars['row']->value['status']==0){?><font class="red">欠费</font><?php }else{ ?><font class="green">正常</font><?php }?></td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td>已交至：</td><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['row']->value['pay_late'];?>
</td>
   </tr>
   <?php if ($_smarty_tpl->tpl_vars['row']->value['status']==0){?>
   <tr bgcolor="#ddeef2">
	<td>欠费月数</td><td>每月应缴</td><td>欠费总额</td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td><font class="red"><?php echo $_smarty_tpl->tpl_vars['row']->value['arrearage_months'];?>
</font> 个月</td><td>¥ <?php echo $_smarty_tpl->tpl_vars['row']->value['pay_month'];?>
</td><td><span class="red">¥ <?php echo $_smarty_tpl->tpl_vars['row']->value['arrearage'];?>
</span></td>
   </tr>
   <?php }?>
  </table>
  <?php }else{ ?>
   <p style="text-align:center">单元房号为 <font class="red"><?php echo $_smarty_tpl->tpl_vars['house_number']->value;?>
</font> 的物业费信息还没有更新哦！</p>
  <?php }?>
 </div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['act']->value=='park'){?>
  <div class="cardexplain">
  <?php if (!empty($_smarty_tpl->tpl_vars['row']->value['log_id'])){?>
  <table cellspacing="1" cellpadding="10" width="100%" bgcolor="#ffffff" class="property">
   <tr bgcolor="#ddeef2">
    <td>单元房号：</td><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['house_number']->value;?>
</td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td>停车费状态：</td><td colspan="2"><?php if ($_smarty_tpl->tpl_vars['row']->value['status']==0){?><font class="red">欠费</font><?php }else{ ?><font class="green">正常</font><?php }?></td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td>已交至：</td><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['row']->value['pay_late'];?>
</td>
   </tr>
   <?php if ($_smarty_tpl->tpl_vars['row']->value['status']==0){?>
   <tr bgcolor="#ddeef2">
	<td>欠费月数</td><td>每月应缴</td><td>欠费总额</td>
   </tr>
   <tr bgcolor="#ddeef2">
	<td><font class="red"><?php echo $_smarty_tpl->tpl_vars['row']->value['arrearage_months'];?>
</font> 个月</td><td>¥ <?php echo $_smarty_tpl->tpl_vars['row']->value['pay_month'];?>
</td><td><span class="red">¥ <?php echo $_smarty_tpl->tpl_vars['row']->value['arrearage'];?>
</span></td>
   </tr>
   <?php }?>
  </table>
  <?php }else{ ?>
   <p style="text-align:center">没有找到单元房号为 <font class="red"><?php echo $_smarty_tpl->tpl_vars['house_number']->value;?>
</font> 的停车费信息哦！</p>
  <?php }?>
 </div>
<?php }?>

</div>
</div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>