<?php
/*
 * 首页
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);    
require("includes/init.php");  
require("includes/cls_wx_checkSignature.php");

/*
* 微信返回的内容
+------------------------------------------------------------------------------
+ 实际应用中，有的消息需要验证签名，有的则不需要
+ 初步判断，凡是用户主动发送的消息，都需要验证签名；如菜单点击等不需要验证签名
+ 为了统一入口，先取得消息类型，再根据类型决定是否需要验证签名
+------------------------------------------------------------------------------
*/
$WxPostStr = Transaction::getWxPostStr();  
$MsgType = $WxPostStr->MsgType;
$FromUserName = $WxPostStr->FromUserName;   //用户微信号
$ToUserName = $WxPostStr->ToUserName;       //开发者微信号

/* event，不需要验证签名 */
if($MsgType=='event')
{ 
   $Event = $WxPostStr->Event;                 //事件名称

   if($Event=='CLICK')   //菜单栏点击事件
   {
      $EventKey = $WxPostStr->EventKey;        //事件key值
	  $sql = "select bc.community_name from ".$Base->table('community')." as bc, ".$Base->table('user')." as bu ".
	         "where bc.community_id=bu.community_id and bu.openid='".$WxPostStr->FromUserName."' ";   //提取小区名称
	  /* 选择小区 */
	  if($EventKey=='communityintro')   
	  { 
	     $data[] = array(
		    'title'=>'选择小区',
			'description'=>'如果你还没有绑定小区，请在这里进行绑定；如果你已绑定小区，可在这里解绑原来的小区，并重新绑定',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'communityintro.html?openid='.$WxPostStr->FromUserName,
		 );
	  }
	  /* 最新通知 */
	  elseif($EventKey=='notice')
	  {
	     $community_name = $Mysql->getOne($sql);
	     $data[] = array(
		    'title'=>$community_name.'最新通知',
			'description'=>'停水停电、维修通知、社区活动......小区动态即刻送达！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'notice.html?openid='.$WxPostStr->FromUserName,
		 );
	  }
	  /* 报修申请 */
	  elseif($EventKey=='askrepair')
	  {
	     $community_name = $Mysql->getOne($sql);
		 $data[] = array(
			'title'=> empty($community_name) ? '报修申请' : $community_name.'维修报备',
			'description'=>'若你有维修需要，可以这里给物业留言，我们会在第一时间进行处理并回馈，欢迎大家监督！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'askrepair.html?openid='.$WxPostStr->FromUserName,
		  );
	  }
	  /* 投诉建议 */
	  elseif($EventKey=='advice')
      {
		 $community_name = $Mysql->getOne($sql);
		 $data[] = array(
			'title'=>$community_name.'投诉建议',
			'description'=>'我们竭诚为您服务，但工作中难免存在疏忽。如果您对物业的工作有建议或投诉，请在这里告诉我们，我们将在第一时间进行处理并回馈，感谢您的支持！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'advice.html?openid='.$WxPostStr->FromUserName,
	     );		 
	  }
	  /* 我的物业 */
	  elseif($EventKey=='myproperty')
	  {
	     $community_name = $Mysql->getOne($sql);
		 $data[] = array(
			'title'=>empty($community_name) ? '我的物业': $community_name.'物业管理',
			'description'=>'物管费查询、停车费查询、快递包裹查询，一键查询，就是这么简单！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'myproperty.html?openid='.$WxPostStr->FromUserName,
	     );
	  }
	  /* 论坛 */
	  elseif($EventKey=='bbs')
	  {
	     $community_name = $Mysql->getOne($sql);
	     $data[] = array(
			'title'=>empty($community_name) ? '点击进入小区论坛': '点击进入'.$community_name.'论坛',
			'description'=>'吃喝玩乐，再也不缺小伙伴啦！还宅在家里发霉？楼上楼下的美女帅哥，都来这里啦！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'bbs/?openid='.$WxPostStr->FromUserName,
	     );
	  }
	  /* 生活导航 */
	  elseif($EventKey=='lifenav')
	  {
	     $community_name = $Mysql->getOne($sql);
	     $data[] = array(
			'title'=>$community_name.'生活百事通',
			'description'=>'管道疏通、废品回收、家电维修、开锁换锁、快递收寄、送水送餐......生活百事通，一切尽在掌握中！',
			'picUrl'=>API_HOST.'templates/images/weixin/'.$EventKey.'.jpg',
			'url'=>API_HOST.'lifenav.html?openid='.$WxPostStr->FromUserName,
	     );	     
	  }
	  echo Transaction::sendNews($data,$FromUserName,$ToUserName);  
	  exit; 
   }
   elseif($Event=='subscribe')     //关注事件
   {		
   	  $EventKey = $WxPostStr->EventKey!="" ? $WxPostStr->EventKey : 'qrscene_0' ;	   //参数值，默认为0
   	  $community_id = intval(substr($EventKey,8));
	  /* 默认返回的信息 */
	  $message = "Hi，欢迎来到小区快帮！\n小区快帮是一个基于本小区的私密社区，".
		         "在这里，你可以查询物管、停车费、快递包裹，也可以在这里找话题、找兴趣、找邻居，".
				 "可以参与小区活动，也可以亲手组织，在这里，你有一个非常非常Big的大家庭，你可以潜水冒泡，也可以成为意见领袖！".
				 "\n但是，你还没有绑定小区哦，".
				 "请先 <a href='".API_HOST."bind.html?openid=".$WxPostStr->FromUserName."&redirectUrl=communityintro.html'>绑定小区</a>";
      /* 关注过 */
	  if($Mysql->getOne("select user_id from ".$Base->table('user')." where openid = '".$FromUserName."'"))
	  {
	      /* 传递参数大于0，则更新 */
	      if($community_id>0)
		  {
	         $data = array(
      		    'community_id'=>$community_id
                );
      	     $table = $Base->table('user');
      	     $where = array('openid'=>$FromUserName);
      	     $Mysql->update($data,$table,$where);	
	      }
	  }
	  /* 未关注过 */
	  else
	  {  
  	      $data = array(
	      'openid'=>$FromUserName,
	      'reg_time'=>time(),
	      'community_id'=>$community_id
	      );
		  $table = $Base->table('user');
		  $Mysql->insert($data,$table);
	  }
	  $sql = "select community_name from ".$Base->table('community')." where community_id=".
	         "(select community_id from ".$Base->table('user')." where openid='".$FromUserName."' )";
	  $community_name  = $Mysql->getOne($sql);
	  if($community_name)
	  {
	     $message = "Hi，欢迎来到小区快帮".$community_name."！\n 小区快帮是一个基于本小区的私密社区，".
		            "在这里，你可以查询物管、停车费、快递包裹，也可以在这里找话题、找兴趣、找邻居，".
				    "可以参与小区活动，也可以亲手组织，在这里，你有一个非常非常Big的大家庭，".
				    "你可以潜水冒泡，也可以成为意见领袖！\n请在下面选择快捷操作：".
				    "\n\n<a href='".API_HOST."bbs/?openid=".$WxPostStr->FromUserName."'>小区论坛 </a> ".
				    "\n\n<a href='http://3g.inbai.com/interface/signin/signin.jsp'>天天有礼</a> ".
				    "\n\n<a href='".API_HOST."lifenav.html?openid=".$WxPostStr->FromUserName."'>生活百事通</a> ".
				    "\n\n<a href='".API_HOST."notice.html?openid=".$WxPostStr->FromUserName."'>物业通知</a> ".
				    "\n\n如果你要重新绑定小区，请 ".
				    "<a href='".API_HOST."bind.html?openid=".$WxPostStr->FromUserName."&redirectUrl=communityintro.html'>重新绑定小区</a>";
	  }
      echo Transaction::sendText($message,$FromUserName,$ToUserName);
	  exit;
   }
   elseif($Event=='unsubscribe')   //取消关注
   {
      //todo  
   }
}
/* text、image、void、video、location、link，用户主动发送，需要验证签名*/
else
{
   $signature = isset($_GET['signature']) ? $Common->charFormat($_GET['signature']): false ;
   $timestamp = isset($_GET['timestamp']) ? $Common->charFormat($_GET['timestamp']): false ;
   $nonce = isset($_GET['nonce']) ? $Common->charFormat($_GET['nonce']): false ;
   $echoStr = isset($_GET['echostr']) ? $_GET['echostr']: false ;
   //签名验证成功
   if(wx_checkSignature::checkSignature($signature, $timestamp, $nonce))   
   {
      /* 用户发送的文本信息 */
      if($MsgType=='text')
	  {
	     $content = $WxPostStr->Content;
	     if(Transaction::overridestripos($content,'论坛') || Transaction::overridestripos($content,'bbs'))
		 {
		    $message = "Hi，你是要进入小区论坛吗～在论坛，可以找话题、找兴趣、找邻居，".
				       "可以参与小区活动，也可以亲手组织，".
				       "可以潜水冒泡，也可以成为意见领袖！".
					   "\n<a href='".API_HOST."bbs/?openid=".$WxPostStr->FromUserName."'>点击进入小区论坛 </a>";
		 }
		 elseif(Transaction::overridestripos($content,'物业') || Transaction::overridestripos($content,'物管'))
		 {
		    $message = "物业费查询，停车费查询，快递包裹查询；一键查询，就是这么简单！".
					   "\n<a href='".API_HOST."myproperty.html?openid=".$WxPostStr->FromUserName."'>点击进入我的物业 </a>";
		 }
		 elseif(Transaction::overridestripos($content,'登陆'))
		 {
		    $message = "亲，你现在已经登陆了，请点击菜单栏或回复关键字进行各项操作";
		 }
		 echo Transaction::sendText($message,$FromUserName,$ToUserName);
		 exit;
	  }
      echo $echoStr;
      exit;
   }
}

?>