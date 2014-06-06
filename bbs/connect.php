<?php
require(dirname(__FILE__)."/global.php");

if ( $loginInfo['uid'] == 0 )
{
	if ( isset($_GET['do']) )
	{
		if ( $_GET['do'] == "QQLogin" )
		{
			$qc = loginWithQQ($PHPSayConfig['qqconnect']['appid'],$PHPSayConfig['qqconnect']['appkey']);

			$qc->qq_login();
		}
		else if ( $_GET['do'] == "QQCallback" )
		{
			$openInfo = callbackWithQQ($PHPSayConfig['qqconnect']['appid'],$PHPSayConfig['qqconnect']['appkey']);

			if( $openInfo['openid'] != "" )
			{
				$DB = database();

				$userArr = PHPSay::getMemberInfo($DB,strtolower($openInfo['connect'])."id",strAddslashes($openInfo['openid']));

				$DB->close();

				if( empty($userArr['uid']) )
				{
					connectCookie($PHPSayConfig['ppsecure'],$openInfo);

					header("location:./?connect=".$openInfo['connect']);
				}
				else
				{
					loginCookie($PHPSayConfig['ppsecure'],$userArr['uid'],$userArr['nickname'],$userArr['groupid']);

					header("location:./");
				}
			}
			else
			{
				header("location:./?connect=error");
			}
		}
		else if ( $_GET['do'] == "Join" )
		{
			$connectInfo = isConnect($PHPSayConfig['ppsecure']);

			$joinReturn = array("status"=>"error","response"=>"");

			if ( $connectInfo['connect'] == "" )
			{
				$joinReturn['response'] = "出现异常，请刷新页面";
			}
			else
			{
				if( isset($_POST['nickname']) )
				{
					$nickname = filterCode($_POST['nickname'],true);

					$nicknameError = checkNickname($nickname);

					if ($nicknameError != "")
					{
						$joinReturn['response'] = $nicknameError;
					}
					else
					{
						$DB = database();

						if( PHPSay::getMemberCount($DB,"qqid",strAddslashes($connectInfo['openid'])) != 0 )
						{
							$joinReturn['response'] = "连接重复，请刷新页面";
						}
						else
						{
							if( PHPSay::getMemberCount($DB,"nickname",$nickname) != 0 )
							{
								$joinReturn['response'] = "昵称已被占用，换一个吧 ^_^";
							}
							else
							{
								$userID = PHPSay::memberJoin($DB,$nickname,"","",$connectInfo['openid']);

								if ($userID > 0)
								{
									newAvatar($userID,$connectInfo['avatar']);

									loginCookie($PHPSayConfig['ppsecure'],$userID,$nickname,1);
									
									$joinReturn["status"] = "success";
								}						
							}
						}

						$DB->close();
					}
				}				
			}

			echo json_encode($joinReturn);
		}
	}
}
else
{
	singOut();
	
	header("location:./");
}
?>