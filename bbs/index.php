<?php
require(dirname(__FILE__)."/global.php");

if ( !isBrowserAllowed() )
{
	$template = template("unsupported_browser.html");

	$template->assign( 'PHPSayConfig', $PHPSayConfig );

	$template->output();
}
else
{ 
  
	if ( $loginInfo['uid'] < 1 )
	{
		$template = template( $isMobileRequest ? "mobile_login.html" : "login.html" );

		$template->assign( 'PHPSayConfig', $PHPSayConfig );

		$connectArray = isConnect($PHPSayConfig['ppsecure']);

		$template->assign( 'connectArray', $connectArray );

        $DB = database();
		
		$template->assign( 'membersList', PHPSay::getRandMembers($DB) );

		$DB->close();

		$template->output();
	}
	else
	{  
		$currentCid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

        $DB = database();

		$clubList = PHPSay::getClubList($DB,$currentCid);

		if( $currentCid > 0 && $clubList['current']['cid'] == 0 )
		{
			header("location:./");
		}
		else
		{
			$template = template("mobile_index.html"); //template( $isMobileRequest ? "mobile_index.html" : "index.html" );
			
			$template->assign( 'PHPSayConfig', $PHPSayConfig );

			$template->assign( 'loginInfo', $loginInfo );

			$template->assign( 'notificationNumber', PHPSay::getUnreadNotificationNumber($DB,$loginInfo['uid']) );

			$template->assign( 'clubList', $clubList );

			if( $clubList["current"]["cid"] > 0 )
			{
				$template->assign( 'topicList', PHPSay::getTopic($DB,"club",$clubList["current"]["cid"],$currentPage,30) );
			}
			else
			{
				$template->assign( 'topicList', PHPSay::getTopic($DB,"home",$clubList['list'],$currentPage,30) );
			}

			$template->assign( 'headerNavi', ($clubList['current']['cid'] > 0) ? "" : "home" );

			$template->assign( 'favoriteNumber', PHPSay::getUserFavoriteNumber($DB,$loginInfo['uid']) );

			$template->assign( 'sponsorList', json_decode(file_get_contents("./_cache/sponsor.json"),true) );

			$template->output();
		}

		$DB->close();
	}
}
?>