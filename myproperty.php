<?php
/*
 * 我的物业
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");
Transaction::checkUserCommunity($redirectUrl='myproperty.html');
Transaction::checkExistsHouseholder();
Transaction::checkUserHouseholder($redirectUrl='myproperty.html');

class Myproperty extends Common
{
   /*
   * 提取某手机号的代收快递 
   */  
   static function getExpressByPhone($community_id,$phone,$pageNow,$pageNum)
   {
      $start = ($pageNow-1)*$pageNum;
	  $sql = "select log_id, name, phone, express_name, express_sn, update_time, status from ".$GLOBALS['Base']->table('log_express')." ".
	         "where community_id=$community_id and phone ='$phone' order by status asc, log_id desc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql);
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  foreach($res as $k=>$v)
	  {
	     $res[$k]['update_time'] = date('Y-n-d',$v['update_time']);
	  }
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res;
	  return $arr;
   }
}

$act = isset($_REQUEST['act']) ? $Common->charFormat($_REQUEST['act']): 'default';

/* 显示默认首页 */
if($act=='default')
{
   $sql = "select house_number from ".$Base->table('householder')." where householder_id=".$_SESSION['wx']['householder_id']." ";
   $smarty->assign('house_number',$Mysql->getOne($sql));
}
/* 代收快递 */
elseif($act=='express')
{
   $phone = isset($_REQUEST['phone']) ? $Common->charFormat(rawurldecode($_REQUEST['phone'])): '' ;
   $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1 ;
   $pageNum = 10;
   $log = Myproperty::getExpressByPhone($_SESSION['wx']['community_id'],$phone,$pageNow,$pageNum);
   $Common->set_page($pageNum,$pageNow,$log['resNum'],$href = 'myproperty.html?act=express&phone='.rawurlencode($phone).'&page=');
   $smarty->assign('log',$log['res']); 
   $smarty->assign('phone',$phone);
}
/* 搜索代收快递 */
elseif($act=='search')
{
   $phone = isset($_POST['phone']) ? $Common->charFormat($_POST['phone']):'';
   if(!empty($phone))
   {
      $phone = rawurlencode($phone);
	  $msg = array('error'=>0,'data'=>'','href'=>'myproperty.html?act=express&phone='.$phone.'');
   }
   else
   {
      $msg = array('error'=>1,'data'=>'请输入收件人电话进行搜索');   
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit; 
}
/* 将快递设为已领取 */
elseif($act=='setExpressReceived')
{
   $log_id = isset($_REQUEST['log_id']) ? intval($_REQUEST['log_id']): 0 ;
   $msg = array('error'=>1,'data'=>'请求错误，请稍后重试');
   if($log_id>0)
   {
      if($Mysql->query("update ".$Base->table('log_express')." set status=1 where log_id=$log_id "))
	  {
	     $msg = array('error'=>0,'data'=>'成功将该快递设为已领取');
	  }
   }
   $msg = $Json->encode($msg);
   echo $msg;
   exit;   
}
/* 物业费查询 */
elseif($act=='property')
{
   $sql = "select house_number from ".$Base->table('householder')." where householder_id=".$_SESSION['wx']['householder_id']." ";
   $smarty->assign('house_number',$Mysql->getOne($sql));
   $sql = "select * from ".$Base->table('log_property')." where householder_id=".$_SESSION['wx']['householder_id']."";
   $row = $Mysql->getRow($sql);
   $row['status'] = time() < strtotime('next month',$row['pay_late']) ? 1: 0 ;  //strtotime为缴费时的下个月第一天
   if($row['status']==0) //欠费，则计算欠费多少
   {
      $row['arrearage_months'] = $Common::getMonthNum($row['pay_late'],time());
      $row['arrearage'] = number_format($row['arrearage_months'] * $row['pay_month'],2); 
   }
   $row['pay_late'] = date('Y年n月',$row['pay_late']);
   $smarty->assign('row',$row);
}
/* 停车费查询 */
elseif($act=='park')
{
   $sql = "select house_number from ".$Base->table('householder')." where householder_id=".$_SESSION['wx']['householder_id']." ";
   $smarty->assign('house_number',$Mysql->getOne($sql));
   $sql = "select * from ".$Base->table('log_park')." where householder_id=".$_SESSION['wx']['householder_id']."";
   $row = $Mysql->getRow($sql);
   $row['status'] = time() < strtotime('next month',$row['pay_late']) ? 1: 0 ;  //strtotime为缴费时的下个月第一天
   if($row['status']==0) //欠费，则计算欠费多少
   {
      $row['arrearage_months'] = $Common::getMonthNum($row['pay_late'],time());
      $row['arrearage'] = number_format($row['arrearage_months'] * $row['pay_month'],2); 
   }
   $row['pay_late'] = date('Y年n月',$row['pay_late']);
   $smarty->assign('row',$row);
}

$smarty->assign('act',$act);
$smarty->display('myproperty.htm');
?>