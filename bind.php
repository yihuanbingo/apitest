<?php
/*
 * 绑定小区、房号
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");

/* 检查是否有openid */
Transaction::checkUserOpenid();

$act = isset($_REQUEST['act']) ? $Common->charFormat($_REQUEST['act']): 'community';
$redirectUrl = isset($_REQUEST['redirectUrl']) ? $Common->charFormat($_REQUEST['redirectUrl']): '' ;
if(!empty($redirectUrl))
{
   $_SESSION['wx']['redirectUrl'] = $redirectUrl;
}

/* 绑定到小区 */
if($act=='community')
{ 
   $keywords = isset($_REQUEST['keywords']) ? $Common->charFormat(rawurldecode($_REQUEST['keywords'])): '';
   if(!empty($keywords))
   {
      $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1 ;
	  $pageNum = 10;
      $res = Transaction::getCommunityByKeywords($keywords,$pageNow,$pageNum);
	  $smarty->assign('res',$res['res']);
	  $Common->set_page($pageNum,$pageNow,$res['resNum'],$href = 'bind.html?act=community&keywords='.rawurlencode($keywords).'&page='); 
	  $smarty->assign('keywords',$keywords); 
   }
}
/* 绑定到小区操作 */
elseif($act=='act_community')
{
   $community_id = isset($_POST['community_id']) ? intval($_POST['community_id']):0;
   $msg = array('error'=>1,'data'=>'请求错误，请稍后重试');
   if($community_id>0)
   {
      $data = array('community_id'=>$community_id);
	  $table = $Base->table('user');
	  $where = array('user_id'=>$_SESSION['wx']['user_id']);
	  if($Mysql->update($data,$table,$where))
	  {
	     $_SESSION['wx']['community_id'] = $community_id;
	     $msg = array(
		 'error'=>0,
		 'data'=>'绑定成功，现在为您跳转'.$_SESSION['wx']['openid'],
		 'href'=>$_SESSION['wx']['redirectUrl'],
		 );
	  }   
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;    
}
/* 绑定房号 */
elseif($act=='householder')
{
   $keywords = isset($_REQUEST['keywords']) ? $Common->charFormat(rawurldecode($_REQUEST['keywords'])): '';
   if(!empty($keywords))
   {
      $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1 ;
	  $pageNum = 10;
      $res = Transaction::getHouseholderByKeywords($_SESSION['wx']['community_id'],$keywords,$pageNow,$pageNum);
	  $smarty->assign('res',$res['res']);
	  $Common->set_page($pageNum,$pageNow,$res['resNum'],$href = 'bind.html?act=householder&keywords='.rawurlencode($keywords).'&page='); 
	  $smarty->assign('keywords',$keywords); 
   }
}
/* 绑定房号操作 */
elseif($act=='act_householder')
{
   $householder_id = isset($_POST['householder_id']) ? intval($_POST['householder_id']): 0;
   $msg = array('error'=>1,'data'=>'请求错误，请稍后重试');
   if($householder_id>0)
   {
      $data = array('householder_id'=>$householder_id);
	  $table = $Base->table('user');
	  $where = array('user_id'=>$_SESSION['wx']['user_id']);
	  if($Mysql->update($data,$table,$where))
	  {
	     $_SESSION['wx']['householder_id'] = $householder_id;
	     $msg = array(
		 'error'=>0,
		 'data'=>'绑定成功，现在为您跳转',
		 'href'=>$_SESSION['wx']['redirectUrl'],
		 );
	  }   
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;    
}
/* 搜索小区或单元房号 */
elseif($act=='search')
{
   $keywords = isset($_POST['keywords']) ? $Common->charFormat($_POST['keywords']):'';
   $type = isset($_POST['type']) ? $Common->charFormat($_POST['type']): 'community' ;
   if(!empty($keywords))
   {
      $keywords = rawurlencode($keywords);
	  $msg = array('error'=>0,'data'=>'','href'=>'bind.html?act='.$type.'&keywords='.$keywords.'');
   }
   else
   {
      $data = $type=='community' ? '请输入所在小区名称': '请输入业主手机号后6位';
      $msg = array('error'=>1,'data'=>$data);   
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;
}
$smarty->assign('act',$act);
$smarty->display('bind.htm');
?>