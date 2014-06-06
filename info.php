<?php
/*
 * 文章管理
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");

class Info extends Common
{
	//获取文章列表
	static function getInfoList($pageNow,$pageNum)
	{
		$start = ($pageNow-1)*$pageNum;
		
		$sql = "select info_id, title, content, add_time from ".$GLOBALS['Base']->table('info')." order by info_id desc ";
		$resNum = $GLOBALS['Mysql']->getCount($sql);
		$sql .= "limit $start, $pageNum ";
		$res = $GLOBALS['Mysql']->getAll($sql);
		foreach($res as $k=>$v)
		{
			$res[$k]['add_time'] = date('Y年n月d日',$v['add_time']);
			$res[$k]['first_pic'] = parent::get_pic_html_first($v['content']);
		    $res[$k]['content'] = mb_substr(parent::charFormat($v['content']),0,40)."...";

		 
		}
		$arr['resNum'] = $resNum;
		$arr['res'] = $res;
		return $arr;
		
	}
	
	static function getInfo($info_id)
	{
		$sql = "select title, content, add_time from ".$GLOBALS['Base']->table('info')." ".
				"where info_id=$info_id ".
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

$info_id = isset($_REQUEST['info_id']) ? intval($_REQUEST['info_id']): 0;

/* 文章列表 */
if($info_id==0)
{
   $pageNow = isset($_REQUEST['page']) ? intval($_REQUEST['page']): 1;
   $pageNum = 10;
   $infolist = Info::getInfoList($pageNow,$pageNum);
   $Common->set_page($pageNum,$pageNow,$infolist['resNum'],$href = 'info.html?page=');
   $smarty->assign('infolist',$infolist['res']);;
}
/* 具体文章 */
else
{ 
   $row = Info::getInfo($info_id);  
   $smarty->assign('row',$row);
}

$smarty->assign('info_id',$info_id);
$smarty->display('info.html');

?>