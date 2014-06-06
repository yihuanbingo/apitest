<?php
error_reporting(E_ALL);

date_default_timezone_set('PRC');

require(dirname(__FILE__)."/config/config_PHPSay.php");

require(dirname(__FILE__)."/controller/class_Xxtea.php");

require(dirname(__FILE__)."/controller/class_PHPSay.php");

require(dirname(__FILE__)."/controller/function.php");

require(dirname(__FILE__)."/controller/class_Wx.php");

require(dirname(__FILE__)."/controller/class_MySQL.php");

require(dirname(__FILE__)."/config/config_MySQL.php");

session_start();

/* 同步微信登录 */
$openid = isset($_REQUEST['openid'])? $_REQUEST['openid']: '';
if(!empty($openid))
{ 
   $DB = database();
   $wx = new Wx;
   $wx::getWxUserInfo($DB, $openid);
   $wx::checkWxLegal($DB, $openid);
   $loginInfo = $wx->getMemberInfo($DB, $openid);
   $DB->close();
}

$loginInfo = isLogin($PHPSayConfig['ppsecure'],$_COOKIE);

$isMobileRequest = isMobileRequest();

$currentPage = ( isset($_GET['page']) && intval($_GET['page']) > 0 ) ? intval($_GET['page']) : 1;

?>