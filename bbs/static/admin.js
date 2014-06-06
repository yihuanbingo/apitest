function checkSponsorInput()
{
	if( $.trim($("#linkText").val()).length > 1 && $.trim($("#linkURL").val()).length > 10 && $("#linkURL").val().replace("s","").substring(0,7) == "http://" )
	{
		$(".setting-button").removeAttr("disabled");
	}
	else
	{
		$(".setting-button").attr("disabled","disabled");
	}
}

function addSponsor()
{
	$.post(location.href,{"linkText":$("#linkText").val(),"linkURL":$("#linkURL").val()},function(result)
	{
		if(parseInt(result) > 1)
		{
			location.href=location.href;
		}
		else
		{
			alertMessage("系统异常");
		}
	});

	return false;
}

function deleteSponsor()
{
	if( !confirm("确定要删除吗？") )
	{
		return false;
	}

	var obj = $(this);

	obj.hide();

	$.post(location.href,{"deleteId":obj.attr("data")},function(result)
	{
		if(parseInt(result) > 1)
		{
			obj.parent().fadeOut(function(){$(this).remove()});
		}
		else
		{
			obj.show();

			alertMessage("删除失败");
		}
	});
}

function checkClubInput()
{
	if( $.trim($(this).val()).length > 1 && $.trim($(this).val()) != $(this).parent().attr("data") )
	{
		$(this).parents("form").find("button.setting-button").removeAttr("disabled");
	}
	else
	{
		$(this).parents("form").find("button.setting-button").attr("disabled","disabled");
	}
}

function editClub()
{
	var obj = $(this);

	var clubId = obj.find("input[name='clubId']").val();

	var clubName = obj.find("input[name='clubName']").val();

	obj.find("button.setting-button").attr("disabled","disabled");

	$.post(location.href,{"clubId":clubId,"clubName":clubName},function(data)
	{
		if (data.result == "success")
		{
			if( clubId > 0 )
			{
				obj.find(".controls").attr("data",clubName);

				alertMessage("保存成功");
			}
			else
			{
				location.href = location.href;
			}
		}
		else if (data.result == "error")
		{
			obj.find("button.setting-button").removeAttr("disabled");

			alertMessage(data.message);
		}
		else
		{
			obj.find("button.setting-button").removeAttr("disabled");
			
			alertMessage("系统异常");
		}
	},"json");

	return false;
}

function trashClub()
{
	var obj = $(this);

	obj.hide();

	$.post(location.href,{"trashId":$(this).attr("data")},function(result)
	{
		if(parseInt(result) == 0)
		{
			location.href = location.pathname + "?type=trash";
		}
		else if(parseInt(result) == 1)
		{
			location.href = location.pathname;
		}
		else
		{
			obj.show();

			alertMessage("系统异常");
		}
	});
}

function saveClubOrder()
{
	var clubOrder = $("#clubList li").map(function() { return $(this).find("input[name='clubId']").val(); }).get().join(",");

	$.post(location.href,{"clubOrder":clubOrder},function(data)
	{
		if(parseInt(data) < 2)
		{
			alertMessage("排序系统异常");
		}
	});
};

function updateTemplateCache()
{
	$(".setting-button").hide();

	$(".setting-end-inner").append('<img src="./static/loading.gif" style="width:29px;height:29px;border:0">');

	$.post(location.href,{"deleteCache":"1"},function(result)
	{
		alertMessage("共清除"+result+"个模版缓存文件");

		$(".setting-end-inner img").remove();

		$(".setting-button").show();
	});

	return false;
}