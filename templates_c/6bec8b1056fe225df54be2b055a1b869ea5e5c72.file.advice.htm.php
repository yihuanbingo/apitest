<?php /* Smarty version Smarty-3.1.7, created on 2014-06-10 13:51:38
         compiled from "./templates/advice.htm" */ ?>
<?php /*%%SmartyHeaderCode:1178890434536343535f40c5-15972493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6bec8b1056fe225df54be2b055a1b869ea5e5c72' => 
    array (
      0 => './templates/advice.htm',
      1 => 1402378595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1178890434536343535f40c5-15972493',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_536343536309d',
  'variables' => 
  array (
    'act' => 0,
    'phone' => 0,
    'status' => 0,
    'log' => 0,
    'l' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536343536309d')) {function content_536343536309d($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="gheader">
  <a href="advice.html"><div class="gtitle">投诉建议</div></a>
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">

<div class="cardexplain">
 <?php if ($_smarty_tpl->tpl_vars['act']->value=='default'){?>
	<ul class="round">
	  <li>
		<a href="advice.html?act=mine"><span>我的投诉与建议<em class="ok"><?php echo insert_get_advice_mine(array(),$_smarty_tpl);?>
</em></span></a>
	  </li>
	</ul>
	<ul class="round">
	  <li>
		<h2>投诉建议说明</h2>
	    <div class="text">
		  您可以在这里输入对我们的建议或投诉，请输入您要投诉或建议的内容，我们会在第一时间进行答复。若是紧急情况，您也可以拨打投诉与建议电话：<?php echo $_smarty_tpl->tpl_vars['phone']->value;?>

		</div>
	  </li>
	</ul>

	<ul class="round">
		<li class="tel"><a href="tel:<?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
"><span>快捷拔号： <?php echo $_smarty_tpl->tpl_vars['phone']->value;?>
</span></a></li>
	</ul>

	<ul class="round">
		<form action="advice.html" method="post" enctype="multipart/form-data" id="advice">
		<li class="title mb"><span class="none">请认真填写表单</span></li>
		<li class="nob">
		<table width="100%" cellspacing="10px">
		 <tr>
		   <td><input type="text" name="name" placeholder="请输入姓名" style="border:1px solid #ccc;border-radius:5px;width:100%;height:30px;line-height:30px;text-indent:5px;font-size:16px;color:#666"></td>
		 </tr>
		 <tr>
		   <td><input type="text" name="phone" placeholder="请输入联系电话" style="border:1px solid #ccc;border-radius:5px;width:100%;height:30px;line-height:30px;text-indent:5px;font-size:16px;color:#666"></td>
		 </tr>
		 <tr>
		   <td>
		    <textarea name="content" placeholder="请输入投诉/建议内容" style="width:100%;height:80px;border-radius:5px;border:1px solid #ccc;line-height:30px;text-indent:5px;font-size:16px;color:#666"></textarea>
		   </td>
		 </tr>
		</table>
		</li>
		<input type="hidden" name="act" value="act_default">
		</form>
    </ul>
	
	<div class="footReturn">
		<input id="showcard" class="submit" value="确认提交投诉/建议内容" type="button" onclick="javascript:AjaxSubmit('advice');"> 
	</div>
 <?php }?>
 
 <?php if ($_smarty_tpl->tpl_vars['act']->value=='mine'){?>
    <ul class="round asklistnav">
	  <li style="width:42%;float:left;border:0;text-align:center;<?php if ($_smarty_tpl->tpl_vars['status']->value==0){?>background:#ccc<?php }?>">
		<a href="advice.html?act=mine"  ><span>待处理</span></a>
	  </li>
	  <li style="width:42%;float:right;border:0;text-align:center;<?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>background:#ccc<?php }?>">
		<a href="advice.html?act=mine&status=1"  ><span>已完成</span></a>
	  </li>
	  <div class="clear"></div>
	</ul>
   
   <?php if (!empty($_smarty_tpl->tpl_vars['log']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['log']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
	<ul class="round">
	  <li class="title">
		<span>
		  发起时间：<?php echo $_smarty_tpl->tpl_vars['l']->value['add_time'];?>

		  <em class="no"><?php if ($_smarty_tpl->tpl_vars['l']->value['status']==0){?>待处理<?php }?><?php if ($_smarty_tpl->tpl_vars['l']->value['status']==1){?>已处理<?php }?><?php if ($_smarty_tpl->tpl_vars['l']->value['status']==2){?>已撤销<?php }?></em>
		</span>	
	  </li>
	  <li>
	   <div class="text">
				<p>联系人：<?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</p>				
				<p>联系电话：<?php echo $_smarty_tpl->tpl_vars['l']->value['phone'];?>
</p>								
			    <p>投诉/建议：<?php echo $_smarty_tpl->tpl_vars['l']->value['content'];?>
</p>
                <?php if ($_smarty_tpl->tpl_vars['l']->value['reply']!=''){?><div class="color999"><p>物业反馈：<?php echo $_smarty_tpl->tpl_vars['l']->value['reply'];?>
</div></p><?php }?>
				<div class="footReturn">
				<?php if ($_smarty_tpl->tpl_vars['status']->value==0){?>							
				  <input  class="submit" value="撤销投诉/建议" type="button" onclick="javascript:canceladvice(<?php echo $_smarty_tpl->tpl_vars['l']->value['advice_id'];?>
);"/>
                  <script type="text/javascript">  
                  /* 撤销投诉/建议 */
                  function canceladvice(advice_id)
                  {
                     if(confirm("撤销后，该投诉或建议将不会被处理，确认撤销？"))
	                 {
	                     $.post("advice.html", {
		                                        act:'cancel', advice_id:advice_id },
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
				<?php }else{ ?>
				  <input  class="submit" value="删除投诉/建议" type="button" onclick="javascript:deleteadvice(<?php echo $_smarty_tpl->tpl_vars['l']->value['advice_id'];?>
);"/>
                  <script type="text/javascript">  
                  /* 撤销投诉/建议 */
                  function deleteadvice(advice_id)
                  {
                     if(confirm("撤销后，该投诉或建议将不会被保留，确认撤销？"))
	                 {
	                     $.post("advice.html", {
		                                        act:'delete', advice_id:advice_id },
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
	   </div>
	  </li>
    </ul>
	<?php } ?>
	<?php echo $_smarty_tpl->getSubTemplate ("library/page.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

   <?php }else{ ?>
     <p style="text-align:center">没有符合条件的投诉或建议哦！</p>
   <?php }?>
 <?php }?>
</div>

</div>
</div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>