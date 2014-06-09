<?php
require(dirname(__FILE__)."/global.php");

if( isset($_POST['COOKIE']) )
{
	$loginInfo = isLogin($PHPSayConfig['ppsecure'],json_decode(urldecode($_POST['COOKIE']),true));
}

if ( $loginInfo['uid'] >= 1 )
{
	if ( isset($_POST['do']) )
	{
		if($_POST['do'] == "addTopic")
		{   
			if( isset($_POST['cid'],$_POST['message']) )
			{  
				$postArray = array( "community_id" => $_SESSION['bbs']['community_id'], 
									"uid"		=> $loginInfo['uid'],
									"nickname"	=> $loginInfo['nickname'],
									"cid"		=> $_POST['cid'],
									"clubname"	=> "",
									"message"	=> filterCode($_POST['message']),
									"picture"	=> "",
									"posttime"	=> time(),
									"lasttime"	=> time() );
    
				if( isset($_FILES['picture']) && !empty($_FILES['picture']['name']) )
				{
					if( $_FILES['picture']['size'] > 2097152 )
					{
						die('{"result":"error","message":"图片太大"}');
					}

					$imgInfo = @getimagesize($_FILES['picture']['tmp_name']);

					if( !$imgInfo || !in_array($imgInfo[2], array(1,2,3)) )
					{
						die('{"result":"error","message":"您上传的图片文件不合法"}');
					}

					if( $imgInfo[0] < 100 || $imgInfo[1] < 100 )
					{
						die('{"result":"error","message":"图片尺寸不能小于 100 x 100"}');
					}

					$savePath = date('Y/n/j');

					mkDirs("./pictures/".$savePath);

					$imageName = $savePath."/".date('His')."_".$loginInfo['uid']."_".rand(1,32000);
					
					switch($imgInfo[2])
					{
						case 1:
							$imageName .= ".gif";
							break;
						case 2:
							$imageName .= ".jpg";
							break;
						case 3:
							$imageName .= ".png";
						break;
					}

					$saveImage = "./pictures/".$imageName;

					if( @move_uploaded_file($_FILES['picture']['tmp_name'], substr($saveImage,0,-4)."_b".substr($saveImage,-4)) )
					{
						if( $imgInfo[0] > 200 || $imgInfo[1] > 200 )
						{
							createImg(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_s".substr($saveImage,-4),$imgInfo,200,200);
						}
						else
						{
							copy(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_s".substr($saveImage,-4));
						}

						if( $imgInfo[0] > 1000 || $imgInfo[1] > 1000 )
						{
							createImg(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_b".substr($saveImage,-4),$imgInfo,1000,1000);
						}

						$postArray["picture"] = $imageName;
					}
					else
					{
						die('{"result":"error","message":"图片上传失败"}');
					}
				}
 
				if( (empty($postArray["message"]) && empty($postArray["picture"])) || getStrlen($postArray["message"]) > 200 )
				{
					deletePicture($postArray["picture"]);
					
					die('{"result":"error","message":"内容长度不合法"}');
				}

				$DB = database();

				$postArray["clubname"] = PHPSay::checkExists($DB,"club",$postArray["cid"]);

				if( $postArray["clubname"] == "" || PHPSay::checkExists($DB,"member",$postArray["uid"]) < 1 )
				{
					deletePicture($postArray["picture"]);
					
					echo '{"result":"error","message":"请求被拒绝"}';
				}
				else
				{
					$postResult = PHPSay::postTopic($DB,$postArray);

					if( isset($postResult['tid']) && $postResult['tid'] > 0 )
					{
						if( $isMobileRequest )
						{
							echo '{"result":"success","message":""}';
						}
						else
						{					
							$template = template("_stream_item.html");

							$template->assign( 'loginInfo', $loginInfo );

							$template->assign( 'topicList', array("list"=>$postResult) );

							echo json_encode(array("result"=>"success","message"=>$template->result()));
						}
					}
					else
					{
						deletePicture($postArray["picture"]);

						echo '{"result":"error","message":"发布失败"}';
					}
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "deleteTopic")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{
				$deletePics = array();

				$DB = database();

				$postInfo = $DB->fetch_one_array("SELECT `tid`,`picture` FROM `phpsay_topic` WHERE `tid`='".$_POST['tid']."'");

				if( !empty($postInfo['picture']) )
				{
					array_push($deletePics, $postInfo["picture"]);
				}

				if( !empty($postInfo['tid']) )
				{
					$DB->query("DELETE FROM `phpsay_topic` WHERE `tid`=".$postInfo['tid']);

					$Result = $DB->query("SELECT `picture` FROM `phpsay_reply` WHERE `tid`=".$postInfo['tid']);

					while($Re = $DB->fetch_array($Result))
					{
						if( !empty($Re['picture']) )
						{
							array_push($deletePics, $Re["picture"]);
						}
					}

					$DB->query("DELETE FROM `phpsay_reply` WHERE `tid`=".$postInfo['tid']);

					$DB->query("DELETE FROM `phpsay_favorite` WHERE `tid`=".$postInfo['tid']);

					$DB->query("DELETE FROM `phpsay_notification` WHERE `tid`=".$postInfo['tid']);
				}

				$DB->close();

				foreach ($deletePics as $deletePic)
				{
					deletePicture($deletePic);
				}

				echo '{"result":"success"}';
			}
		}
		else if($_POST['do'] == "stickTopic")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{ 
				$DB = database();
				$DB->query("UPDATE `phpsay_topic` SET `stick`=1 WHERE `tid`=".$_POST['tid']);
				$DB->close();
				echo '{"result":"success"}';
			}
		}
		else if($_POST['do'] == "nostickTopic")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{ 
				$DB = database();
				$DB->query("UPDATE `phpsay_topic` SET `stick`=0 WHERE `tid`=".$_POST['tid']);
				$DB->close();
				echo '{"result":"success"}';
			}
		}
		if($_POST['do'] == "replyTopic")
		{
			if( isset($_POST['tid'],$_POST['message']) && is_numeric($_POST['tid']) )
			{
				$postArray = array( "tid"		=> $_POST['tid'],
									"uid"		=> $loginInfo['uid'],
									"nickname"	=> $loginInfo['nickname'],
									"message"	=> filterCode($_POST['message']),
									"picture"	=> "",
									"posttime"	=> time() );

				if( isset($_FILES['picture']) && !empty($_FILES['picture']['name']) )
				{
					if( $_FILES['picture']['size'] > 2097152 )
					{
						die('{"result":"error","message":"图片太大"}');
					}

					$imgInfo = @getimagesize($_FILES['picture']['tmp_name']);

					if( !$imgInfo || !in_array($imgInfo[2], array(1,2,3)) )
					{
						die('{"result":"error","message":"您上传的图片文件不合法"}');
					}

					if( $imgInfo[0] < 100 || $imgInfo[1] < 100 )
					{
						die('{"result":"error","message":"图片尺寸不能小于 100 x 100"}');
					}

					$savePath = date('Y/n/j');

					mkDirs("./pictures/".$savePath);

					$imageName = $savePath."/".date('His')."_".$loginInfo['uid']."_".rand(1,32000);

					switch($imgInfo[2])
					{
						case 1:
							$imageName .= ".gif";
							break;
						case 2:
							$imageName .= ".jpg";
							break;
						case 3:
							$imageName .= ".png";
						break;
					}
					
					$saveImage = "./pictures/".$imageName;

					if( @move_uploaded_file($_FILES['picture']['tmp_name'], substr($saveImage,0,-4)."_b".substr($saveImage,-4)) )
					{
						if( $imgInfo[0] > 200 || $imgInfo[1] > 200 )
						{
							createImg(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_s".substr($saveImage,-4),$imgInfo,200,200);
						}
						else
						{
							copy(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_s".substr($saveImage,-4));
						}

						if( $imgInfo[0] > 1000 || $imgInfo[1] > 1000 )
						{
							createImg(substr($saveImage,0,-4)."_b".substr($saveImage,-4),substr($saveImage,0,-4)."_b".substr($saveImage,-4),$imgInfo,1000,1000);
						}

						$postArray["picture"] = $imageName;
					}
					else
					{
						die('{"result":"error","message":"图片上传失败"}');
					}
				}

				if( (empty($postArray["message"]) && empty($postArray["picture"])) || getStrlen($postArray["message"]) > 200 )
				{
					deletePicture($postArray["picture"]);
					
					die('{"result":"error","message":"内容长度不合法"}');
				}

				$DB = database();

				$topicTime = PHPSay::checkExists($DB,"topic_time",$postArray["tid"]);

				if( $topicTime == "" || PHPSay::checkExists($DB,"member",$postArray["uid"]) < 1 )
				{
					deletePicture($postArray["picture"]);
					
					echo '{"result":"error","message":"请求被拒绝"}';
				}
				else
				{
					$postResult = PHPSay::replyTopic( $DB, $postArray, ($topicTime > 0) ? true : false );

					if( isset($postResult['pid']) && $postResult['pid'] > 0 )
					{
						if( $isMobileRequest )
						{
							echo '{"result":"success","message":""}';
						}
						else
						{
							$template = template("_reply_item.html");

							$template->assign( 'loginInfo', $loginInfo );

							$template->assign( 'replyList', array("list"=>$postResult) );

							echo json_encode(array("result"=>"success","message"=>$template->result()));
						}
					}
					else
					{
						deletePicture($postArray["picture"]);

						echo '{"result":"error","message":"回复失败"}';
					}
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "deleteReply")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['pid']) && is_numeric($_POST['pid']) )
			{
				$DB = database();

				$deleteResult = PHPSay::deleteReply($DB,$_POST['pid']);

				$DB->close();

				deletePicture($deleteResult["picture"]);

				echo '{"result":"success"}';
			}
		}
		else if($_POST['do'] == "userGroup")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['uid']) && is_numeric($_POST['uid']) )
			{
				if( $loginInfo['uid'] == $_POST['uid'] )
				{
					die('{"result":"error"}');
				}

				$DB = database();

				$userGroup = PHPSay::checkExists($DB,"member",$_POST["uid"]);

				if( $userGroup == 0 )
				{
					$DB->query("UPDATE `phpsay_member` SET `groupid`=1 WHERE `uid`='".$_POST['uid']."'");

					echo '{"result":"success","message":1}';
				}
				else if( $userGroup == 1 )
				{
					$DB->query("UPDATE `phpsay_member` SET `groupid`=0 WHERE `uid`='".$_POST['uid']."'");

					echo '{"result":"success","message":0}';
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "topicStatus")
		{
			if ( $loginInfo['group'] > 1 && isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{
				$DB = database();

				$lastPostTime = PHPSay::checkExists($DB,"topic",$_POST["tid"]);

				if( $lastPostTime != "" )
				{
					if( $lastPostTime == 0 )
					{
						$lastTime = PHPSay::getTopicLastTime($DB,$_POST['tid']);

						if( !empty($lastTime) )
						{
							$DB->query("UPDATE `phpsay_topic` SET `lasttime`=".$lastTime." WHERE `tid`='".$_POST['tid']."'");

							echo '{"result":"success","message":1}';
						}
						else
						{
							echo '{"result":"error"}';
						}
					}
					else
					{
						$DB->query("UPDATE `phpsay_topic` SET `lasttime`=0 WHERE `tid`='".$_POST['tid']."'");

						echo '{"result":"success","message":0}';
					}
				}
				else
				{
					echo '{"result":"error"}';
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "deleteNotification")
		{
			if ( isset($_POST['nid']) && is_numeric($_POST['nid']) )
			{
				$DB = database();

				$deleteNumber = $DB->affected_rows("DELETE FROM `phpsay_notification` WHERE `nid`='".$_POST['nid']."' AND `atuid`=".$loginInfo['uid']);

				$DB->close();

				if( $deleteNumber == 1 )
				{
					echo '{"result":"success"}';
				}
				else
				{
					echo '{"result":"error"}';
				}	
			}
		}
		else if($_POST['do'] == "favTopic")
		{
			if ( isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{
				$DB = database();

				$favId = PHPSay::addUserFavorite($DB,"tid",$_POST['tid'],$loginInfo['uid']);

				$DB->close();

				if( $favId != 0 )
				{
					echo '{"result":"success","message":'.$favId.'}';
				}
				else
				{
					echo '{"result":"error"}';
				}
			}
		}
		else if($_POST['do'] == "unFavTopic")
		{
			if ( isset($_POST['tid']) && is_numeric($_POST['tid']) )
			{
				$DB = database();

				if( $DB->affected_rows("DELETE FROM `phpsay_favorite` WHERE `pid`=0 AND `tid`='".$_POST['tid']."' AND `fuid`=".$loginInfo['uid']) == 1 )
				{
					echo '{"result":"success"}';
				}
				else
				{
					echo '{"result":"error"}';
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "favReply")
		{
			if ( isset($_POST['pid']) && is_numeric($_POST['pid']) )
			{
				$DB = database();

				$favId = PHPSay::addUserFavorite($DB,"pid",$_POST['pid'],$loginInfo['uid']);

				$DB->close();

				if( $favId != 0 )
				{
					echo '{"result":"success","message":'.$favId.'}';
				}
				else
				{
					echo '{"result":"error"}';
				}
			}
		}
		else if($_POST['do'] == "unFavReply")
		{
			if ( isset($_POST['pid']) && is_numeric($_POST['pid']) )
			{
				$DB = database();

				if( $DB->affected_rows("DELETE FROM `phpsay_favorite` WHERE `pid`='".$_POST['pid']."' AND `fuid`=".$loginInfo['uid']) == 1 )
				{
					echo '{"result":"success"}';
				}
				else
				{
					echo '{"result":"error"}';
				}

				$DB->close();
			}
		}
		else if($_POST['do'] == "settingAvatar")
		{
			if( isset($_FILES['avatar']) && !empty($_FILES['avatar']['name']) )
			{
				if( $_FILES['avatar']['size'] > 1048576 )
				{
					if ( $isMobileRequest )
					{
						setcookie("upload_avatar_result","图片不能超过1MB");

						header("location:./settings.php");

						exit;
					}
									
					die('{"result":"error","message":"图片不能超过1MB"}');
				}

				$imgInfo = @getimagesize($_FILES['avatar']['tmp_name']);

				if( !$imgInfo || !in_array($imgInfo[2], array(1,2,3)) )
				{
					if ( $isMobileRequest )
					{
						setcookie("upload_avatar_result","您上传的图片文件不合法");

						header("location:./settings.php");

						exit;
					}
									
					die('{"result":"error","message":"您上传的图片文件不合法"}');
				}

				if( $imgInfo[0] < 100 || $imgInfo[1] < 100 )
				{
					if ( $isMobileRequest )
					{
						setcookie("upload_avatar_result","图片尺寸不能小于 100 x 100");

						header("location:./settings.php");

						exit;
					}
									
					die('{"result":"error","message":"图片尺寸不能小于 100 x 100"}');
				}

				mkDirs(avatarPath($loginInfo['uid']));

				$avatarFile = getAvatarURL($loginInfo['uid'],100);

				if( @move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarFile) )
				{
					if( $imgInfo[2] != 2 || $imgInfo[0] != 100 || $imgInfo[1] != 100 )
					{
						createAvatar($avatarFile,$imgInfo,100,$avatarFile);
					}

					if ( $isMobileRequest )
					{
						setcookie("upload_avatar_result","SUCCESS");

						header("location:./settings.php");

						exit;
					}
					
					die('{"result":"success","message":""}');
				}
				else
				{
					if ( $isMobileRequest )
					{
						setcookie("upload_avatar_result","图片上传失败");

						header("location:./settings.php");

						exit;
					}
									
					die('{"result":"error","message":"图片上传失败"}');
				}
			}
		}
		else if($_POST['do']=='refreshAvatar')
		{
		   $Wx = new Wx;
		   $DB = database();
	       if($Wx->refreshAvatar($DB, $loginInfo['uid']))
		   {
		       die('{"result":"success","message":"刷新成功"}');
		   }
		   else
		   {
			die('{"result":"error","message":"系统错误，请求失败"}');
		   }
		}
		else if($_POST['do'] == "settingEmail")
		{
			if( isset($_POST['password'],$_POST['email']) )
			{
				$currentPasswd = stripslashes($_POST['password']);

				$newEmail = trim($_POST['email']);

				if( strlen($currentPasswd) < 6 || strlen($currentPasswd) > 26 || substr_count($currentPasswd," ") > 0 )
				{
					die('{"result":"error","message":"当前密码无效","position":1}');
				}

				if( !emailCheck($newEmail) )
				{
					die('{"result":"error","message":"邮件地址不合法","position":2}');
				}

				$DB = database();

				$userInfo = $DB->fetch_one_array("SELECT `email`,`password` FROM `phpsay_member` WHERE `uid`=".$loginInfo['uid']);

				if( $userInfo['password'] != md5($currentPasswd) )
				{
					echo '{"result":"error","message":"当前密码不正确","position":1}';
				}
				else
				{
					if( $userInfo['email'] == $newEmail )
					{
						echo '{"result":"error","message":"邮箱未修改","position":2}';
					}
					else
					{
						if( $DB->fetch_one("SELECT COUNT(`uid`) FROM `phpsay_member` WHERE `email`='".$newEmail."'") > 0 )
						{
							echo '{"result":"error","message":"该邮件地址已被其它账号占用","position":2}';
						}
						else
						{
							$DB->query("UPDATE `phpsay_member` SET email='".$newEmail."' WHERE `uid`=".$loginInfo['uid']);

							echo '{"result":"success","message":""}';
						}
					}
				}

				$DB->close();			
			}
		}
		else if($_POST['do'] == "settingPassword")
		{
			if( isset($_POST['currentPasswd'],$_POST['userPasswd']) )
			{
				$currentPasswd = stripslashes($_POST['currentPasswd']);

				$userPasswd = stripslashes($_POST['userPasswd']);

				if( substr_count($currentPasswd," ") > 0 || substr_count($userPasswd," ") > 0 )
				{
					die('{"result":"error","message":"密码不能使用空格","position":2}');
				}

				if( strlen($userPasswd) < 6 )
				{
					die('{"result":"error","message":"密码长度不能少于6位","position":2}');
				}

				if( strlen($currentPasswd) > 26 || strlen($userPasswd) > 26 )
				{
					die('{"result":"error","message":"密码长度不能超出26位","position":2}');
				}

				$DB = database();

				$currentPassword = $DB->fetch_one("SELECT `password` FROM `phpsay_member` WHERE `uid`=".$loginInfo['uid']);

				if( $currentPassword != "" && md5($currentPasswd) != $currentPassword )
				{
					echo '{"result":"error","message":"当前密码不正确","position":1}';
				}
				else
				{
					if( $currentPassword == md5($userPasswd) )
					{
						echo '{"result":"error","message":"新密码不能与当前密码相同","position":2}';
					}
					else
					{
						$DB->query("UPDATE `phpsay_member` SET password='".md5($userPasswd)."' WHERE `uid`=".$loginInfo['uid']);

						echo '{"result":"success","message":""}';
					}
				}

				$DB->close();
			}
		}
	}
}
else
{
	if ( $isMobileRequest )
	{
		if ( isset($_POST['do']) )
		{
			if($_POST['do'] == "settingAvatar")
			{
				header("location:./");

				exit;
			}
		}
	}
	
	echo '{"result":"login"}';
}
?>