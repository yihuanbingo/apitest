// JavaScript Document

/* 验证是否是小数 */
function isDecimal(s) 
{
 var regu =   "^[0-9]*[.][0-9][0-9]*$"; 
 var re = new RegExp(regu);
 if (s.search(re) != -1)
  {
    return true;
  }
 else
  {
    return false;
  }
}

/* 验证是否为正整数 */
function isNum(num)
{
	var type =   "^[0-9]*[1-9][0-9]*$"; 
	var re   =   new   RegExp(type); 
	if(num.match(re)==null) 
	{
	    return false;
	}
	else
	{
		return true;
    }
}

/* 
 * 全选(全不选)函数 
 * obj，form表单对象
*/
function selectAll(obj, chk)
{
  if (chk == null)
  {
    chk = 'checkboxes';
  }
  var elems = obj.form.getElementsByTagName("INPUT");
  for (var i=0; i < elems.length; i++)
  {
    if (elems[i].name == chk || elems[i].name == chk + "[]")
    {
      elems[i].checked = obj.checked;
    }
  }
}

/* 判断是否至少选中一个 */
function checkSubmit(na) 
{ 
 
    var str = ''; 
    for(var i=0;i < document.getElementsByName(na).length;i++) 
    { 
          if(document.getElementsByName(na)[i].checked)
		  { 
            if(str=='') str += document.getElementsByName(na)[i].value; 
            else str += ',' + document.getElementsByName(na)[i].value; 
          } 
    } 
    if(str=='') 
    { 
        alert('请至少选择一个'); 
        return false; 
    } 
	else
	{
	   return true;	
    }
} 

/* 对json字符串时行解码 */
function json_decode(data)
{
  return eval('('+data+')');	
}

/* 模拟alert函数弹出的信息层*/
function alertMsg(msg)
{
   document.getElementById('popDiv').style.display='block';
   document.getElementById('alertMsg').innerHTML=msg;
   document.getElementById('bg').style.display='block';
   setTimeout(closeMsg,1500);
}
function closeMsg()
{
   document.getElementById('popDiv').style.display='none';
   document.getElementById('bg').style.display='none';
}

/* 翻页 */
function gotoPage(page)
{
     document.forms["search"].elements['page'].value = page;
     document.forms["search"].submit();
     return true;;
}

/* 跳转指定页 */
function gotoMpage(id)
{
     var page = parseInt($("#"+id).val());
	 if(page>0)
	 {
	    gotoPage(page);
	 }
}