<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, height=device-height, user-scalable=no" name="viewport">
  <title><?php
echo $_obj['PHPSayConfig']['sitename'];
?>
</title>
  <link rel="stylesheet" type="text/css" media="screen" href="mobile_static/flat.css" />
  <link rel="apple-touch-icon" href="mobile_static/apple-touch-icon.png" />
  <script type="text/javascript" src="static/jquery.js"></script>
  <script type="text/javascript" src="mobile_static/mobile.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $("#setname").submit(setname);
  });
  </script>
</head>
<body>
  <div class="header">
   <div class="logo">
    <a style="width:auto;font-size:20px;line-height:30px;color:#333;text-align:left;background:none;">
     <?php
echo $_obj['CommunityName'];
?>

	</a>
   </div>
  </div>
  <div id="wrapper">
    <div class="content">
      <div class="box">
        <div class="cell">
          <div class="navigation">亲，请设置一个响亮的社区昵称吧～</div>
        </div>
        <div class="inner">
          <form id="setname" method="post" action="./passport.php?do=setname">
            <div class="user-input">
              <input id="nickname" type="text" maxlength="36" placeholder="<?php
echo $_obj['nickname'];
?>
" name="nickname" autocorrect="off" autocapitalize="off">
            </div>
            <input class="submit-button" type="submit" value="确认使用该昵称，进入社区">
          </form>
        </div>
      </div>   
    </div>
  </div>
  <div class="footer">
	&copy; 2014 小区快帮
</div>
</body>
</html>