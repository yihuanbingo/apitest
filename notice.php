<?php
/*
 * 公告
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");
Transaction::checkUserCommunity($redirectUrl='notice.html');

class Notice extends Common
{
   /*
   * 提取某个小区的通知列表
   */
   static function getNoticeLog($community_id,$pageNow,$pageNum)
   {
      $start = ($pageNow-1)*$pageNum;
	  $sql = "select notice_id, title, content, add_time from ".$GLOBALS['Base']->table('notice')." ".
	         "where community_id=$community_id ".
			 //"and status=1 ".
			 "order by notice_id desc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql);
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  foreach($res as $k=>$v)
	  {
	      $res[$k]['add_time'] = date('Y年n月d日',$v['add_time']);
		  $res[$k]['first_pic'] = parent::get_pic_html_first($v['content']);
		  $res[$k]['content'] = mb_substr(parent::charFormat($v['content']),0,80)."...";
	  } 
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res; 
	  return $arr;
   }
   
   /*
   * 提取某个通知的详情
   */
   static function getNoticeDesc($notice_id)
   {
      $sql = "select title, content, add_time from ".$GLOBALS['Base']->table('notice')." ".
	         "where notice_id=$notice_id ".
			 //"and status=1 ".
			 "limit 1";
	  $row = $GLOBALS['Mysql']->getRow($sql);
	  if(!empty($row))
	  {
	     $row['add_time'] = date('Y-n-d',$row['add_time']);
		 $row['content'] = parent::set_pic_html($row['content']);   //绝对化图片路径
      }
	  return $row;
   }
   
}

$notice_id = isset($_REQUEST['notice_id']) ? intval($_REQUEST['notice_id']): 0;

/* 通知列表 */
if($notice_id==0)
{
   $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1;
   $pageNum = 10;
   $log = Notice::getNoticeLog($_SESSION['wx']['community_id'],$pageNow,$pageNum);
   $Common->set_page($pageNum,$pageNow,$log['resNum'],$href = 'notice.html?page=');
   $smarty->assign('log',$log['res']);;
}
/* 具体通知 */
else
{ 
   $row = Notice::getNoticeDesc($notice_id);  
   $smarty->assign('row',$row);
}

$smarty->assign('notice_id',$notice_id);
$smarty->display('notice.htm');

?>