<?php
/*
 * 定义全局变量
*/
if(!defined('IN_BS')) 
{
 die('hacking attempt');
}

define('ROOT_PATH',                     './');   // 图片上传根目录

define('API_HOST',                     'http://api.bangsoon.cn/');        //客户端响应服务器
define('WWW_HOST',                     'http://property.bangsoon.cn/');         //PC端响应服务器

$smarty->assign('api_host',API_HOST);

?>