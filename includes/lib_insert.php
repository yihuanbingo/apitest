<?php
/*
 * 无缓存函数
 * author yuanjiang @2.16.2013
*/
if(!defined('IN_BS'))
{
  die('hacking attempt');
}

/* 当前用户的报修申请数 */
function insert_get_askrepair_mine()
{
   $sql = "select ask_id from ".$GLOBALS['Base']->table('askrepair')." where community_id=".$_SESSION['wx']['community_id']."  and ".
          "user_id=".$_SESSION['wx']['user_id']." order by ask_id desc ";
   $num = $GLOBALS['Mysql']->getCount($sql);
   echo $num;
} 

/* 当前用户的报修申请数 */
function insert_get_advice_mine()
{
   $sql = "select advice_id from ".$GLOBALS['Base']->table('advice')." where community_id=".$_SESSION['wx']['community_id']." and ".
          "user_id=".$_SESSION['wx']['user_id']." order by advice_id desc ";
   $num = $GLOBALS['Mysql']->getCount($sql);
   echo $num;
} 

?>