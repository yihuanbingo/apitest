<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] < 1 )
{
	header("location:./");
}
else
{
	$settingType  = ( isset($_GET['with']) && in_array($_GET['with'], array("email","password")) ) ? $_GET['with'] : "avatar";

	$DB = database();

	$userInfo = PHPSay::getMemberInfo($DB,"uid",$loginInfo['uid']);

	$DB->close();

	$template = template('mobile_settings.html'); //template( $isMobileRequest ? "mobile_settings.html" : "settings.html" );
	
	$template->assign( 'PHPSayConfig', $PHPSayConfig );

	$template->assign( 'loginInfo', $loginInfo );

	$template->assign( 'headerNavi', "settings" );

	$template->assign( 'settingType', $settingType );
	
	$template->assign( 'userInfo', $userInfo );

	$template->output();
}
?>