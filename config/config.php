<?php
/*
 * smarty配置信息
 * smarty 3.1.7
 * author yuanjiang @2.16.2013 
*/
if(!defined('IN_BS'))
{
  die('hacking attempt');
} 
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT']); 
define('WEB_PATH','/');
require_once SERVER_PATH.WEB_PATH.'includes/Smarty.class.php';
$smarty=new Smarty;
$smarty->joined_template_dir=SERVER_PATH.WEB_PATH.'templates';
$smarty->compile_dir=SERVER_PATH.WEB_PATH.'templates_c';
$smarty->config_dir=SERVER_PATH.WEB_PATH.'config';
$smarty->caching = false ;   // 禁用缓存
$smarty->caching_dir = SERVER_PATH.WEB_PATH.'temp';
$smarty->left_delimiter='{';
$smarty->right_delimiter='}';

/*设置时区 东八区*/
date_default_timezone_set('Asia/Chongqing');

/*禁用php报告，程序存在相当数量的未定义变量，这是程序的一个潜在bug*/
error_reporting(0);

/* 数据库设置 */
$host = '112.124.119.178';
$db_user = 'root';
$db_pass = '20140303Dyw';
$charset = 'utf8';  //编码
$db_name = 'bangsoon';  
$prefix = 'bs_';    //表前缀

?>