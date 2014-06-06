<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['group'] < 3 )
{
	header("location:./");
}
else
{
	if( isset($_POST['deleteId']) )
	{
		$sponsorArray = array();

		$sponsorList = json_decode(file_get_contents("./_cache/sponsor.json"),true);

		for( $i=0,$len=count($sponsorList); $i<$len; $i++ )
		{
			if( $i == $_POST['deleteId'] )
			{
				continue;
			}

			$sponsorArray[] = $sponsorList[$i];
		}

		echo file_put_contents("./_cache/sponsor.json", json_encode($sponsorArray));

		exit;
	}

	if( isset($_POST['linkText'],$_POST['linkURL']) )
	{
		$linkText = stripslashes(trim($_POST['linkText']));

		$linkURL = stripslashes(trim($_POST['linkURL']));

		if( $linkText != "" && substr($linkURL,0,4) == "http")
		{
			$sponsorArray = array();

			$sponsorArray[] = array( "url" => $linkURL, "target" => "_blank", "text" => $linkText );

			$sponsorList = json_decode(file_get_contents("./_cache/sponsor.json"),true);

			foreach( $sponsorList as $sponsor )
			{
				$sponsorArray[] = $sponsor;
			}

			echo file_put_contents("./_cache/sponsor.json", json_encode($sponsorArray));
		}

		exit;
	}

	$template = template( "admin_sponsor.html" );

	$template->assign( 'PHPSayConfig', $PHPSayConfig );

	$template->assign( 'loginInfo', $loginInfo );

	$template->assign( 'headerNavi', "" );

	$template->assign( 'adminNavi', "sponsor" );

	$template->assign( 'sponsorList', json_decode(file_get_contents("./_cache/sponsor.json"),true) );

	$template->output();
}
?>