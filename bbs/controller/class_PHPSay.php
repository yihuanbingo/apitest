<?php
class PHPSay
{
	public static function getMemberInfo($DB,$key,$value)
	{
		$memberArray = array( "uid"=>0, "nickname"=>"", "email"=>"", "password"=>"", "regtime"=>"", "qqid"=>"", "groupid"=>0 );

		$DBArray = $DB->fetch_one_array("SELECT * FROM `phpsay_member` WHERE `".$key."`='".$value."'");

		if( !empty($DBArray['uid']) )
		{
			$memberArray['uid']			= $DBArray['uid'];

			$memberArray['avatar']		= getAvatarURL($DB, $DBArray['uid']);
			
			$memberArray['nickname']	= $DBArray['nickname'];
			
			$memberArray['email']		= $DBArray['email'];
			
			$memberArray['password']	= $DBArray['password'];
			
			$memberArray['regtime']		= date('Y-n-j H:i',$DBArray['regtime']);
			
			$memberArray['qqid']		= $DBArray['qqid'];
			
			$memberArray['groupid']		= $DBArray['groupid'];
		}

		return $memberArray;
	}

	public static function getMemberCount($DB,$key,$value)
	{
		return $DB->fetch_one("SELECT COUNT(`uid`) FROM `phpsay_member` WHERE `".$key."`='".$value."'");
	}

	public static function memberJoin($DB,$nickname,$email,$password,$qqid,$groupid=1)
	{
		$addDBArray = array("nickname"	=> $nickname,
							"email"		=> $email,
							"password"	=> $password,
							"regtime"	=> time(),
							"qqid"		=> $qqid,
							"groupid"	=> $groupid);

		$DB->query( $DB->insert_sql("`phpsay_member`",$addDBArray) );

		return $DB->insert_id();
	}

	public static function getRandMembers($DB,$num=18)
	{
		$memberList = array();

		$Result = $DB->query("SELECT `uid`,`nickname` FROM `phpsay_member` ORDER BY `uid` DESC LIMIT ".$num);

		while($Re = $DB->fetch_array($Result))
		{
			$memberList[] = array( 
			                     "uid" => $Re['uid'], 
								 "avatar" => getAvatarURL($DB,$Re['uid']),
								 "nickname" => $Re['nickname'] 
								 );
		}

		return $memberList;
	}

	public static function getMemberList($DB,$page,$per)
	{
		$memberArr = array();

		$memberCount = $DB->fetch_one("SELECT COUNT(`uid`) FROM `phpsay_member`");

		$Result = $DB->query("SELECT * FROM `phpsay_member` ORDER BY `uid` DESC LIMIT ".($page-1)*$per.",".$per);

		while($Re = $DB->fetch_array($Result))
		{
			$memberArr[] = array(
								"uid"		=> $Re['uid'],
								"avatar"	=> getAvatarURL($DB,$Re['uid']),
								"nickname"	=> $Re['nickname'],
								"email"		=> $Re['email'],
								"password"	=> $Re['password'],
								"regtime"	=> date('Y-m-d H:i:s',$Re['regtime']),
								"qqid"		=> $Re['qqid'],
								"groupid"	=> $Re['groupid']
								);
		}

		return array("count"=>$memberCount,"list"=>$memberArr,"page"=>self::getPage($memberCount,$per,$page));
	}

	public static function getResetPasswordCode($DB,$key,$value)
	{
		$codeArr = array( "uid" => 0, "code" => "", "dateline" => 0 );

		$getArr = $DB->fetch_one_array("SELECT * FROM `phpsay_resetpassword` WHERE `".$key."`='".$value."'");

		if( $getArr )
		{
			$codeArr = array( "uid" => $getArr['uid'], "code" => $getArr['code'], "dateline" => $getArr['dateline'] );
		}

		return $codeArr;
	}

	public static function checkExists($DB,$table,$value)
	{
		if ($table == "member")
		{
			$grouId = $DB->fetch_one("SELECT `groupid` FROM `phpsay_member` WHERE `uid`='".$value."'");

			if ( empty($grouId) )
			{
				$grouId = 0;
			}

			return $grouId;
		}

		if ($table == "club")
		{
			return $DB->fetch_one("SELECT `clubname` FROM `phpsay_club` WHERE `cid`='".$value."' AND `position` > 0");
		}

		if ($table == "clubname")
		{
			return $DB->fetch_one("SELECT COUNT(`cid`) FROM `phpsay_club` WHERE `clubname`='".$value."'");
		}

		if ($table == "topic")
		{
			return $DB->fetch_one("SELECT `lasttime` FROM `phpsay_topic` WHERE `tid`='".$value."'");
		}

		if ($table == "topic_time")
		{
			$timeArray = $DB->fetch_one_array("SELECT `posttime`,`lasttime` FROM `phpsay_topic` WHERE `tid`='".$value."'");

			if( empty($timeArray['posttime']) )
			{
				return "";
			}

			if( (time() - $timeArray['posttime']) > 604800 || $timeArray['lasttime'] == 0 )
			{
				return "0";
			}

			return "1";
		}		
	}

	public static function getUnreadNotificationNumber($DB,$atuid)
	{
		return $DB->fetch_one("SELECT COUNT(`nid`) FROM `phpsay_notification` WHERE `atuid`=".$atuid." AND `isread`=0");
	}

	public static function getNotification($DB,$atuid,$isread,$page,$per)
	{
		$whereSymbol = "=";

		if( $isread == "" )
		{
			$whereSymbol = " IN(0,1)";
		}

		$Total = $DB->fetch_one("SELECT COUNT(`nid`) FROM `phpsay_notification` WHERE `atuid`=".$atuid." AND `isread`".$whereSymbol.$isread);

		$postList = array();

		if ( $Total > 0 )
		{
			$Result = $DB->query("SELECT * FROM `phpsay_notification` WHERE `atuid`=".$atuid." AND `isread`".$whereSymbol.$isread." ORDER BY `nid` DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$postList[] = array("nid"		=> $Re['nid'],
									"atuid"		=> $Re['atuid'],
									"uid"		=> $Re['uid'],
									"avatar"	=> getAvatarURL($DB,$Re['uid']),
									"nickname"	=> $Re['nickname'],
									"tid"		=> $Re['tid'],
									"pid"		=> $Re['pid'],
									"message"	=> ubbReplace(filterHTML($Re['message'])),
									"smallimg"	=> getImageURL($Re["picture"],"s"),
									"bigimage"	=> getImageURL($Re["picture"],"b"),
									"posttime"	=> countDownTime($Re["posttime"]),
									"isread"	=> $Re['isread'],
									"groupid" => $DB->fetch_one("select groupid from phpsay_member where uid = ".$Re['uid']." "),
									);

				if( $Re['isread'] == 0 )
				{
					$DB->query("UPDATE `phpsay_notification` SET `isread`=1 WHERE `nid`=".$Re['nid']);
				}
			}
		}

		return array("count"=>$Total,"list"=>$postList,"page"=>self::getPage($Total,$per,$page));
	}

	private static function writeNotification($DB,$array)
	{
		
		/*选取楼主ID*/
		$sql = "select uid from phpsay_topic where tid = ".$array['tid'];
		$t_uid = $DB->fetch_one($sql);
		if($t_uid!=$array['uid'])
		{
			$notificationArray = array( "atuid"		=> $t_uid,
					"uid"		=> $array['uid'],
					"nickname"	=> $array['nickname'],
					"tid"		=> $array['tid'],
					"pid"		=> $array['pid'],
					"message"	=> $array['message'],
					"picture"	=> $array["picture"],
					"posttime"	=> $array["posttime"] );
			
			$DB->query( $DB->insert_sql("`phpsay_notification`",$notificationArray) );
		}
			
			preg_match_all("@\@(.*?)([\s]+)@is",$array['message']." ",$nameArray);
	
			if( isset($nameArray[1]) )
			{
				$writeName = array(strtolower($array['nickname']));
	
				foreach( $nameArray[1] as $nickname )
				{
					if( in_array(strtolower($nickname), $writeName) )
					{
						continue;
					}
	
					array_push($writeName, strtolower($nickname));
	
					$atUid = $DB->fetch_one("SELECT `uid` FROM `phpsay_member` WHERE `nickname`='".$nickname."'");
	
					if( !empty($atUid) && $atUid!=$t_uid )
					{
						$notificationArray = array( "atuid"		=> $atUid,
													"uid"		=> $array['uid'],
													"nickname"	=> $array['nickname'],
													"tid"		=> $array['tid'],
													"pid"		=> $array['pid'],
													"message"	=> $array['message'],
													"picture"	=> $array["picture"],
													"posttime"	=> $array["posttime"] );
	
						$DB->query( $DB->insert_sql("`phpsay_notification`",$notificationArray) );
					}
				}
			}
		

		return true;
	}

	public static function getUserFavoriteNumber($DB,$fuid)
	{
		return $DB->fetch_one("SELECT COUNT(`fid`) FROM `phpsay_favorite` WHERE `fuid`=".$fuid);
	}

	public static function getUserFavorite($DB,$fuid,$page,$per)
	{
		$Total = self::getUserFavoriteNumber($DB,$fuid);

		$postList = array();

		if ( $Total > 0 )
		{
			$Result = $DB->query("SELECT * FROM `phpsay_favorite` WHERE `fuid`=".$fuid." ORDER BY `fid` DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$postList[] = array("fid"		=> $Re['fid'],
									"fuid"		=> $Re['fuid'],
									"uid"		=> $Re['uid'],
									"avatar"	=> getAvatarURL($DB,$Re['uid']),
									"nickname"	=> $Re['nickname'],
									"tid"		=> $Re['tid'],
									"pid"		=> $Re['pid'],
									"message"	=> ubbReplace(filterHTML($Re['message'])),
									"smallimg"	=> getImageURL($Re["picture"],"s"),
									"bigimage"	=> getImageURL($Re["picture"],"b"),
									"posttime"	=> countDownTime($Re["posttime"]));
			}
		}

		return array("count"=>$Total,"list"=>$postList,"page"=>self::getPage($Total,$per,$page));
	}

	public static function getUserFavoriteId($DB,$key,$id,$fuid)
	{
		if($key == "tid")
		{
			return $DB->fetch_one("SELECT `fid` FROM `phpsay_favorite` WHERE `pid` = 0 AND `".$key."`=".$id." AND `fuid`=".$fuid);
		}
		else if($key == "pid")
		{
			return $DB->fetch_one("SELECT `fid` FROM `phpsay_favorite` WHERE `".$key."`=".$id." AND `fuid`=".$fuid);
		}
	}

	public static function addUserFavorite($DB,$key,$id,$fuid)
	{
		$favId = self::getUserFavoriteId($DB,$key,$id,$fuid);

		if( !empty($favId) )
		{
			return -1;
		}

		if( $key == "tid" )
		{
			$postArray = $DB->fetch_one_array("SELECT * FROM `phpsay_topic` WHERE `tid`=".$id);

			if( empty($postArray['tid']) )
			{
				return 0;
			}

			$postArray['pid'] = 0;
		}
		else if( $key == "pid" )
		{
			$postArray = $DB->fetch_one_array("SELECT * FROM `phpsay_reply` WHERE `pid`=".$id);

			if( empty($postArray['pid']) )
			{
				return 0;
			}
		}

		$favArray = array(	"fuid"		=>	$fuid,
							"uid"		=>	$postArray['uid'],
							"nickname"	=>	$postArray['nickname'],
							"tid"		=>	$postArray['tid'],
							"pid"		=>	$postArray['pid'],
							"message"	=>	$postArray['message'],
							"picture"	=>	$postArray['picture'],
							"posttime"	=>	$postArray['posttime']
						);

		$DB->query( $DB->insert_sql("`phpsay_favorite`",$favArray) );

		return $DB->insert_id();
	}

	public static function getClubOne($DB,$cid)
	{
		$clubArray = array("cid"=>0,"cname"=>"");

		$DBArray = $DB->fetch_one_array("SELECT * FROM `phpsay_club` WHERE `cid`='".$cid."'");

		if( !empty($DBArray['cid']) )
		{
			$clubArray['cid']	= $DBArray['cid'];

			$clubArray['cname']	= $DBArray['cname'];
		}

		return $clubname;
	}

	public static function getClubList($DB,$currentCid)
	{
		$Result = $DB->query("SELECT * FROM `phpsay_club` WHERE `position` > 0 ORDER BY `position` ASC");

		$clubList = array("list"=>array(),"current"=>array("cid"=>0,"cname"=>""));

		$i = -1;

		while($Re = $DB->fetch_array($Result))
		{
			$i++;

			$clubList["list"][] = array("cid"	=> $Re['cid'],
										"cname"	=> $Re['clubname'],
										"css"	=> "");

			if($Re['cid'] == $currentCid)
			{
				$clubList["list"][$i]["css"] = "active";

				$clubList["current"]["cid"] = $clubList["list"][$i]["cid"];

				$clubList["current"]['cname'] = $clubList["list"][$i]["cname"];
			}
		}

		if( $i >= 0 )
		{
			$clubList["list"][0]['css'] .= " first-child";
		}

		if( $i > 0 )
		{
			$clubList["list"][$i]['css'] .= " last-child";
		}

		return $clubList;
	}

	public static function getAdminClub($DB,$Trash)
	{
		$Sql = "SELECT * FROM `phpsay_club` WHERE ";

		if( $Trash == 0 )
		{
			$Sql .= "`position` = 0";
		}
		else
		{
			$Sql .= "`position` > 0 ORDER BY `position` ASC";
		}

		$Result = $DB->query($Sql);

		$clubList = array();

		while($Re = $DB->fetch_array($Result))
		{
			$clubList[] = array("cid"		=> $Re['cid'],
								"cname"		=> $Re['clubname'],
								"position"	=> $Re['position']
								);
		}

		return $clubList;
	}

	public static function trashClub($DB,$clubId)
	{
		$Position = $DB->fetch_one("SELECT `position` FROM `phpsay_club` WHERE `cid`=".$clubId);

		if( $Position ==  "" )
		{
			return "";
		}

		if( $Position ==  0 )
		{
			$DB->query("UPDATE `phpsay_club` SET `position` = 1 WHERE `cid`=".$clubId);

			return "1";
		}

		if( $Position >  0 )
		{
			$DB->query("UPDATE `phpsay_club` SET `position` = 0 WHERE `cid`=".$clubId);

			return "0";
		}
	}

	public static function clubOrder($DB,$clubIds)
	{
		$position = 1;

		foreach($clubIds as $cid)
		{
			$position++;

			$DB->query("UPDATE `phpsay_club` SET `position` = ".$position." WHERE `cid`=".$cid);
		}

		return $position - 1;
	}

	public static function editClub($DB,$clubId,$clubName)
	{
		if( $clubId == 0 )
		{
			$DB->query( $DB->insert_sql("`phpsay_club`",array("clubname" => $clubName)) );

			return $DB->insert_id();
		}
		else if( $clubId >= 1 )
		{
			if( self::checkExists($DB,"club",$clubId) == "" )
			{
				return "";
			}

			$DB->query( $DB->update_sql("`phpsay_club`",array("clubname" => $clubName),"`cid`=".$clubId) );

			$DB->query( $DB->update_sql("`phpsay_topic`",array("clubname" => $clubName),"`cid`=".$clubId) );

			return $clubId;
		}

		return "";
	}

	public static function postTopic($DB,$array)
	{
		$DB->query( $DB->insert_sql("`phpsay_topic`",$array) );

		$topicArray = array("tid"		=> $DB->insert_id(),
							"uid"		=> $array['uid'],
							"avatar"	=> getAvatarURL($DB,$array['uid']),
							"nickname"	=> $array['nickname'],
							"cid"		=> $array['cid'],
							"clubname"	=> "",
							"message"	=> filterHTML($array['message']),
							"smallimg"	=> getImageURL($array["picture"],"s"),
							"bigimage"	=> getImageURL($array["picture"],"b"),
							"posttime"	=> "刚刚",
							"lasttime"	=> 0,
							"comments"	=> 0);

		$array['tid'] = $topicArray['tid'];

		$array['pid'] = 0;
		
		self::writeNotification($DB,$array);

		return $topicArray;
	}

	public static function getOneTopic($DB,$tid)
	{
		$topicArray = array( "tid" => 0 );

		$Re = $DB->fetch_one_array("SELECT * FROM `phpsay_topic` WHERE `tid`='".$tid."'");

		if(!empty($Re['tid']))
		{
			$topicArray = array("tid"		=> $Re['tid'],
								"uid"		=> $Re['uid'],
								"avatar"	=> getAvatarURL($DB,$Re['uid']),
								"nickname"	=> $Re['nickname'],
								"cid"		=> $Re['cid'],
								"clubname"	=> $Re['clubname'],
								"message"	=> ubbReplace(filterHTML($Re['message'])),
								"smallimg"	=> getImageURL($Re["picture"],"s"),
								"bigimage"	=> getImageURL($Re["picture"],"b"),
								"posttime"	=> countDownTime($Re["posttime"]),
								"lasttime"	=> $Re["lasttime"],
								"comments"	=> $Re["comments"],
								"stick"     => $Re["stick"],
								"groupid" => $DB->fetch_one("select groupid from phpsay_member where uid=".$Re['uid'].""),								
								);
		}

		return $topicArray;
	}

	public static function getTopicLastTime($DB,$tid)
	{
		$lastTime = $DB->fetch_one("SELECT `posttime` FROM `phpsay_reply` WHERE `tid`=".$tid." ORDER BY `pid` DESC LIMIT 1");

		if (empty($lastTime))
		{
			$lastTime = $DB->fetch_one("SELECT `posttime` FROM `phpsay_topic` WHERE `tid`=".$tid);
		}

		return $lastTime;
	}

	public static function getTopic($DB,$key,$value,$page,$per)
	{
		$whereSQL = "WHERE";

		if( $key == "home" )
		{
			$clubIds = array();

			foreach($value as $clubInfo)
			{
				array_push($clubIds, $clubInfo['cid']);
			}

			$whereSQL .= " `cid` IN (".implode(",", $clubIds).")";
		}
		else if( $key == "club" )
		{
			$whereSQL .= " `cid` = ".$value;
		}
		else if( $key == "user" )
		{
			$whereSQL .= " `uid` = ".$value;
		}
		
		/* 绑定到小区 */
		$whereSQL .= " and community_id =  ".$GLOBALS['loginInfo']['community_id']." ";

		$Total = $DB->fetch_one("SELECT COUNT(`tid`) FROM `phpsay_topic` ".$whereSQL);

		$postList = array();

		if ( $Total > 0 )
		{
			$Result = $DB->query("SELECT * FROM `phpsay_topic` ".$whereSQL." ORDER BY `stick` DESC,`lasttime` DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$postList[] = array("tid"		=> $Re['tid'],
									"uid"		=> $Re['uid'],
									"avatar"	=> getAvatarURL($DB,$Re['uid']),
									"nickname"	=> $Re['nickname'],
									"cid"		=> $Re['cid'],
									"clubname"	=> ($key == "club") ? "" : $Re['clubname'],
									"message"	=> ubbReplace(filterHTML($Re['message'])),
									"smallimg"	=> getImageURL($Re["picture"],"s"),
									"bigimage"	=> getImageURL($Re["picture"],"b"),
									"posttime"	=> countDownTime($Re["posttime"]),
									"lasttime"	=> countDownTime($Re["lasttime"]),
									"comments"	=> $Re["comments"],
									"stick"     => $Re["stick"],
									"groupid" => $DB->fetch_one("select groupid from phpsay_member where uid=".$Re['uid'].""),
									);
			}
		}

		return array("count"=>$Total,"list"=>$postList,"page"=>self::getPage($Total,$per,$page));
	}

	public static function replyTopic($DB,$array,$updateLastTime)
	{
		$DB->query( $DB->insert_sql("`phpsay_reply`",$array) );

		$replyArray = array("pid"		=> $DB->insert_id(),
							"tid"		=> $array['tid'],
							"uid"		=> $array['uid'],
							"avatar"	=> getAvatarURL($DB,$array['uid']),
							"nickname"	=> $array['nickname'],
							"message"	=> filterHTML($array['message']),
							"smallimg"	=> getImageURL($array["picture"],"s"),
							"bigimage"	=> getImageURL($array["picture"],"b"),
							"posttime"	=> "刚刚");

		$updateArray = array( "comments" => array("`comments`+1") );

		if( $updateLastTime )
		{
			$updateArray['lasttime'] = $array['posttime'];
		}

		$DB->query($DB->update_sql("`phpsay_topic`",$updateArray,"`tid`=".$array['tid']));

		$array['pid'] = $replyArray['pid'];

		self::writeNotification($DB,$array);

		return $replyArray;
	}

	public static function deleteReply($DB,$pid)
	{
		$postInfo = $DB->fetch_one_array("SELECT `pid`,`tid`,`picture` FROM `phpsay_reply` WHERE `pid`='".$pid."'");

		if( !empty($postInfo['pid']) )
		{
			$deleteNumber = $DB->affected_rows("DELETE FROM `phpsay_reply` WHERE `pid`=".$postInfo['pid']);

			if ($deleteNumber == 1)
			{
				$DB->query("DELETE FROM `phpsay_notification` WHERE `pid`=".$postInfo['pid']);

				$DB->query("DELETE FROM `phpsay_favorite` WHERE `pid`=".$postInfo['pid']);

				$topicTime = self::checkExists($DB,"topic_time",$postInfo["tid"]);

				if( $topicTime != "" )
				{
					$updateArray = array( "comments" => array("`comments`-1") );

					if( $topicTime > 0 )
					{
						$updateArray['lasttime'] = self::getTopicLastTime($DB,$postInfo['tid']);
					}

					$DB->query($DB->update_sql("`phpsay_topic`",$updateArray,"`tid`=".$postInfo['tid']));
				}
			}
		}

		return array( "picture" => $postInfo['picture'] );
	}

	public static function getReply($DB,$key,$id,$page,$per)
	{
		$Total = $DB->fetch_one("SELECT COUNT(`pid`) FROM `phpsay_reply` WHERE `".$key."`=".$id);

		$postList = array();

		if ( $Total > 0 )
		{
			$Result = $DB->query("SELECT * FROM `phpsay_reply` WHERE `".$key."` = ".$id." ORDER BY `pid` ASC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$postList[] = array("pid"		=> $Re['pid'],
									"tid"		=> $Re['tid'],
									"uid"		=> $Re['uid'],
									"avatar"	=> getAvatarURL($DB,$Re['uid']),
									"nickname"	=> $Re['nickname'],
									"message"	=> ubbReplace(filterHTML($Re['message'])),
									"smallimg"	=> getImageURL($Re["picture"],"s"),
									"bigimage"	=> getImageURL($Re["picture"],"b"),
									"posttime"	=> countDownTime($Re["posttime"]),
									"groupid" => $DB->fetch_one("select groupid from phpsay_member where uid=".$Re['uid']." "),
									);
			}
		}

		return array("count"=>$Total,"list"=>$postList,"page"=>self::getPage($Total,$per,$page));
	}

	public static function searchPost($DB,$type,$word,$page,$per)
	{
		$columnName = ( $type == "reply" ) ? "pid" : "tid";

		$Total = $DB->fetch_one("SELECT COUNT(`".$columnName."`) FROM `phpsay_".$type."` WHERE `message` LIKE '%".$word."%'");

		$postList = array();

		if ( $Total > 0 )
		{
			$Result = $DB->query("SELECT * FROM `phpsay_".$type."` WHERE `message` LIKE '%".$word."%' ORDER BY `".$columnName."` DESC LIMIT ".($page-1)*$per.",".$per);

			while($Re = $DB->fetch_array($Result))
			{
				$postList[] = array("pid"		=> ( $type == "reply" ) ? $Re['pid'] : 0,
									"tid"		=> $Re['tid'],
									"uid"		=> $Re['uid'],
									"nickname"	=> $Re['nickname'],
									"message"	=> filterHTML($Re['message']),
									"smallimg"	=> getImageURL($Re["picture"],"s"),
									"bigimage"	=> getImageURL($Re["picture"],"b"),
									"posttime"	=> countDownTime($Re["posttime"]));
			}
		}

		$userStatistics = isset($_COOKIE['searchSubmits']) ? intval($_COOKIE['searchSubmits']) : 0;

		if( isset($_COOKIE['searchSubmit']) )
		{
			setcookie('searchSubmit','',0);

			$userStatistics++;
		}

		setcookie('searchSubmits',$userStatistics,mktime(23,59,59,date('n'),date('j'),date('Y')),"/");

		return array("count"=>$Total,"list"=>$postList,"page"=>self::getPage($Total,$per,$page),"statistics"=>$userStatistics);
	}

	private static function getPage($total,$per,$page)
	{
		$pageCount = ceil($total/$per);

		$string = str_replace("index.php","",$_SERVER['PHP_SELF'])."?";

		foreach( $_GET as $k => $v )
		{
			if( $k != "page" )
			{
				$string .= "".$k."=".urlencode($v)."&";
			}
		}

		$pagePrev = "";

		$pageNext = "";

		if ( $page > 1 )
		{
			$pagePrev = $page - 1;
		}

		if ( $page < $pageCount )
		{
			$pageNext = $page + 1;
		}
		
		return array( "Prefix" => $string."page=", "Prev" => $pagePrev, "Next" => $pageNext, "Total"=> $pageCount, "Current" => $page );
	}	
}
?>