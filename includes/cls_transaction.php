<?php
/*
 * 通用函数（参与逻辑与数据库）
 * author yuanjiang @2.16.2013
*/
if(!defined('IN_BS'))
{
  die('hacking attempt');
}
 
class Transaction extends Common
{

   /*
   * 提取微信返回的主体内容
   */
   static function getWxPostStr()
   { 
      $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	  if(!empty($postStr))
	  {
	     $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);    //解析后的xml
	     return $postObj;
	  }
   }
   
   /*
   * 根据用户的openid提取user_id，community_id，householder_id，并保存至session
   +--------------------------------------------------------------------------------------
   + openid是用户进行其他所有操作的基础，几乎都通过链接的url传递，GET
   +--------------------------------------------------------------------------------------
   */
   static function getWxUserInfo()
   { 
      $openid = isset($_REQUEST['openid']) ? parent::charFormat($_REQUEST['openid']): '' ;
	  $sql = "select user_id, community_id, householder_id from ".$GLOBALS['Base']->table('user')." where openid = '$openid ' ";
	  $row = $GLOBALS['Mysql']->getRow($sql);
	  if($row['user_id']>0)   //已关注
	   {
	     $_SESSION['wx'] = $row;
	     $_SESSION['wx']['openid'] = $openid;   
		 return true;   
	  }
   }
   
   /*
   * 验证是否session中是否有openid
   +-------------------------------------------------
   + session保存的openid是用户进行绝大多数操作的基础
   +-------------------------------------------------
   */
   static function checkUserOpenid()
   {
      if(empty($_SESSION['wx']['openid']))
	  {
	     echo '只有关注小区快帮的微信用户才能执行此操作';
		 exit;
	  }
   }
   
   /*
   * 验证是否session有保存community_id，且大于0
   */
   static function checkUserCommunity($redirectUrl)
   {
      if(empty($_SESSION['wx']['community_id']) || $_SESSION['wx']['community_id']<=0)
	  {
	     $GLOBALS['smarty']->assign('bind','community');
		 $GLOBALS['smarty']->assign('redirectUrl',$redirectUrl);
		 $GLOBALS['smarty']->display('notbinded.htm');
		 exit;
	  }
	  return true;
   }
   
   /*
   * 验证是否session有保存householder_id，且大于0
   */
   static function checkUserHouseholder($redirectUrl)
   {
      if(empty($_SESSION['wx']['householder_id']) || $_SESSION['wx']['householder_id']<=0)
	  {
	     $GLOBALS['smarty']->assign('bind','householder');
		 $GLOBALS['smarty']->assign('redirectUrl',$redirectUrl);
		 $GLOBALS['smarty']->display('notbinded.htm');
		 exit;
	  }
	  return true;
   }
   
   /*
   * 验证该小区是否有上传业主信息
   */
   static function checkExistsHouseholder()
   {
      $sql = "select count(householder_id) from ".$GLOBALS['Base']->table('householder')." where community_id=".$_SESSION['wx']['community_id']." ";
      $householder_number = $GLOBALS['Mysql']->getOne($sql);
	  if($householder_number==0)
	  {
	     $GLOBALS['smarty']->assign('openid',$_REQUEST['openid']);
         $GLOBALS['smarty']->assign('bind','notExistshouseholder');
		 $GLOBALS['smarty']->display('notbinded.htm');
		 exit;
	  }
	  return true;
   }
   
   /*
   * 微信回复文本消息
   @ param ....
   return String
   */
   static function sendText($data,$fromUsername,$toUsername)
   {
      $time = time();
      $msgType = 'text';
      $textTpl = "<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Content><![CDATA[%s]]></Content>
              </xml>";
      $str = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$data);
      return $str;
   }

   /*
   * 微信回复图文消息
   @ data为一个二维数组，表示多条图文消息
   return String
   */
   static function sendNews($data,$fromUsername,$toUsername)
   {
       $time = time();
       $msgType = 'news';
       $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <ArticleCount>%s</ArticleCount>
                   <Articles>";
       $articleCount = count($data);
       $content =  sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $articleCount); 
       $mid = '';
	   foreach($data as $v)
	   {
	      $textTpl ="<item>
                     <Title><![CDATA[%s]]></Title> 
                     <Description><![CDATA[%s]]></Description>
                     <PicUrl><![CDATA[%s]]></PicUrl>
                     <Url><![CDATA[%s]]></Url>
                     </item>";
          $mid .=  sprintf($textTpl,$v['title'],$v['description'],$v['picUrl'],$v['url']);
	    }
       $textTpl = "</Articles>
		           </xml>"; 			
       $str = $content.$mid.$textTpl;
	   return $str;     	  
   }
   
   /*
   * 根据搜索关键词提取小区列表
   */
   static function getCommunityByKeywords($keywords,$pageNow,$pageNum)
   {
      $start = ($pageNow-1)*$pageNum;
	  $sql = "select community_id, community_name, city, district, address from ".$GLOBALS['Base']->table('community')." where status=1 ".
	         "and community_name like '%$keywords%' order by community_id asc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql);
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res;
	  return $arr;
   }
   
   /*
   * 根据搜索关键词提取房号列表
   */
   static function getHouseholderByKeywords($community_id,$keywords,$pageNow,$pageNum)
   {
      $start = ($pageNow-1)*$pageNum;
	  $sql = "select householder_id, house_number from ".$GLOBALS['Base']->table('householder')." where community_id=$community_id ".
	         "and mobile like '%$keywords' order by householder_id asc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql);
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res;
	  return $arr;
   }
   
}
?>