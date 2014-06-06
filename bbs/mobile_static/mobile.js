function scroll2Bottom()
{
	$('html, body').animate({scrollTop: $(document).height()}, 300);
}

function locationHash()
{
	var h = window.location.hash;

	if ( h.substring(0,6) == "#reply" )
	{
		$("html,body").animate({scrollTop: $("#reply-"+h.substr(6)).offset().top}, 1000);
	}
}

function loginPHPSay()
{
	var account = $.trim($('input[name=account]').val());

	if( account.length < 2 || account.length > 36 )
	{
		$('input[name=account]').focus();

		return false;
	}

	var password = $('input[name=password]').val();

	if( password.length < 6 || password.length > 26 )
	{
		$('input[name=password]').focus();

		return false;
	}

	$('input[name=account]').attr("readonly",true);

	$('input[name=password]').attr("readonly",true);

	$('.submit-button').attr("value","正在登录...");

	$('.submit-button').attr("disabled","disabled");

	$.post($("#loginForm").attr("action"),{"account":account,"password":password},function(data)
	{
		if (data.result == "success")
		{
			$('.submit-button').attr("value","登录成功，正在跳转...");

			setTimeout("location.href='./';",1000);
		}
		else if (data.result == "error")
		{
			$('input[name=account]').attr("readonly",false);

			$('input[name=password]').attr("readonly",false);

			$('.submit-button').removeAttr('disabled');

			$('.submit-button').attr('value','重新登录');

			if( data.position == 1 )
			{
				$('input[name=account]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","邮箱或昵称")});
			}
			else if( data.position == 2 )
			{
				$('input[name=password]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","密码")});
			}
		}

	},"json");

	return false;
}

function joinPHPSay()
{
	var nickname = $.trim($('input[name=nickname]').val());

	if( nickname.length < 1 || nickname.length > 13 )
	{
		$('input[name=nickname]').focus();

		return false;
	}

	$('input[name=nickname]').attr("readonly",true);

	$('.submit-button').attr("value","正在提交...");

	$('.submit-button').attr("disabled","disabled");

	$.post($("#joinForm").attr("action"),{"nickname":nickname},function(data)
	{
		if (data.status == "success")
		{
			$('.submit-button').attr("value","保存成功，正在跳转...");

			setTimeout("location.href='./';",1000);
		}
		else if (data.status == "error")
		{
			$('.submit-button').removeAttr('disabled');

			$('.submit-button').attr('value','重新提交');

			$('input[name=nickname]').attr("readonly",false).attr("placeholder",data.response).val("").keydown(function(){$(this).attr("placeholder","昵称")});
		}

	},"json");

	return false;
}

function setname()
{
    var nickname = $.trim($('input[name=nickname]').val());

	if( nickname.length < 1 || nickname.length > 13 )
	{
		$('input[name=nickname]').focus();

		return false;
	}
	$('input[name=nickname]').attr("readonly",true);

	$('.submit-button').attr("value","正在设置昵称...");

	$('.submit-button').attr("disabled","disabled");	
	
	$.post("passport.php?do=setname",{"nickname":nickname},function(data)
	{
	
	/*
	$.post($("#setname").attr("action"),{"nickname":nickname},function(data)
	{ 
	*/
		if (data.status == "success")
		{
			$('.submit-button').attr("value","设置昵称成功，正在跳转到社区...");

			setTimeout("location.href='./';",1000);
		}
		else if (data.status == "error")
		{
			$('.submit-button').removeAttr('disabled');

			$('.submit-button').attr('value','重新设置昵称');

			$('input[name=nickname]').attr("readonly",false).attr("placeholder",data.response).val("").keydown(function(){$(this).attr("placeholder","昵称")});
		}

	},"json");

	return false;
}

function changeVerify()
{
	$(".verify-image").attr("src","verify.php?referer=send_password&rd="+Math.random());
}

function sendPassword()
{
	var email = $('input[name=email]').val();

	if( !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/.test(email) )
	{
		$('input[name=email]').focus();

		return false;
	}

	var verify_code = $('input[name=verify_code]').val();

	if( verify_code.length != 5 )
	{
		$('input[name=verify_code]').focus();

		return false;
	}	

	$('input[name=email]').attr("readonly",true);

	$('input[name=verify_code]').attr("readonly",true);

	$(".verify-image").unbind();

	$('.submit-button').attr("value","正在提交...");

	$('.submit-button').attr("disabled","disabled");

	$.post($(this).attr("action"),{"email":email,"verify_code":verify_code},function(data)
	{
		if (data.result == "success")
		{
			$('.submit-button').attr("value","发送成功");

			setTimeout("alert('发送成功！请查看您的邮箱');location.href='./';",500);
		}
		else if (data.result == "error")
		{
			$('input[name=email]').attr("readonly",false);

			$('input[name=verify_code]').attr("readonly",false);

			changeVerify();
			
			$(".verify-image").click(changeVerify);

			$('.submit-button').removeAttr('disabled');

			$('.submit-button').attr('value','重新提交');

			if( data.position == 1 )
			{
				$('input[name=email]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","邮箱")});

				$('input[name=verify_code]').val("");
			}
			else if( data.position == 2 )
			{
				$('input[name=verify_code]').attr("placeholder","验证码错误").val("").keydown(function(){$(this).attr("placeholder","验证码")});
			}
		}

	},"json");

	return false;
}

function resetPassword()
{
	var password = $('input[name=password]').val();

	if( password.length < 6 )
	{
		$('input[name=password]').focus();

		return false;
	}

	if( $('input[name=password_confirm]').val() != password )
	{
		$('input[name=password_confirm]').focus();

		return false;
	}	

	$('input[name=password]').attr("readonly",true);

	$('input[name=password_confirm]').attr("readonly",true);

	$('.submit-button').attr("value","正在保存...");

	$('.submit-button').attr("disabled","disabled");

	$.post($(this).attr("action"),{"password":password},function(data)
	{
		if (data.result == "success")
		{
			$('.submit-button').attr("value","密码重设成功");

			setTimeout("location.href='./';",1000);
		}
		else if (data.result == "error")
		{
			$('input[name=password]').attr("readonly",false).val("");

			$('input[name=password_confirm]').attr("readonly",false).val("");

			$('.submit-button').removeAttr('disabled');

			$('.submit-button').attr('value','重新提交');

			alert(data.message);
		}

	},"json");

	return false;
}

function postTopic()
{   
    
	var msg = $.trim($('textarea[name=message]').val());
	if( msg.length < 1 || msg.length > 200 )
	{
		$('textarea[name=message]').focus();
		return false;
	}
	$("#post-header").html('<div style="text-align:center"><img src="./mobile_static/loading.gif" width="30" height="30"></div>');
	$("#post-form").slideUp();
 
    /* jquery.form 提交表单 */
    $("#add-topic-form").ajaxSubmit({  
	  dataType:  'json',
	  success:function(data)
	  { 
		if (data.result == "login")
		{
			location.href = "./";
		}
		else if (data.result == "success")
		{
			$("#post-header").html('<div style="text-align:center;color:rgb(95,191,96);height:30px;line-height:30px;">发布成功</div>');

			setTimeout('location.href = "./?cid='+$('input[name=cid]').val()+'";',1000);
		}
		else if (data.result == "error")
		{
			$("#post-header").html('<div style="text-align:center;color:rgb(237,99,86);height:30px;line-height:30px;">'+data.message+'</div>');

			setTimeout('$("#post-header").html("发篇新的");$("#post-form").slideDown(function(){scroll2Bottom()});',2000);
		} 
	  }
	});
    /*
	$.post("post.php",{"do":"addTopic","cid":$('input[name=cid]').val(),"msg":msg},function(data)
	{
		if (data.result == "login")
		{
			location.href = "./";
		}
		else if (data.result == "success")
		{
			$("#post-header").html('<div style="text-align:center;color:rgb(95,191,96);height:30px;line-height:30px;">发布成功</div>');

			setTimeout('location.href = "./?cid='+$('input[name=cid]').val()+'";',1000);
		}
		else if (data.result == "error")
		{
			$("#post-header").html('<div style="text-align:center;color:rgb(237,99,86);height:30px;line-height:30px;">'+data.message+'</div>');

			setTimeout('$("#post-header").html("发篇新的");$("#post-form").slideDown(function(){scroll2Bottom()});',2000);
		}
	},"json");
	*/
	return false;
}

function replyTopic()
{
	var msg = $.trim($('textarea[name=message]').val());
	
	if( msg.length < 1 || msg.length > 200 )
	{
		$('textarea[name=message]').focus();

		return false;
	}

	if( $("#replyStatus").length == 0 )
	{
		$("#reply-topic").prepend('<div style="text-align:center;font-size:14px;height:30px;line-height:30px;" id="replyStatus"></div>');
	}

	$("#replyStatus").html('<img src="./mobile_static/loading.gif" width="30" height="30">');	

	$("#reply-topic-form").slideUp(200);
	
	/* jquery.form 提交表单 */
    $("#reply-topic-form").ajaxSubmit({  
	  dataType:  'json',
	  success:function(data)
	  { 
	     if (data.result == "login")
		{
			location.href = "./";
		}
		else if (data.result == "success")
		{
			$("#replyStatus").html('<span style="color:rgb(95,191,96)">回复成功</span>');

			setTimeout('location.href = "./t.php?id='+$('input[name=tid]').val()+'";',1000);
		}
		else if (data.result == "error")
		{
			$("#replyStatus").html('<span style="color:rgb(237,99,86)">'+data.message+'</span>');

			setTimeout('$("#replyStatus").remove();$("#reply-topic-form").slideDown();',2000);
		}
	  }
	});
	
	/*
	$.post("post.php",{"do":"replyTopic","tid":$('input[name=tid]').val(),"msg":msg},function(data)
	{
		if (data.result == "login")
		{
			location.href = "./";
		}
		else if (data.result == "success")
		{
			$("#replyStatus").html('<span style="color:rgb(95,191,96)">回复成功</span>');

			setTimeout('location.href = "./t.php?id='+$('input[name=tid]').val()+'";',1000);
		}
		else if (data.result == "error")
		{
			$("#replyStatus").html('<span style="color:rgb(237,99,86)">'+data.message+'</span>');

			setTimeout('$("#replyStatus").remove();$("#reply-topic-form").slideDown();',2000);
		}
	},"json");
    */
	
	return false;
}

function replyAt(uname)
{
	$('textarea[name=message]').val($('textarea[name=message]').val().replace("@"+uname+" ","")+"@"+uname+" ");

	$('textarea[name=message]').focus();
}

function deleteNotify(nid)
{
	if(confirm("确定要删除该条提醒吗？"))
	{
		$("#notify-"+nid).slideUp();

		$.post("post.php",{"do":"deleteNotification","nid":nid},function(data)
		{
			if (data.result == "success")
			{
				$("#notify-"+nid).remove();
			}
			else
			{
				$("#notify-"+nid).slideDown();
			}
		},"json");
	}
}

function deleteFavorite(tid,pid)
{
	if(confirm("确定要删除该收藏吗？"))
	{
		$("#favorite-"+tid+"-"+pid).slideUp();

		var formData = {};

		if ( pid > 0 )
		{
			formData = {"do":"unFavReply","pid":pid};
		}
		else
		{
			formData = {"do":"unFavTopic","tid":tid};
		}

		$.post("post.php",formData,function(data)
		{
			if (data.result == "success")
			{
				$("#favorite-"+tid+"-"+pid).remove();
			}
			else
			{
				$("#favorite-"+tid+"-"+pid).slideDown();
			}
		},"json");
	}
}

function uploadAvatar()
{
	if( $("input[type='file']").val() == "" )
	{
		alert("请先选择一张图片");
		
		return false;
	}
	
	if( !/.(gif|jpg|jpeg|png)$/.test( $("input[type='file']").val().toLowerCase() ) )
	{
		alert("您只能上传 JPG/GIF/PNG");
		
		return false;
	}
	
	$(".current-avatar").append('<div style="position:absolute;z-index:1000;width:180px;height:50px;top:0;left:65px;background:url(./mobile_static/loading.gif) no-repeat;background-size:30px 30px;background-position:0 20px;"></div>');
	
	$(".submit-button").attr("value","正在上传");
	
	$(".submit-button").attr("disabled","disabled");
}

/* 刷新微信头像与昵称头像 */
function refreshAvatar()
{
	$("#refreshAvatarSubmit").val('正在刷新...');
	$.post("post.php",{"do":"refreshAvatar",},function(data)
	{
		if (data.result == "success")
		{
			window.location.reload();
		}
		else if(data.result=='error')
		{
		    alert(data.message);	
		}
	},"json");
	return false;
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

function checkSettingResult()
{
	var result = parseCookie("upload_avatar_result");

	if( result != "" )
	{
		document.cookie = "upload_avatar_result=";

		if( result == "SUCCESS" )
		{
			var avatarURL = $(".current-avatar img").attr("src") + "?" + Math.random();

			$(".user img").attr("src",avatarURL);
			
			$(".current-avatar img").attr("src",avatarURL);
		}
		else
		{
			alert(decodeURIComponent(result));
		}
	}
}

function settingPassword()
{
	var password_current = $('input[name=password_current]').val();

	if( $(".user-input:eq(0)").css('display') != "none" )
	{
		if( password_current.length < 6 )
		{
			$('input[name=password_current]').focus();

			return false;
		}
	}

	var password = $('input[name=password]').val();

	var placeholderText = $('input[name=password]').attr("placeholder");

	if( password.length < 6 || password.length > 26 )
	{
		$('input[name=password]').attr("placeholder","密码至少6位，最多26位").val("").focus().keydown(function(){$(this).attr("placeholder",placeholderText)});

		return false;
	}

	if( password == password_current )
	{
		$('input[name=password]').attr("placeholder","新密码不能与当前密码相同").val("").focus().keydown(function(){$(this).attr("placeholder",placeholderText)});

		$('input[name=password_confirm]').val("");

		return false;
	}

	if( password != $('input[name=password_confirm]').val() )
	{
		$('input[name=password_confirm]').attr("placeholder","两次输入的密码不一致").val("").focus().keydown(function(){$(this).attr("placeholder","确认密码")});

		return false;
	}

	$('.user-input input').attr("readonly",true);

	$('.submit-button').attr("value","正在保存...");

	$('.submit-button').attr("disabled","disabled");

	$.post($(this).attr("action"),{"do":"settingPassword","currentPasswd":password_current,"userPasswd":password},function(data)
	{
		if (data.result == "success")
		{
			$('.user-input input').val("");

			if( $(".user-input:eq(0)").css('display') == "none" )
			{
				$(".user-input:eq(0)").slideDown();

				$('input[name=password]').attr("placeholder","新密码");

				$(".submit-button").attr("value","密码设置成功！");
			}
			else
			{
				$(".submit-button").attr("value","密码修改成功！");
			}

			setTimeout('$(".submit-button").attr("value","保 存");',3500);
		}
		else if (data.result == "error")
		{
			$('.submit-button').attr('value','重新提交');

			if( data.position == 1 )
			{
				$('input[name=password_current]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","当前密码")});
			}
			else if( data.position == 2 )
			{
				$('input[name=password]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder",placeholderText)});

				$('input[name=password_confirm]').val("");
			}
		}

		$('.user-input input').attr("readonly",false);

		$('.submit-button').removeAttr('disabled');

	},"json");

	return false;
}

function settingEmail()
{
	var password = $('input[name=password]').val();

	if( password.length < 6 )
	{
		$('input[name=password]').focus();

		return false;
	}

	var email = $('input[name=email]').val();

	if( !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/.test(email) )
	{
		$('input[name=email]').focus();

		return false;
	}

	$('.user-input input').attr("readonly",true);

	$('.submit-button').attr("value","正在保存...");

	$('.submit-button').attr("disabled","disabled");

	$.post($(this).attr("action"),{"do":"settingEmail","password":password,"email":email},function(data)
	{
		if (data.result == "success")
		{
			$('input[name=password]').val("");

			$('.submit-button').attr('value','保存成功！');

			setTimeout("$('.submit-button').attr('value','保 存');",3500);
		}
		else if (data.result == "error")
		{
			$('.submit-button').attr('value','重新提交');

			if( data.position == 1 )
			{
				$('input[name=password]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","当前密码")});
			}
			else if( data.position == 2 )
			{
				$('input[name=email]').attr("placeholder",data.message).val("").keydown(function(){$(this).attr("placeholder","邮件地址")});
			}
		}

		$('.user-input input').attr("readonly",false);

		$('.submit-button').removeAttr('disabled');

	},"json");
	
	return false;
}

function favoriteMsg()
{
	var arr = $(this).attr("data").split("-");

	var formData = {};

	if( arr[0] == "topic" )
	{
		formData = {"do":"favTopic","tid":arr[1]};
	}
	else if( arr[0] == "reply" )
	{
		formData = {"do":"favReply","pid":arr[1]};
	}
	
	var o = $(this);

	o.hide();

	$.post("post.php",formData,function(data)
	{
		if (data.result == "success")
		{
			o.removeClass("favorite");

			o.addClass("favorited");

			o.unbind();

			o.click(unFavoriteMsg);
		}

		o.show();

	},"json");
}

function unFavoriteMsg()
{
	var arr = $(this).attr("data").split("-");

	var formData = {};

	if( arr[0] == "topic" )
	{
		formData = {"do":"unFavTopic","tid":arr[1]};
	}
	else if( arr[0] == "reply" )
	{
		formData = {"do":"unFavReply","pid":arr[1]};
	}
	
	var o = $(this);

	o.hide();

	$.post("post.php",formData,function(data)
	{
		if (data.result == "success")
		{
			o.removeClass("favorited");

			o.addClass("favorite");

			o.unbind();

			o.click(favoriteMsg);
		}

		o.show();

	},"json");
}

function deleteTopic(tid,cid)
{
	if(confirm("确定要删除该条消息及其回复吗？"))
	{
		$(".delete").hide();

		$.post("post.php",{"do":"deleteTopic","tid":tid},function(data)
		{
			if (data.result == "success")
			{
				location.href = "./?cid="+cid;
			}
			else
			{
				$(".delete").show();
			}
		},"json");
	}
}

function deleteReply(pid)
{
	if(confirm("确定要删除该条回复吗？"))
	{
		$("#reply-"+pid).slideUp();

		$.post("post.php",{"do":"deleteReply","pid":pid},function(data)
		{
			if (data.result == "success")
			{
				$("#reply-"+pid).remove();
			}
			else
			{
				$("#reply-"+pid).slideDown();
			}
		},"json");
	}
}

function imageZoom()
{
	var marginTop = $(window).height()/3+$(window).scrollTop();

	$('<div id="maskview"></div>').appendTo(document.body).css({
		position:'absolute',
		top:0,
		left:0,
		'z-index':900,
		width:$(document).width(),
		height:$(document).height(),
		'background':'#000 url(../static/loading2.gif) no-repeat',
		'background-position':'center '+marginTop+'px',
		'background-size':'16px 16px',
		'opacity':'0.8'
	}).click(function()
	{
		$("#maskview").remove();
		$("#imageView").remove();
	});

	$('<div id="imageView"></div>').appendTo(document.body).css({
		position:'absolute',
		top:0,
		left:0,
		width:$(document).width(),
		'text-align':'center',
		'z-index':901
	}).click(function()
	{
		$("#maskview").remove();
		$("#imageView").remove();
	});

	$("#imageView").append($('<img id="bigImage" src="'+$(this).attr("rel")+'" style="opacity:1.0" />').hide().load(function()
	{
		$(this).parent().css({'background-image':'none'});

		var mWidth = $(window).width()-13;

		$(this).css({'max-width':mWidth+"px"});

		var margin_Top = $(window).scrollTop();

		if( $(this).height() < $(window).height() )
		{
			margin_Top = ($(window).height() - $(this).height())/3 + margin_Top;
		}

		$(this).css({'margin-top':margin_Top}).fadeIn();
	}));
}