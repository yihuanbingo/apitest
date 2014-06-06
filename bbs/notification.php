<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] < 1 )
{
	header("location:./");
}
else
{
	$notifyStatus = ( isset($_GET['status']) && ($_GET['status'] == "0" || $_GET['status'] == "1") ) ? $_GET['status'] : "";

	$template = template( $isMobileRequest ? "mobile_notification.html" : "notification.html" );

	$template->assign( 'PHPSayConfig', $PHPSayConfig );

	$template->assign( 'loginInfo', $loginInfo );

	$DB = database();

	$notificationList = PHPSay::getNotification($DB,$loginInfo['uid'],$notifyStatus,$currentPage,30);

	$template->assign( 'notificationList', $notificationList );

	$template->assign( 'headerNavi', "at" );

	if( $notifyStatus == "0" )
	{
		$template->assign( 'notificationNumber', $notificationList['count'] );
	}
	else
	{
		$template->assign( 'notificationNumber', PHPSay::getUnreadNotificationNumber($DB,$loginInfo['uid']) );
	}

	$template->assign( 'favoriteNumber', PHPSay::getUserFavoriteNumber($DB,$loginInfo['uid']) );

	$template->assign( 'notifyStatus', $notifyStatus );

	$DB->close();

	$template->output();
}
?>