function searchPHPSay()
{
	var word = $.trim($('input[name=q]').val());

	if( word.length < 2 || word.length > 15 )
	{
		alertMessage("输入字符长度范围：2～15");

		$('input[name=q]').focus();
		
		return false;
	}

	document.cookie = "searchSubmit=YES";
}

function locationHash()
{
	var h = window.location.hash;

	if ( h.substring(0,6) == "#reply" )
	{
		$("html,body").animate({scrollTop: $("#item-"+h.substr(6)).offset().top-42}, 1000);
	}
}

function parseCookie(varName)
{
	var srcCookie = window.document.cookie;

	if(srcCookie=="")
	{
		return "";
	}

	var nPos=srcCookie.lastIndexOf(varName+"=");
	
	if(nPos>0)
	{
		if(nPos>=2)
		{
			nPos=srcCookie.indexOf("; "+varName+"=",nPos-2);
		}
		else
		{
			nPos=srcCookie.indexOf("; "+varName+"=");
		}
	}

	if(nPos>=0)
	{
		nPos=srcCookie.indexOf('=',nPos)+1;

		var nTailPos=srcCookie.indexOf("; ",nPos);
		
		if(nTailPos>0)
		{
			return srcCookie.substring(nPos,nTailPos);
		}
		else
		{
			return srcCookie.substr(nPos);
		}
	}

	return "";
}

function uploadAvatar()
{
	$('#avatar-file').uploadify({
		'swf':'static/uploadify.swf',
      	'uploader':'post.php',
      	'method':'post',
      	'formData':{'do':'settingAvatar','COOKIE':'{"phpsay_uname":"'+parseCookie("phpsay_uname")+'","phpsay_secure":"'+parseCookie("phpsay_secure")+'"}'},
      	'buttonClass':'web-icon',
      	'auto':false,
      	'fileObjName':'avatar',
      	'buttonText':'',
      	'width':40,
      	'height':28,
      	'fileTypeDesc':'图片（*.jpg;*.jpeg;*.gif;*.png）',
      	'fileTypeExts':'*.jpg; *.jpeg; *.gif; *.png',
      	'fileSizeLimit':'1024KB',
      	'multi':false,
      	'queueSizeLimit':1,
      	'removeTimeout':0,
      	'overrideEvents':['onDialogClose','onSelectError'],
      	'onSelectError':function(file){
      		if (file) {
      			if (file.size > 1048576) {
      				alertMessage('图片不能超过1MB');
      				return false;
      			}
				var fileExt = file.type.toLowerCase();
				if( fileExt != ".jpg" || fileExt != ".jpeg" || fileExt != ".gif" || fileExt != ".png")
				{
					alertMessage('不被接受的文件格式');
					return false;
				}
      		}
      	},
      	'onSelect':function(file){
        	$(".setting-button").removeAttr("disabled");
        	$(".setting-button").click(function(){
          		$('#avatar-file').uploadify('upload');
        	});
        	$('#avatar-file').uploadify('disable',true);
      	},
      	'onCancel':function(file){
        	$(".setting-button").attr("disabled","disabled");
        	$(".setting-button").unbind();
        	$('#avatar-file').uploadify('disable',false);
      	},
      	'onUploadError':function(file, errorCode, errorMsg, errorString){
        	$(".setting-button").attr("disabled","disabled");
        	$(".setting-button").unbind();
        	$('#avatar-file').uploadify('disable',false);
      	},
		'onUploadStart':function(file) {
			$(".setting-button").attr("disabled","disabled");
		},
      	'onUploadSuccess':function(file, data, response){
        	$('#avatar-file').uploadify('disable',false);
        	$(".setting-button").unbind();
        	data = eval("("+data+")");
        	if (data.result=="success") {
        		alertMessage("头像修改成功");
        		var avatarURL = $(".current-avatar").attr("src") + "?"+Math.random();
        		$(".current-avatar").attr("src",avatarURL);
        		$("#profile-avatar").attr("src",avatarURL);
        	}
        	else{
        		alertMessage("头像保存失败");
        	}
      	}
    });
	
	$(".uploadify-queue").css({"top":"38px","left":"0"});

	$(".setting-button").attr("disabled","disabled");
}

function checkEmail(email)
{
	var patten = new RegExp(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/);

	return patten.test(email);
}

function checkEmailInput()
{
	if( checkEmail($("#user_email").val()) && $("#user_email").val() != $("#email_fieldset label").attr("data") && $("#current_password").val().length >= 6 )
	{
		$(".setting-button").removeAttr("disabled");
	}
	else
	{
		$(".setting-button").attr("disabled","disabled");
	}
}

function settingEmail()
{
	$("#emailSetting input").attr("readonly",true);

	$(".setting-button").attr("disabled","disabled");

	$(".setting-button").html("正在保存");

	$.post("post.php",{"do":"settingEmail","password":$('#current_password').val(),"email":$('#user_email').val()},function(data)
	{
		if (data.result == "success")
		{
			alertMessage("邮箱设置成功");

			$("#email_fieldset label").attr("data",$('#user_email').val());
			
			$("#current_password").val("");
		}
		else
		{
			alertMessage(data.message);
		}

		$("#emailSetting input").attr("readonly",false);

		$(".setting-button").html("保存设置");

	},"json");

	return false;
}

function checkPasswordInput()
{
	var buttonStatus = false;

	if( $("#user_password").val().length >= 6 && $("#user_password").val() == $("#user_password_confirmation").val() )
	{
		buttonStatus = true;
	}

	if( $("#control-current-password").css('display') != "none" )
	{
		if( $("#current_password").val().length < 6 || $("#current_password").val() == $("#user_password").val() )
		{
			buttonStatus = false;
		}
	}

	if( buttonStatus )
	{
		$(".setting-button").removeAttr("disabled");
	}
	else
	{
		$(".setting-button").attr("disabled","disabled");
	}
}

function settingPassword()
{
	$("#passwdSetting input").attr("readonly",true);

	$(".setting-button").attr("disabled","disabled");

	$(".setting-button").html("正在保存");

	$.post("post.php",{"do":"settingPassword","currentPasswd":$('#current_password').val(),"userPasswd":$('#user_password').val()},function(data)
	{
		if (data.result == "success")
		{
			alertMessage("密码设置成功");

			$("#current_password").val("");
			
			$("#user_password").val("");

			$("#user_password_confirmation").val("");

			if( $("#control-current-password").css('display') == "none" )
			{
				$("#control-current-password").slideDown();

				$("#control-user-password label").html("新密码");
			}
		}
		else
		{
			alertMessage(data.message);
		}

		$("#passwdSetting input").attr("readonly",false);

		$(".setting-button").html("保存设置");

	},"json");

	return false;
}

function uploadify(type)
{
	var txtobj = $("#"+type+"-form textarea[name=message]");
	
	var btnobj = $("#"+type+"-form .submit-button");

	$('#picture').uploadify({
		'swf':'static/uploadify.swf',
		'uploader':'post.php',
		'method':'post',
		'buttonClass':'web-icon',
		'auto':false,
		'fileObjName':'picture',
		'buttonText':'',
		'width':40,
		'height':28,
		'fileTypeDesc':'图片（*.jpg;*.jpeg;*.gif;*.png）',
		'fileTypeExts':'*.jpg; *.jpeg; *.gif; *.png',
		'fileSizeLimit':'2048KB',
		'multi':false,
		'queueSizeLimit':1,
		'removeTimeout':0,
		'overrideEvents':['onDialogClose','onSelectError'],
		'onSelectError':function(file){
			if (file) {
				if (file.size > 2097152) {
					alertMessage('图片不能超过2MB');
					return false;
				}
				var fileExt = file.type.toLowerCase();
				if( fileExt != ".jpg" || fileExt != ".jpeg" || fileExt != ".gif" || fileExt != ".png")
				{
					alertMessage('不被接受的文件格式');
					return false;
				}
			}
		},
		'onSelect':function(file){
			btnobj.attr("data","media");
			if($.trim(txtobj.val()).length <= 200){
				btnobj.removeAttr("disabled");
			}
			btnobj.unbind();
			btnobj.click(function(){
				$('#picture').uploadify('upload');
				return false
			});
			$('#picture').uploadify('disable',true);
		},
		'onCancel':function(file){
			btnobj.attr("data","text");
			if($.trim(txtobj.val()).length == 0){
				btnobj.attr("disabled","disabled");
			}
			btnobj.unbind();
			if(type == "topic"){
				btnobj.click(postNewTopic);
			}
			else if(type == "reply"){
				btnobj.click(function(){postReplyTopic(false);return false;});
			}
			$('#picture').uploadify('disable',false);
		},
		'onUploadError':function(file, errorCode, errorMsg, errorString){
			btnobj.attr("data","text");
			if($.trim(txtobj.val()).length == 0){
				btnobj.attr("disabled","disabled");
			}
			btnobj.unbind();
			if(type == "topic"){
				btnobj.click(postNewTopic);
			}
			else if(type == "reply"){
				btnobj.click(function(){postReplyTopic(false);return false;});
			}
			txtobj.removeAttr("disabled");
			$('#picture').uploadify('disable',false);
		},
		'onUploadStart':function(file) {
			var message = $.trim(txtobj.val());
			if(message == "")
				message = " ";
			if(type == "topic"){
				$('#picture').uploadify('settings','formData',{'do':'addTopic','cid':$('input[name=cid]').val(),'msg':message,'COOKIE':'{"phpsay_uname":"'+parseCookie("phpsay_uname")+'","phpsay_secure":"'+parseCookie("phpsay_secure")+'"}'});
			}
			else if(type == "reply"){
				$('#picture').uploadify('settings','formData',{'do':'replyTopic','tid':$('input[name=tid]').val(),'msg':message,'COOKIE':'{"phpsay_uname":"'+parseCookie("phpsay_uname")+'","phpsay_secure":"'+parseCookie("phpsay_secure")+'"}'});
			}
			
			btnobj.attr("disabled","disabled");
			txtobj.attr("disabled","disabled");
		},
		'onUploadSuccess':function(file, data, response){
			$('#picture').uploadify('disable',false);
			if(type == "topic")
				postTopicResult(data);
			else if(type == "reply")
				replyTopicResult(data,false);
		}
	});

	txtobj.val('');

	btnobj.attr("disabled","disabled");

	btnobj.attr("data","text");

	if(type == "topic"){
		btnobj.click(postNewTopic);
	}
	else if(type == "reply"){
		btnobj.click(function(){postReplyTopic(false);return false;});
	}

	txtobj.keyup(function(){textCounter(type+"-form");});
	
	txtobj.keydown(function(event){
		var e = event || window.event;
		if(e.keyCode==13)
			return false;
	});
}

function textCounter(fname)
{
	var textArray = $('#'+fname+' textarea[name=message]').val().replace("\r","").split("\n");

	var arrayLen = textArray.length;

	if( arrayLen > 1)
	{
		var textString = "";

		for(var i=0;i<arrayLen;i++)
		{
			if( $.trim(textArray[i]) != "" )
			{
				textString += textArray[i].replace("　　","");

				if(i < (arrayLen-1))
				{
					textString += " / ";
				}
			}
		}

		$('#'+fname+' textarea[name=message]').val(textString);
	}

	var len = $.trim($('#'+fname+' textarea[name=message]').val()).length;
	
	if( len < 1 )
	{
		if ($("#"+fname+" .submit-button").attr("disabled") != "disabled" && $("#"+fname+" .submit-button").attr("data") == "text")
		{
			$("#"+fname+" .submit-button").attr("disabled","disabled");
		}
	}
	else if( len > 200 )
	{
		if ($("#"+fname+" .submit-button").attr("disabled") != "disabled")
		{
			$("#"+fname+" .submit-button").attr("disabled","disabled");
		}		
	}
	else
	{
		if ($("#"+fname+" .submit-button").attr("disabled") == "disabled")
		{
			$("#"+fname+" .submit-button").removeAttr("disabled");
		}
	}

	len = 200 - len;

	$("#"+fname+" .text-counter").html(len);

	if( len < 11 )
	{
		$("#"+fname+" .text-counter").css("color","#D40D12");

		return false;
	}

	if( len < 21 )
	{
		$("#"+fname+" .text-counter").css("color","#5C0002");

		return false;
	}

	$("#"+fname+" .text-counter").css("color","#999999");
}

function postNewTopic()
{
	$("#topic-form .submit-button").attr("disabled","disabled");

	$('#topic-form textarea[name=message]').attr("disabled","disabled");

	$.post("post.php",{"do":"addTopic","cid":$('#topic-form input[name=cid]').val(),"msg":$.trim($('#topic-form textarea[name=message]').val())},function(result)
	{
		postTopicResult(result);
	});

	return false;
}

function postTopicResult(str)
{
	data = eval("("+str+")");

	if (data.result == "login")
	{
		location.href = "./";

		return false;
	}

	$('#topic-form textarea[name=message]').removeAttr("disabled");

	$("#topic-form .submit-button").attr("data","text");

	$("#topic-form .submit-button").unbind();

	$("#topic-form .submit-button").click(postNewTopic);

	if (data.result == "success")
	{
		$('#topic-form textarea[name=message]').val('');

		$("#topic-form .text-counter").html("200");

		$(".stream-items").prepend(data.message);

		$('.stream-items li:eq(0) .zoom').flyout();

		$('.stream-items li:eq(0) .stream-content').click(quickReply);

		alertMessage("发布成功");
	}
	else
	{
		if( $.trim($('#topic-form textarea[name=message]').val()).length >= 1 )
		{
			$("#topic-form .submit-button").removeAttr("disabled");
		}

		if (data.result == "error")
		{
			alertMessage(data.message);
		}
		else
		{
			alertMessage("服务器异常");
		}
	}
}

function postReplyTopic(isquick)
{
	$("#reply-form .submit-button").attr("disabled","disabled");

	$('#reply-form textarea[name=message]').attr("disabled","disabled");

	$.post("post.php",{"do":"replyTopic","tid":$('#reply-form input[name=tid]').val(),"msg":$.trim($('#reply-form textarea[name=message]').val())},function(result)
	{
		replyTopicResult(result,isquick);
	});
}

function replyTopicResult(str,isquick)
{
	data = eval("("+str+")");

	if (data.result == "login")
	{
		location.href = "./";

		return false;
	}

	$('#reply-form textarea[name=message]').removeAttr("disabled");

	$("#reply-form .submit-button").attr("data","text");

	$("#reply-form .submit-button").unbind();

	$("#reply-form .submit-button").click(function(){postReplyTopic(isquick);return false;});

	if (data.result == "success")
	{
		if (!isquick)
		{
			$('#reply-form textarea[name=message]').val('');

			$("#reply-form .text-counter").html("200");

			$(".reply-items").append(data.message);

			$('.reply-items li:last .zoom').flyout();

			$("html,body").animate({scrollTop: $(".topic-end-inner").offset().top}, 500);
		}
		else
		{
			$(".stream-items .stream-item .quickreply-form").slideUp(function(){
				$(this).prev().unbind();
				$(this).prev().click(quickReply);
				$(this).remove();
			});

			var commentObj = $("#comment-"+$('#reply-form input[name=tid]').val());

			if( commentObj.length > 0 )
			{
				var commentNumObj = commentObj.children("strong");

				if (commentNumObj.length > 0)
				{
					commentNumObj.html((parseInt(commentNumObj.html())+1));
				}
				else
				{
					commentObj.html("查看所有回复");
				}
			}
		}

		alertMessage("回复成功");
	}
	else
	{
		if( $.trim($('#reply-form textarea[name=message]').val()).length >= 1 )
		{
			$("#reply-form .submit-button").removeAttr("disabled");
		}

		if (data.result == "error")
		{
			alertMessage(data.message);
		}
		else
		{
			alertMessage("服务器异常");
		}
	}
}

function checkIsGoOn(ev)
{
	var target = ev.target || ev.srcElement;

	var tag = target.tagName;

	if ( tag )
	{
		if( tag.toLowerCase() === 'p' || tag.toLowerCase() === 'div' )
		{
			return true;
		}
	}

	return false;
}

function quickReply(ev)
{
	if ( checkIsGoOn(ev) )
	{
		$(".stream-items .stream-item .quickreply-form").slideUp(function(){
			$(this).prev().removeAttr("style");
			$(this).prev().unbind();
			$(this).prev().click(quickReply);
			$(this).remove();
		});

		$(this).attr("style","background-color:#FFF");

		var replyPost = $(this).attr("data");

		var replyName = "";

		var dataArray = replyPost.split("-");

		if (dataArray.length > 1)
		{
			replyPost = dataArray[0];

			replyName = "@"+dataArray[1]+" ";
		}

		var quickReplyForm = '<div class="quickreply-form">';

		quickReplyForm += '<form id="reply-form">';

		quickReplyForm += '<input type="hidden" name="tid" value="'+replyPost+'">';

		quickReplyForm += '<textarea class="input-body" name="message" rows="2">'+replyName+'</textarea>';

		quickReplyForm += '<div class="post-button-right">';

		quickReplyForm += '<span class="text-counter">200</span>';

		quickReplyForm += '<button class="submit-button" type="submit" data="text"><span class="submit-button-text">回复</span></button>';

		quickReplyForm += '</div>';

		quickReplyForm += '<div class="clear"></div>';

		quickReplyForm += '</form>';

		quickReplyForm += '</div>';

		$(this).unbind();

		$(this).after(quickReplyForm);

		textCounter("reply-form");

		$(this).next().slideDown(function(){
			$("#reply-form textarea[name=message]").keyup(function(){
				textCounter("reply-form");
			});
			$("#reply-form textarea[name=message]").keydown(function(event){
				var e = event || window.event;
				if(e.keyCode==13)
					return false;
			});
			$("#reply-form .submit-button").click(function(){postReplyTopic(true);return false;});
			$(this).prev().click(function(event){
				if ( checkIsGoOn(event) ){
					$(this).removeAttr("style");
					$(this).next().slideUp(function(){
						$(this).prev().unbind();
						$(this).prev().click(quickReply);
						$(this).remove();
					});
				}
			});
		});
	}
}

function alertMessage(msg)
{
	$(".alert-messages .message .message-text").html(msg);

	$(".alert-messages").fadeIn();

	setTimeout('$(".alert-messages").fadeOut()',2000);
}

function cancelDeleteTopic(tid,cid)
{
	var o = $("#deleteTopic-"+tid);

	o.html(o.html().replace("确认","删除"));

	o.unbind();

	o.click(function(){deleteTopic(tid,cid);});
}

function deleteTopic(tid,cid)
{
	var o = $("#deleteTopic-"+tid);

	o.removeAttr("onclick");

	o.html(o.html().replace("删除","确认"));

	o.unbind();

	o.click(function(){
		$.post("post.php",{"do":"deleteTopic","tid":tid},function(data){
			if (data.result == "success") {
				if(cid > 0) {
					location.href = "./?cid="+cid;
				}
				else {
					$("#item-"+tid).slideUp(300,function(){$(this).remove();alertMessage("删除成功");});
				}
			}
			else {
				alertMessage("删除失败");
				o.show();
			}
		},"json");
		
		o.hide();
	});

	setTimeout('cancelDeleteTopic('+tid+','+cid+')',3000);
}

function replyAt(uname)
{
	$('#reply-form textarea[name=message]').val($('#reply-form textarea[name=message]').val().replace("@"+uname+" ","")+"@"+uname+" ");

	$('#reply-form textarea[name=message]').focus();

	$('#reply-form textarea[name=message]').focusEnd();

	$("html,body").animate({scrollTop: $(".reply-form").offset().top-42}, 300);

	textCounter("reply-form");
}

function cancelDeleteReply(pid)
{
	var o = $("#deleteReply-"+pid);

	o.html(o.html().replace("确认","删除"));

	o.unbind();

	o.click(function(){deleteReply(pid);});
}

function deleteReply(pid)
{
	var o = $("#deleteReply-"+pid);

	o.removeAttr("onclick");

	o.html(o.html().replace("删除","确认"));

	o.unbind();

	o.click(function(){
		$.post("post.php",{"do":"deleteReply","pid":pid},function(data){
			if (data.result == "success") {
				$("#item-"+pid).slideUp(300,function(){$(this).remove();alertMessage("删除成功");});
			}
			else {
				alertMessage("删除失败");
				o.show();
			}
		},"json");

		o.hide();
	});

	setTimeout('cancelDeleteReply('+pid+')',3000);
}

function cancelDeleteNotification(nid)
{
	var o = $("#deleteNotification-"+nid);

	o.html(o.html().replace("确认","删除"));

	o.unbind();

	o.click(function(){deleteNotification(nid);});
}

function deleteNotification(nid)
{
	var o = $("#deleteNotification-"+nid);

	o.removeAttr("onclick");

	o.html(o.html().replace("删除","确认"));

	o.unbind();

	o.click(function(){
		$.post("post.php",{"do":"deleteNotification","nid":nid},function(data){
			if (data.result == "success") {
				$("#item-"+nid).slideUp(300,function(){$(this).remove();alertMessage("删除成功");});
			}
			else {
				alertMessage("删除失败");
				o.show();
			}
		},"json");

		o.hide();
	});

	setTimeout('cancelDeleteNotification('+nid+')',3000);
}

function updateFavNum(num)
{
	var o = $(".favorite-count").children("strong");

	if (o.length > 0)
	{
		o.html(parseInt(o.html()) + num);
	}
}

function favTopic(tid)
{
	var o = $("#favTopic-"+tid);

	o.hide();

	$.post("post.php",{"do":"favTopic","tid":tid},function(data)
	{
		if (data.result == "success")
		{
			o.addClass("favorite");

			o.html(o.html().replace("收藏","已收藏"));

			o.attr("onclick","unFavTopic("+tid+")");

			if (data.message >= 1)
			{
				updateFavNum(1);
			}
		}
		else{
			alertMessage("收藏失败");
		}
		o.show();
	},"json");
}

function unFavTopic(tid)
{
	var o = $("#favTopic-"+tid);

	o.hide();

	$.post("post.php",{"do":"unFavTopic","tid":tid},function(data)
	{
		if (data.result == "success")
		{
			o.removeClass("favorite");

			o.html(o.html().replace("已收藏","收藏"));

			o.attr("onclick","favTopic("+tid+")");

			updateFavNum(-1);
		}
		else
		{
			alertMessage("取消收藏失败");
		}
		o.show();
	},"json");
}

function favReply(pid)
{
	var o = $("#favReply-"+pid);

	o.hide();

	$.post("post.php",{"do":"favReply","pid":pid},function(data)
	{
		if (data.result == "success")
		{
			o.addClass("favorite");

			o.html(o.html().replace("收藏","已收藏"));

			o.attr("onclick","unFavReply("+pid+")");

			if (data.message >= 1)
			{
				updateFavNum(1);
			}
		}
		else{
			alertMessage("收藏失败");
		}
		o.show();
	},"json");
}

function unFavReply(pid)
{
	var o = $("#favReply-"+pid);

	o.hide();

	$.post("post.php",{"do":"unFavReply","pid":pid},function(data)
	{
		if (data.result == "success")
		{
			o.removeClass("favorite");

			o.html(o.html().replace("已收藏","收藏"));

			o.attr("onclick","favReply("+pid+")");

			updateFavNum(-1);
		}
		else
		{
			alertMessage("取消收藏失败");
		}
		o.show();
	},"json");
}

function sinkTopic(tid)
{
	var o = $("#sinkTopic-"+tid);

	o.hide();

	$.post("post.php",{"do":"topicStatus","tid":tid},function(data)
	{
		if (data.result == "success")
		{
			if(data.message == 1)
			{
				o.html(o.html().replace("恢复","下沉"));
			}
			else
			{
				o.html(o.html().replace("下沉","恢复"));
			}
		}
		else
		{
			alertMessage("操作异常");
		}

		o.show();

	},"json");	
}

function blockUser(uid)
{
	$("#block").hide();

	$.post("post.php",{"do":"userGroup","uid":uid},function(data)
	{
		if (data.result == "success")
		{
			if(data.message == 1)
			{
				$("#block").removeClass("blocked").addClass("block");
			}
			else if(data.message == 0)
			{
				$("#block").removeClass("block").addClass("blocked");
			}
		}
		else
		{
			alertMessage("操作异常");
		}

		$("#block").show();

	},"json");	
}