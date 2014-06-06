<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] < 1 )
{
	header("location:./");
}
else
{
	$searchType = ( isset($_GET['t']) && $_GET['t'] == "reply" ) ? $_GET['t'] : "topic";

	$searchWord = isset($_GET['q']) ? filterCode($_GET['q']) : "";

	if( strlen($searchWord) < "2" || getStrlen($searchWord) > "15" )
	{
		header("location:./");
	}
	else
	{
		$template = template( "search.html" );

		$template->assign( 'PHPSayConfig', $PHPSayConfig );

		$template->assign( 'loginInfo', $loginInfo );

		$template->assign( 'headerNavi', "" );

		$template->assign( 'searchType', $searchType );

		$template->assign( 'searchWord', stripslashes($searchWord) );

		$DB = database();

		$template->assign( 'searchList', PHPSay::searchPost($DB,$searchType,$searchWord,$currentPage,30) );

		$DB->close();

		$template->output();
	}
}
?>