<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] < 1 )
{
	header("location:./");
}
else
{
	if( !isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] < 1 )
	{
		header("location:./");
	}
	else
	{
		$DB = database();

		$topicInfo = PHPSay::getOneTopic($DB,$_GET['id']);

		if( $topicInfo['tid'] < 1 )
		{
			header("location:./");
		}
		else
		{
			$clubList = PHPSay::getClubList($DB,$topicInfo['cid']);

			if( $topicInfo['cid'] > 0 && $clubList['current']['cid'] == 0 )
			{
				header("location:./");
			}
			else
			{
				$template =  template("mobile_t.html"); //template( $isMobileRequest ? "mobile_t.html" : "t.html" );

				$template->assign( 'PHPSayConfig', $PHPSayConfig );

				$template->assign( 'loginInfo', $loginInfo );

				$template->assign( 'headerNavi', "" );

				$template->assign( 'notificationNumber', PHPSay::getUnreadNotificationNumber($DB,$loginInfo['uid']) );

				$template->assign( 'favoriteNumber', PHPSay::getUserFavoriteNumber($DB,$loginInfo['uid']) );

				$template->assign( 'clubList', $clubList );

				$template->assign( 'topicInfo', $topicInfo );

				$template->assign( 'favoriteId', PHPSay::getUserFavoriteId($DB,"tid",$topicInfo['tid'],$loginInfo['uid']) );

				$template->assign( 'replyList', PHPSay::getReply($DB,"tid",$topicInfo["tid"],$currentPage,100) );

				$template->output();
			}
		}

		$DB->close();
	}
}
?>