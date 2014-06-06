<?php
/*
 * 生活导航
 * author yuanjiang @2.16.2013
*/
define("IN_BS",true);

require("includes/init.php");
Transaction::checkUserCommunity($redirectUrl='lifenav.html');

class Lifenav extends Common{
   
   /*
   * 提取所有生活导航分类
   */
   public static function getLifenavCatlist()
   {
      $lifenav_cat = $GLOBALS['Mysql']->getAll("select cat_id, cat_name from ".$GLOBALS['Base']->table('lifenav_cat')." ".
	                                           "where parent_id=0 order by sort_order asc, cat_id asc ");
	  foreach($lifenav_cat as $k=>$v)
	  {
	     $lifenav_cat[$k]['sub_cat'] = $GLOBALS['Mysql']->getAll("select cat_id, cat_name from ".$GLOBALS['Base']->table('lifenav_cat')." where parent_id=".$v['cat_id']." order by sort_order asc, cat_id asc ");
	  }										   
	  return $lifenav_cat;
   }
   
   /*
   * 提取某个分类下的生活导航内容
   */
   public static function getLifenavList($cat_id, $pageNow, $pageNum)
   {
      $start = ($pageNow -1)*$pageNum;
	  $sql = "select content from ".$GLOBALS['Base']->table('lifenav')." where cat_id=$cat_id ".
	         "and community_id=".$_SESSION['wx']['community_id']." ".
			 "order by sort_order asc, lifenav_id desc ";
	  $resNum = $GLOBALS['Mysql']->getCount($sql);
	  $sql .= "limit $start, $pageNum ";
	  $res = $GLOBALS['Mysql']->getAll($sql);
	  foreach($res as $k=>$v)
	  {
	     $res[$k]['content'] = unserialize($v['content']);
	  }
	  $arr['resNum'] = $resNum;
	  $arr['res'] = $res;
	  return $arr;
   }

}

$lifenav_cat_id = isset($_REQUEST['lifenav_cat_id']) ? abs(intval($_REQUEST['lifenav_cat_id'])) : 0;

/* 显示生活导航列表按钮 */
if($lifenav_cat_id==0)
{
   $list = Lifenav::getLifenavCatlist(); 
   $smarty->assign('list',$list);
}
/* 具体内容 */
else
{ 
   $pageNow = isset($_REQUEST['page']) ? abs(intval($_REQUEST['page'])): 1 ;
   $pageNum = 10;
   $list = Lifenav::getLifenavList($lifenav_cat_id, $pageNow, $pageNum);  
   $smarty->assign('list',$list['res']);
   $Common->set_page($pageNum,$pageNow,$list['resNum'],$href = 'lifenav.html?lifenav_cat_id='.$lifenav_cat_id.'&page=');
   $lifenav_cat_name = $Mysql->getOne("select cat_name from ".$Base->table('lifenav_cat')." where cat_id=$lifenav_cat_id ");
   $smarty->assign('lifenav_cat_name',$lifenav_cat_name);
}

$smarty->assign('lifenav_cat_id',$lifenav_cat_id);
$smarty->display('lifenav.htm');

?>