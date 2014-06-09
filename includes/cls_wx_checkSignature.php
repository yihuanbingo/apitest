<?php
/*
 * 微信验证签名是否正确
 * 请在每一个微信接口前调用此类
 * author yuanjiang @2.16.2013
*/

if (!defined('IN_BS'))
{
  die('Hacking attempt');
}

class wx_checkSignature
{

	static $token = 'a23dfa3sdf34llk423oiu242342fasdf';
	
	/*
	 * 验证签名
	*/	
	static function checkSignature($signature, $timestamp, $nonce)
	{   
		$tmpArr = array(self::$token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>