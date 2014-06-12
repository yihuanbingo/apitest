// JavaScript Document
/* 
 * ajax提交表单，全局通用 
 @ param string 
*/
function AjaxSubmit(formid,id,location)
{ 
  //$("#ajaxMsg").hformide();
  //$("#ajaxMsg").html("<img src='/templates/images/loading.gif'>");
  $("#"+id).attr("disabled","disabled");
  $("#"+id).attr("class","submited");
  $("#"+formid).ajaxSubmit({  
  success:function(data)
  { 
  	 
	 var data = json_decode(data);
	 if(data.error==1)
	 { 
       //$("#ajaxMsg").html(data.data);
	   //$("#ajaxMsg").show();
	   alertMsg(data.data);
	   $("#"+id).attr("class","submit");
	   $("#"+id).removeAttr("disabled");
	 }
	 if(data.error==0)
	 { 
	    
       	if(location==true)  // 不输出信息，直接跳转
	    {
	      window.location.href = data.href;	 
	    }
		else
		{ 
          if(data.href)  // 输出信息，跳转其他页
		  { 
		      //$("#alt_msg_btm").html('2秒后页面自动跳转');
		      alertMsg(data.data);
			  setTimeout(function(){window.location.href=data.href},2000);
		  }
          else  // 输出信息，本页刷新
		  {
		      //$("#alt_msg_btm").html('2秒后页面自动刷新');
		      alertMsg(data.data);			  
		      setTimeout("window.location.reload();",2000);
		  }
		}
	 }  			
   }
 });		
}