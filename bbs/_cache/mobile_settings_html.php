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
  <script type="text/javascript" src="static/jquery.js"></script>
  <script type="text/javascript" src="mobile_static/mobile.js"></script>
</head>
<body>
    <div class="header">
    <div class="logo">
      <a href="./" style="width:auto;font-size:20px;line-height:30px;color:#333;text-align:left;background:none;"><?php
echo $_obj['loginInfo']['community_name'];
?>
</a>
    </div>
    <div class="navi">
      <a class="user" href="user.php" ><img src="<?php
echo $_obj['loginInfo']['avatar'];
?>
"></a>
      <a class="setting" href="settings.php"></a>
      <a class="logout" href="connect.php" style="display:none;"></a>
    </div>
  </div>
  <div id="wrapper">
    <div class="content">
      <div class="box">
        <div class="cell">
          <a class="tab<?php
if ($_obj['settingType'] == "avatar"){
?> current<?php
}
?>" href="./settings.php">头像</a>
          <a class="tab<?php
if ($_obj['settingType'] == "email"){
?> current<?php
}
?>" href="./settings.php?with=email" style="display:none">邮箱</a>
          <a class="tab<?php
if ($_obj['settingType'] == "password"){
?> current<?php
}
?>" href="./settings.php?with=password" style="display:none">密码</a>
        </div>
        <div class="inner">
          <?php
if ($_obj['settingType'] == "avatar"){
?>
            <form id="refreshAvatar">
            <div class="current-avatar">
              <img src="<?php
echo $_obj['loginInfo']['avatar'];
?>
" style="float:left;margin-right:10px">
              <font style="font-size:16px"><?php
echo $_obj['loginInfo']['nickname'];
?>
</font>
            </div>
            <input class="submit-button" id="refreshAvatarSubmit" type="submit" value="刷新微信头像">
          </form>
		  <script type="text/javascript">$("#refreshAvatar").submit(refreshAvatar);</script>
          <?php
} elseif ($_obj['settingType'] == "email"){
?>
            <?php
if ($_obj['userInfo']['password'] == ""){
?>
              <strong>设置邮箱前需要先设置个人密码</strong>
            <?php
} else {
?>
              <form name="emailForm" id="emailForm" action="post.php" method="post">
                <div class="user-input">
                  <input type="password" maxlength="26" placeholder="当前密码" name="password" autocorrect="off" autocapitalize="off">
                </div>
                <div class="user-input">
                  <input type="text" maxlength="36" placeholder="邮件地址" name="email" value="<?php
echo $_obj['userInfo']['email'];
?>
" autocorrect="off" autocapitalize="off">
                </div>            
                <input class="submit-button" type="submit" value="保 存">
              </form>
              <script type="text/javascript">$("#emailForm").submit(settingEmail);</script>
            <?php
}
?>
          <?php
} elseif ($_obj['settingType'] == "password"){
?>
          <form name="passwordForm" id="passwordForm" action="post.php" method="post">
            <div class="user-input<?php
if ($_obj['userInfo']['password'] == ""){
?> hidden<?php
}
?>">
              <input type="password" maxlength="26" placeholder="当前密码" name="password_current" autocorrect="off" autocapitalize="off">
            </div>
            <div class="user-input">
              <input type="password" maxlength="26" placeholder="<?php
if ($_obj['userInfo']['password'] != ""){
?>新<?php
}
?>密码" name="password" autocorrect="off" autocapitalize="off">
            </div>
            <div class="user-input">
              <input type="password" maxlength="26" placeholder="确认密码" name="password_confirm" autocorrect="off" autocapitalize="off">
            </div>            
            <input class="submit-button" type="submit" value="保 存">
          </form>
          <script type="text/javascript">$("#passwordForm").submit(settingPassword);</script>
          <?php
}
?>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
	&copy; 2014 小区快帮
</div>
  <script type="text/javascript">

</script>
</body>
</html>