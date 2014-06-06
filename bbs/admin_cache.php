<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['group'] < 3 )
{
	header("location:./");
}
else
{
	if( isset($_POST['deleteCache']) )
	{
		$deleteNumber = 0;

		$pathName = dirname(__FILE__)."/_cache";

		if ( false != ($handle = opendir($pathName)) )
		{
			while ( false !== ($file = readdir($handle)) )
			{
				if ( substr($file,-9) == "_html.php" )
				{
					unlink($pathName."/".$file);

					$deleteNumber++;
				}
			}
		}

		echo $deleteNumber;
	}
	else
	{
		$template = template( "admin_cache.html" );

		$template->assign( 'PHPSayConfig', $PHPSayConfig );

		$template->assign( 'loginInfo', $loginInfo );

		$template->assign( 'headerNavi', "" );

		$template->assign( 'adminNavi', "cache" );

		$template->output();
	}
}
?>