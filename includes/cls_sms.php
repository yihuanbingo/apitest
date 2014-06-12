<?php
/*
 * 手机短信功能：手机验证、系统消息发送
 ===================  中国网建短信接口，106网关，http://sms.webchinese.cn  ======================
 @ author yuanjiang 5.6.2013
*/
if(!defined('IN_WD'))
{
  die('hacking attempt');
}

class Sms
{
   /* 配置信息 */
   var $url = '';     // 应用接口地址
   var $uid = '';     // 账号
   var $sms_key = '';  // 账号对应密钥
   
   function __construct($url,$uid,$key)
   {
      $this->url = $url;
	  $this->uid = $uid;
	  $this->sms_key = $key;
   }		

   /*
    * 发送验证码
    * @param phone
	* @return int，大于1则发送成功
   */
   function sendCaptcha($phone)
   {
	  $word = $this->generate_word();
	  $this->record_word($word);
	  $content = "验证码：".$word."，10分钟内有效，不区分大小写";
	  $this->sendMsg($phone,$content);
   }
   
   /*
    * 发送普通信息
	* @param phone, content
	* @return int，大于1则发送成功
   */
   function sendMsg($phone,$content)
   {    
        $content = urlencode($content);    //加密
        $url = $this->url.'/?Uid='.$this->uid.'&key='.$this->sms_key.'&smsMob='.$phone.'&smsText='.$content;
	    if(function_exists('file_get_contents'))
        {
            $file_contents = file_get_contents($url);
        }
        else
        {
           $ch = curl_init();
           $timeout = 5;
           curl_setopt ($ch, CURLOPT_URL, $url);
           curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
           $file_contents = curl_exec($ch);
           curl_close($ch);
         }
		 return $file_contents;   // 结果：大于0则发送成功，正整数表示该信息条数；错误代码请参照sms.webchinese.cn
   }
   
   /**
     * 生成随机的验证码
     *
     * @access  private
     * @param   integer $length     验证码长度
     * @return  string
     */
    private function generate_word($length = 6)
    {
        $chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

        for ($i = 0, $count = strlen($chars); $i < $count; $i++)
        {
            $arr[$i] = $chars[$i];
        }

        mt_srand((double) microtime() * 1000000);
        shuffle($arr);

        return substr(implode('', $arr), 0, $length);
    }
	
	/**
     * 对需要记录的串进行加密
     *
     * @access  private
     * @param   string  $word   原始字符串
     * @return  string
     */
    private function encrypts_word($word)
    {
        return substr(md5($word), 1, 10);
    }
	
	/**
     * 将验证码保存到session
     *
     * @access  private
     * @param   string  $word   原始字符串
     * @return  void
     */
    private function record_word($word)
    {
        $_SESSION['captcha_sms'] = base64_encode($this->encrypts_word($word));
    }
	
	/**
     * 检查给出的验证码是否和session中的一致
     *
     * @access  public
     * @param   string  $word   验证码
     * @return  bool
     */
    public function check_word($word)
    {
        $recorded = isset($_SESSION['captcha_sms']) ? base64_decode($_SESSION['captcha_sms']) : '';
        $given    = $this->encrypts_word(strtoupper($word));

        return (preg_match("/$given/", $recorded));
    }


}

/* 以下为使用实例，工作正常 
$sms = new cls_sms('http://utf8.sms.webchinese.cn','lebai100','837bb2234bd3aea4b340');
echo $sms->sendMsg('13628026066','收到短信说明接口正常');
*/

?>