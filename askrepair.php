<?php
/*
 * 投诉与建议
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");
Transaction::checkUserCommunity($redirectUrl='askrepair.html');

class Askrepair extends Common
{
   /*
   * 根据status提取用户的报修申请
   */
   static function getAskrepairLog($community_id,$user_id,$pageNow,$pageNum,$status)
   {
      $start = ($pageNow-1)*$pageNum;
      $sql = "select ask_id, content, name, phone, add_time, status ,reply from ".$GLOBALS['Base']->table('askrepair')." ".
	         "where community_id=$community_id and user_id=$user_id ";
	  if($status==0)
	  {		 
	     $sql .= "and status=0 ";
	  }
	  else
	  {
	     $sql .= "and status>0 ";
	  }
	  $sql .= "order by ask_id desc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql); 
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  foreach($res as $k=>$v)
	  {
	     $res[$k]['add_time'] = date('Y-n-d',$v['add_time']);
	  }
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res;
	  return $arr;
   }
}

$act = isset($_REQUEST['act']) ? $Common->charFormat($_REQUEST['act']): 'default';

/* 显示报修申请 */
if($act=='default')
{ 
   $phone = $Mysql->getOne("select contact_phone from ".$Base->table('community')." where community_id=".$_SESSION['wx']['community_id']." ");
   $smarty->assign('phone',$phone);
}
/* 执行报修申请 */
elseif($act=='act_default')
{
   $name = isset($_POST['name']) ? $Common->charFormat($_POST['name']): '';
   $phone = isset($_POST['phone']) ? $Common->charFormat($_POST['phone']): '';
   $content = isset($_POST['content']) ? $Common->charFormat($_POST['content']): '';
   if(empty($name))
   {
      $msg = array(
	  'error'=>1,
	  'data'=>'请输入姓名',
	  );
   }
   elseif(empty($phone))
   {
      $msg = array(
	  'error'=>1,
	  'data'=>'请输入联系电话',
	  );
   }
   elseif(empty($content))
   {
      $msg = array(
	  'error'=>1,
	  'data'=>'请输入报修内容',
	  );
   }
   else
   {
      $data = array(
	  'user_id'=>$_SESSION['wx']['user_id'],
	  'community_id'=>$_SESSION['wx']['community_id'],
	  'name'=>$name,
	  'phone'=>$phone,
	  'content'=>$content,
	  'add_time'=>time(),
	  );
	  $table = $Base->table('askrepair');
	  if($Mysql->insert($data,$table))
	  {
	     $msg = array(
		 'error'=>0,
		 'data'=>'提交成功，请等待受理',
		 'href'=>'askrepair.html?act=mine',
		 );
	  }
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;
}
/* 我的报修申请 */
elseif($act=='mine')
{
   $status = isset($_REQUEST['status']) ? intval($_REQUEST['status']): 0 ; 
   $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1;
   $pageNum =10 ;
   $log = Askrepair::getAskrepairLog($_SESSION['wx']['community_id'],$_SESSION['wx']['user_id'],$pageNow,$pageNum,$status); 
   $Common->set_page($pageNum,$pageNow,$log['resNum'],$href = 'askrepair.html?act=mine&status='.$status.'&page=');
   $smarty->assign('status',$status);
   $smarty->assign('log',$log['res']);
   
}
/* 撤销报修 */
elseif($act=='cancel')
{
   $ask_id = isset($_POST['ask_id']) ? intval($_POST['ask_id']): 0;
   $sql = "select ask_id from ".$Base->table('askrepair')." where ask_id=$ask_id and user_id=".$_SESSION['wx']['user_id']." and community_id=".$_SESSION['wx']['community_id']." ";
   $ask_id = $Mysql->getOne($sql);
   $msg = array('error'=>1,'data'=>'系统错误，操作失败');
   if($ask_id)
   {
       $sql = "update ".$Base->table('askrepair')." set status=2 where ask_id=$ask_id ";
	   if($Mysql->query($sql))
	   {
	      $msg = array(
		  'error'=>0,
		  'data'=>'成功撤销该报修内容',
		  );
	   }
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;
}

$smarty->assign('act',$act);
$smarty->display('askrepair.htm');


?>