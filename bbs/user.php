<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] < 1 )
{
	header("location:./");
}
else
{
	$postType = ( isset($_GET['list']) && ( $_GET['list'] == "reply" || $_GET['list'] == "favorite" || $_GET['list'] == "bigheadimg" ) ) ? $_GET['list'] : "topic";

	$memberId = ( isset($_GET['id']) && $postType != "favorite" ) ? $_GET['id'] : $loginInfo['uid'];

	if( !is_numeric($memberId) && checkNickname($memberId) != "" )
	{
		header("location:./user.php");
	}
	else
	{
		$DB = database();

		$userInfo = PHPSay::getMemberInfo( $DB, is_numeric($memberId) ? "uid" : "nickname", $memberId );

		if( $userInfo["uid"] < 1 )
		{
			header("location:./");
		}
		else
		{
			$template = template( $isMobileRequest ? "mobile_user.html" : "user.html" );
			
			$template->assign( 'PHPSayConfig', $PHPSayConfig );

			$template->assign( 'loginInfo', $loginInfo );

			$template->assign( 'headerNavi', ($memberId == $loginInfo['uid']) ? "me" : "" );
			
			$template->assign( 'userInfo', $userInfo );

			$template->assign( 'postType', $postType );

			switch ($postType)
			{
				case "reply":
					$template->assign( 'postList', PHPSay::getReply($DB,"uid",$userInfo["uid"],$currentPage,30) );
					break;
				case "favorite":
					$template->assign( 'postList', PHPSay::getUserFavorite($DB,$loginInfo["uid"],$currentPage,30) );
					break;
				case "bigheadimg":
				    $headimg = $DB->fetch_one("select headimgurl from phpsay_member where uid = $memberId ");
					$bigheadimg = empty($headimg) ? '' : $headimg.'/0' ;
					$template->assign('bigheadimg', $bigheadimg);
					break;	
				default:
					$template->assign( 'postList', PHPSay::getTopic($DB,"user",$userInfo["uid"],$currentPage,30) );
			}

			$template->output();
		}

		$DB->close();
	}
}
?>