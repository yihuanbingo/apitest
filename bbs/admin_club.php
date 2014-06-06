<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['group'] < 3 )
{
	header("location:./");
}
else
{
	if( isset($_POST['trashId']) )
	{
		$DB = database();

		echo PHPSay::trashClub($DB, $_POST['trashId']);

		$DB->close();

		exit;
	}

	if( isset($_POST['clubOrder']) )
	{
		$clubIds = explode(",",$_POST['clubOrder']);

		if( count($clubIds) > 1 )
		{
			$DB = database();

			echo PHPSay::clubOrder($DB, $clubIds);

			$DB->close();
		}

		exit;
	}
	
	if( isset($_POST['clubId'],$_POST['clubName']) )
	{
		$clubId = $_POST['clubId'];

		$clubName = stripslashes(trim($_POST['clubName']));

		if( getStrlen($clubName) < 1 || getStrlen($clubName) > 16 || !preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\s]+$/u', $clubName) )
		{
			die('{"result":"error","message":"名称不合法"}');
		}

		$DB = database();

		if( PHPSay::checkExists($DB,"clubname",$clubName) > 0 )
		{
			echo '{"result":"error","message":"名称已存在"}';
		}
		else
		{
			if( PHPSay::editClub($DB,$clubId,$clubName) > 0 )
			{
				echo '{"result":"success","message":""}';
			}
			else
			{
				echo '{"result":"error","message":"拒绝请求"}';
			}
		}

		$DB->close();

		exit;
	}

	if( isset($_GET['type']) && $_GET['type'] == "trash" )
	{
		$clubType = 0;
	}
	else
	{
		$clubType = 1;
	}

	$DB = database();

	$clubList = PHPSay::getAdminClub($DB, $clubType);

	$DB->close();

	$template = template( "admin_club.html" );

	$template->assign( 'PHPSayConfig', $PHPSayConfig );

	$template->assign( 'loginInfo', $loginInfo );

	$template->assign( 'headerNavi', "" );

	$template->assign( 'adminNavi', "club" );

	$template->assign( 'clubType', $clubType );

	$template->assign( 'clubList', $clubList );

	$template->output();
}
?>