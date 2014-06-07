<?php
/*
 * 小区介绍页
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");
Transaction::checkUserCommunity($redirectUrl='communityintro.html');
 
$act = isset($_REQUEST['act']) ? $Common->charFormat($_REQUEST['act']): 'default';
/* 默认显示绑定与小区介绍 */
if($act=='default')
{
   //todo     
   $sql = "select community_name from ".$Base->table('community')." where community_id=".$_SESSION['wx']['community_id']."";
   $smarty->assign('community_name',$Mysql->getOne($sql));
}
/* 小区介绍 */
elseif($act=='intro')
{
   $sql = "select community_name, intro, verify_time from ".$Base->table('community')." where community_id=".$_SESSION['wx']['community_id']." ";
   $row = $Mysql->getRow($sql);
   $row['verify_time'] = date('Y-n-d',$row['verify_time']);
   $row['intro'] = $Common::set_pic_html($row['intro']);   //绝对化图片路径
   $smarty->assign('row',$row);
}

$smarty->assign('act',$act);
$smarty->display('communityintro.htm');

?>