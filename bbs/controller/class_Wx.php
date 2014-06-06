<?php
/*
* 微信用户同步登录类
+-----------------------------
* 微信用户访问时会传递openid值
+-----------------------------
*/
class Wx {

   static $appid = 'wx91c5fd7d0669a634';
   static $appsecret = '70fd10a8dfbfdb8f218e3cd4307e90ad';
   static $member_url = "https://api.weixin.qq.com/cgi-bin/user/info?";

   /*
   * 根据用户的openid提取user_id，community_id，householder_id，并保存至session
   +--------------------------------------------------------------------------------------
   + openid是用户进行其他所有操作的基础，几乎都通过链接的url传递，GET
   +--------------------------------------------------------------------------------------
   */
   static function getWxUserInfo($DB, $openid)
   { 

	  $sql = "select user_id from bs_user where openid = '$openid ' ";
	  $wx_user_id = $DB->fetch_one($sql);;
	  if($wx_user_id>0)   //已关注
	   {
	     $_SESSION['wx']['user_id'] = $wx_user_id;
	     $_SESSION['wx']['openid'] = $openid;   
		 return true;   
	  }
   }

   /*
   * 检验openid值是否是已关注微信的合法用户
   * @param openid
   */
   static function checkWxLegal($DB, $openid)
   {
      $sql = "select bu.community_id, bc.community_name from bs_user as bu, bs_community as bc ".
	         "where bc.community_id=bu.community_id and bu.openid='$openid' ";
	  $community = $DB->fetch_one_array($sql);
	  /* 该用户所属社区 */
	  if(empty($community))
	  {
		 $_SESSION['wx']['openid'] = $openid;
	     $template = template("mobile_wxillegal.html" );
		 $template->assign( 'openid', $openid);
	     $template->output();
	  }
	  else
	  {  
	     $_SESSION['bbs']['community_id'] = $community['community_id'];
		 $_SESSION['bbs']['community_name'] = $community['community_name']; 
	  }
   }
   
   /*
    * 获取access_token
   */
   private function getAccess_token()
   {   
       $token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$appid.'&secret='.self::$appsecret;
       $access_token = $this->file_get($token_url);
	   $access_token = json_decode($access_token);  
	   return $access_token;   
   } 
   
   /*
   * 获取有效的access_token
   */
   private function setAccess_token($DB)
   {
       $expire =  time() + 60;   //防止响应延时，access_token提前1分钟过期
       $sql = "select access_token from bs_access_token where expire > $expire limit 0, 1 "; 
	   $access_token = $DB->fetch_one($sql); 
	   /* 数据库中access_token可用 */
	   if($access_token)
	   {  
	      return $access_token;
	   }    
	   /* 重新获取access_token，并保存数据库 */
       else
	   {
	      $access_token = $this->getAccess_token();
		  $sql = "update bs_access_token set access_token='".$access_token->access_token."', expire=".(time()+$access_token->expires_in)." ";
		  $DB->query($sql);
          return $access_token->access_token;  
	   }
   } 
   
   /*
   * 获取用户的基本信息，并更新【写进】数据库，同时执行一次登录
   */
   public function getMemberInfo($DB, $openid)
   {  
      $sql = "select uid, nickname, groupid, headimgurl from phpsay_member where openid='$openid' "; 
	  $row = $DB->fetch_one_array($sql);
	  if(empty($row))   //数据库没有信息，则从微信获取并插入数据库
	  {
	     $access_token = $this->setAccess_token($DB);   
	     $url = self::$member_url."access_token=".$access_token."&openid=".$openid."&lang=zh_CN";  
	     $memberInfo = $this->file_get($url);
	     $memberInfo = json_decode($memberInfo);
		
		 $nickname = $memberInfo->nickname;
		 if($DB->fetch_one("select uid from phpsay_member where nickname='$nickname'"))   //昵称已存在
		 {
		    $_SESSION['tmpUser'] = array('openid'=>$openid,'headimgurl'=>$memberInfo->headimgurl); 
		    $template = template("mobile_setname.html");
			$template->assign( 'PHPSayConfig', $GLOBALS['PHPSayConfig'] );
			$template->assign( 'CommunityName', $_SESSION['bbs']['community_name'] ); 
			$template->assign('nickname',$nickname);
			$template->output();
		 }
		 else   //插入数据库
		 { 
	        $data = array(
	                      'nickname'=>$nickname,
	                      'headimgurl'=>substr($memberInfo->headimgurl,0,-2),   //去掉最后两个尺寸参数字符
	                      'regtime'=>time(),
					      'openid'=>$openid
					      );
		    $DB->query( $DB->insert_sql("`phpsay_member`",$data) );
		    $sql = "select uid, nickname, groupid, headimgurl from phpsay_member where openid='$openid' ";
	        $row = $DB->fetch_one_array($sql);	    
	     }
	  }  
      $_SESSION['bbs']['uid'] = $row['uid'];
	  $_SESSION['bbs']['nickname'] = $row['nickname'];
	  $_SESSION['bbs']['group'] = $row['groupid'];
	  $_SESSION['bbs']['avatar'] = empty($row['headimgurl']) ? '/bbs/static/avatar.jpg' :$row['headimgurl'].'/132';
   }

   /*
   * 刷新头像
   */
   public function refreshAvatar($DB,$uid)
   {
      $sql = "select openid from phpsay_member where uid=$uid ";
	  $openid = $DB->fetch_one($sql);
      $access_token = $this->setAccess_token($DB); 
	  $url = self::$member_url."access_token=".$access_token."&openid=".$openid."&lang=zh_CN";    
	  $memberInfo = $this->file_get($url); 
	  $memberInfo = json_decode($memberInfo);
	  $data = array(
	                'headimgurl'=>substr($memberInfo->headimgurl,0,-2),   //去掉最后两个尺寸参数字符
                    );
	  if($DB->query( $DB->update_sql("`phpsay_member`", $data, "`uid`=".$uid) ))
	  {
	     $_SESSION['bbs']['avatar'] = $data['headimgurl'].'/132';
         return true;
	  }
      return false;
   }
   
   /*
   * get
   * get方式请求资源 
   * @param string $url       基于的baseUrl
   * @return string           返回的资源内容
   */
   private function file_get($url)
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
   private function file_post($url, $keysArr, $flag = 0)
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
}

?>