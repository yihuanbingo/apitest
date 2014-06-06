<?php
/*
 * 基础类函数
 * author yuanjiang @2.16.2013
*/
if(!defined('IN_BS'))
{
  die('hacking attempt');
}
 
class Common 
{

 /*
  * 将用户输入的字符串转义，防止sql注入
  * param $char 
 */
 function charFormat($char)
 {
   $char = trim($char);
   $char = strip_tags($char); // 去除字符串中的html标签,js启用
   $char = str_replace('&nbsp;','',$char);   //去除字符串中的&nbsp;字符
   return htmlspecialchars($char); //htmlspecialchars()函数只能转义5种符号，不能完全防止SQL注入
 }  

 /**
 * 自定义 header 函数，用于过滤可能出现的安全隐患
 * @param   string  string  内容
 * @return  void
 **/
 function base_header($string, $replace = true, $http_response_code = 0)
 {
    $string = str_replace(array("\r", "\n"), array('', ''), $string);
    if (preg_match('/^\s*location:/is', $string))
    {
        @header($string . "\n", $replace);
        exit();
    }
    if (empty($http_response_code) || PHP_VERSION < '4.3')
    {
        @header($string, $replace);
    }
    else
    {
        @header($string, $replace, $http_response_code);
    }
 }

/* 加密函数 
 * 所有加密均用这个接口，以便修改
*/
function md5Code($char)
{
  return md5(md5($char)); 
}

/*
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
 */
function make_dir($folder)
{   
    /* 切换到顶级目录 */
	chdir(SERVER_PATH.WEB_PATH);
    $reval = false;

    if (!file_exists($folder))
    {
        /* 如果目录不存在则尝试创建该目录 */
        @umask(0);

        /* 将目录路径拆分成数组 */
        preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

        /* 如果第一个字符为/则当作物理路径处理 */
        $base = ($atmp[0][0] == '/') ? '/' : '';

        /* 遍历包含路径信息的数组 */
        foreach ($atmp[1] AS $val)
        {
            if ('' != $val)
            {
                $base .= $val;

                if ('..' == $val || '.' == $val)
                {
                    /* 如果目录为.或者..则直接补/继续下一个循环 */
                    $base .= '/';

                    continue;
                }
            }
            else
            {
                continue;
            }

            $base .= '/';

            if (!file_exists($base))
            {
                /* 尝试创建目录，如果创建失败则继续循环 */
                if (@mkdir(rtrim($base, '/'), 0777))
                {
                    @chmod($base, 0777);
                    $reval = true;
                }
            }
        }
    }
    else
    {
        /* 路径已经存在。返回该路径是不是一个目录 */
        $reval = is_dir($folder);
    }
    clearstatcache();
    return $reval;
}

/**
 * 获得当前格林威治时间的时间戳
 * @return  integer
 */
function gmtime()
{
    return (time() - date('Z'));
}

/**
 * 检查文件类型
 * @access      public
 * @param       string      filename            文件名
 * @param       string      realname            真实文件名
 * @param       string      limit_ext_types     允许的文件类型
 * @return      string
 */
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
    if ($realname)
    {
        $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
    }
    else
    {
        $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
    {
        return '';
    }

    $str = $format = '';

    $file = @fopen($filename, 'rb');
    if ($file)
    {
        $str = @fread($file, 0x400); // 读取前 1024 个字节
        @fclose($file);
    }
    else
    {
        if (stristr($filename, ROOT_PATH) === false)
        {
            if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' ||
                $extname == 'xls' || $extname == 'txt'  || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' ||
                $extname == 'pdf' || $extname == 'rm'   || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' ||
                $extname == 'swf' || $extname == 'chm'  || $extname == 'sql' || $extname == 'cert'|| $extname == 'pptx' || 
                $extname == 'xlsx' || $extname == 'docx')
            {
                $format = $extname;
            }
        }
        else
        {
            return '';
        }
    }

    if ($format == '' && strlen($str) >= 2 )
    {
        if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
        {
            $format = 'mid';
        }
        elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
        {
            $format = 'wav';
        }
        elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
        {
            $format = 'jpg';
        }
        elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
        {
            $format = 'gif';
        }
        elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
        {
            $format = 'png';
        }
        elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
        {
            $format = 'bmp';
        }
        elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
        {
            $format = 'swf';
        }
        elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
        {   // D0CF11E == DOCFILE == Microsoft Office Document
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
            {
                $format = 'doc';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
            {
                $format = 'xls';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
            {
                $format = 'ppt';
            }
        } elseif (substr($str ,0, 4) == "PK\x03\x04")
        {
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'docx')
            {
                $format = 'docx';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xlsx')
            {
                $format = 'xlsx';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'pptx')
            {
                $format = 'pptx';
            }else
            {
                $format = 'zip';
            }
        } elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
        {
            $format = 'rar';
        } elseif (substr($str ,0, 4) == "\x25PDF")
        {
            $format = 'pdf';
        } elseif (substr($str ,0, 3) == "\x30\x82\x0A")
        {
            $format = 'cert';
        } elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
        {
            $format = 'chm';
        } elseif (substr($str ,0, 4) == "\x2ERMF")
        {
            $format = 'rm';
        } elseif ($extname == 'sql')
        {
            $format = 'sql';
        } elseif ($extname == 'txt')
        {
            $format = 'txt';
        }
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
    {
        $format = '';
    }

    return $format;
}

/**
 * 将上传文件转移到指定位置
 * @param string $file_name
 * @param string $target_name
 * @return blog
 */
function move_upload_file($file_name, $target_name = '')
{
    if (function_exists("move_uploaded_file"))
    {
        if (move_uploaded_file($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
        else if (copy($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
    }
    elseif (copy($file_name, $target_name))
    {
        @chmod($target_name,0755);
        return true;
    }
    return false;
}

/* 生成6位随机数 */
function get_rand_number()
{
   /* 选择一个随机的方案 */
   mt_srand((double) microtime() * 1000000);
   return  str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}

/*
 * 从html中提取图片整合到数组
 @ param string $str
 @ return array
*/
function get_pic_html($str)
{
   $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.png|\.jpg]))[\'|\"].*?[\/]?>/";    // 正则式
   preg_match_all($pattern,$str,$match);  
   return $match[1];    // 返回只带有图片路径的一维数组
}

/*
 * 从html中提取第一张图片，并替换为绝对路径
 @ param string $str
 @ return array
*/
function get_pic_html_first($str)
{
   $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.png|\.jpg]))[\'|\"].*?[\/]?>/";    // 正则式
   preg_match_all($pattern,$str,$match);  
   $first_pic =  $match[1][0];    // 返回只带有图片路径的一维数组
   if(!empty($first_pic))
   {
      $chars = "/((^http)|(^https)|(^ftp)):\/\/(\S)+\.(\w)+/";    //验证图片是否是绝对路径
      $first_pic = preg_match($chars, $first_pic) ? $first_pic : WWW_HOST.$first_pic ;
   }
   return $first_pic;
}

/*
 * 将html中的图片批量替换绝对路径
 @ param string $str
 @ return array
*/
function set_pic_html($str)
{
   //提取图片路径的src的正则表达式
   preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",$str,$matches);
   if(!empty($matches)) 
   {
      $img = $matches[2];            //注意，上面的正则表达式说明src的值是放在数组的第三个中
   } 
   if (!empty($img)) 
   {
      $patterns= array();
      $replacements = array();
      foreach($img as $imgItem)
	  {   
	     $chars = "/((^http)|(^https)|(^ftp)):\/\/(\S)+\.(\w)+/";    //验证图片是否是绝对路径
         $final_imgUrl = preg_match($chars, $imgItem) ? $imgItem : WWW_HOST.$imgItem ;
         $replacements[] = $final_imgUrl;
         $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
         $patterns[] = $img_new;
      }
	  //让数组按照key来排序
      ksort($patterns);
      ksort($replacements);
      //替换内容
      $str = preg_replace($patterns, $replacements, $str);
   }
   return $str;
}

/*
 * get
 * get方式请求资源 
 * @param string $url       基于的baseUrl
 * @return string           返回的资源内容
*/
function file_get($url)
{
   if(function_exists('file_get_contents'))
   {
       $response = file_get_contents($url);
   }
   else
   {
       $ch = curl_init();
       $timeout = 5;
       curl_setopt ($ch, CURLOPT_URL, $url);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
       $response = curl_exec($ch);
       curl_close($ch);
   }
   return $response;
}
 
/**
  * post
  * post方式请求资源
  * @param string $url       基于的baseUrl
  * @param array $keysArr    请求的参数列表
  * @param int $flag         标志位
  * @return string           返回的资源内容
*/
function file_post($url, $keysArr, $flag = 0)
{
     $ch = curl_init();
     if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
     curl_setopt($ch, CURLOPT_POST, TRUE); 
     curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr); 
     curl_setopt($ch, CURLOPT_URL, $url);
     $ret = curl_exec($ch);

     curl_close($ch);
     return $ret;
}

/*
 * 验证输入的网址
 * @param string  $url 
 * @return bool
 */
function is_url($url)
{
    $chars = "/((^http)|(^https)|(^ftp)):\/\/(\S)+\.(\w)+/";
	if (preg_match($chars, $url))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
 * 验证手机号格式
 * @param String mobile
 * @return bool
*/
function is_mobile($mobile)
{
   if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|147[0-9]{8}$/",$mobile)) 
   {
      return true;
   }
   else
   {
      return false;
   }
}

/*
 * 检查Email是否符合规范
 * param char
*/
function is_email($email)
{
   if(preg_match("/^[0-9a-zA-Z]+(?:[\_\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $email))
   {
      return true;
   }
   else
   {
      return false;
   }
}

/*
 * 设置分页
*/
function set_page($pageNum,$pageNow=1,$allNum,$href)
{  
   $pageAll = ceil($allNum/$pageNum);   // 总页数  
   
   if($pageNow>$pageAll)  // 当前页大于所有页
   {
      $pageNow = $pageAll ;
   }
   if($pageNow<1)  // 当前页小于1
   {
      $pageNow =1 ;
   }
   // 首页，上一页
   if($pageNow>1)
   {
     $pageF = $href.'1';
     $GLOBALS['smarty']->assign('pageF',$pageF);
	 $pageP = $href.($pageNow-1);
	 $GLOBALS['smarty']->assign('pageP',$pageP);
   }
   // 下一页，末页
   if($pageNow<$pageAll)
   {
     $pageL = $href.$pageAll; 
     $GLOBALS['smarty']->assign('pageL',$pageL);
	 $pageN = $href.($pageNow+1);
	 $GLOBALS['smarty']->assign('pageN',$pageN);      
   }
   // 上一页
   $GLOBALS['smarty']->assign('pageNow',$pageNow);
   $GLOBALS['smarty']->assign('pageAll',$pageAll);
   for($i=1;$i<=$pageAll;$i++)
   {
      $pageArr[] = array(
	               'num'=>$i,
				   'uri'=> $href.$i, 
	               );
   }
   $GLOBALS['smarty']->assign('pageArr',$pageArr);
}

/*
* 计算2个时间段的月份差
* @param $st开始时间 $et结束时间(时间戳格式)
* @return $total 返回的差值 
*/
function getMonthNum($st, $et)
{
	   $s_m = date('n', $st);
	   $e_m = date('n', $et);
	   $s_y = date('Y', $st);
	   $e_y = date('Y', $et);
	   $total = 12 - $s_m + ($e_y - $s_y - 1) * 12 + $e_m; //计算月份差
	   return $total;
}

}
?>