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
  <script type="text/javascript">
  $(document).ready(function(){
    $('.item-image img').click(imageZoom);
  });
  </script>  
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
          <div class="navigation">
            <a href="./">首页</a><span class="chevron">›</span>我的提醒
          </div>
        </div>
        <?php
if (!empty($_obj['notificationList']['list'])){
if (!is_array($_obj['notificationList']['list']))
$_obj['notificationList']['list']=array(array('list'=>$_obj['notificationList']['list']));
$_tmp_arr_keys=array_keys($_obj['notificationList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['notificationList']['list']=array(0=>$_obj['notificationList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['notificationList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
        <div class="cell" id="notify-<?php
echo $_obj['nid'];
?>
">
          <div class="item">
            <div class="item-avatar">
              <a href="user.php?id=<?php
echo $_obj['uid'];
?>
"><img src="<?php
echo $_obj['avatar'];
?>
"></a>
            </div>
            <div class="item-nickname">
              <a href="user.php?id=<?php
echo $_obj['uid'];
?>
">
			  <?php
echo $_obj['nickname'];
?>

			  <?php
if ($_obj['groupid'] > "1"){
?>
			   <span class="tag">管理员</span>
			  <?php
}
?>
			  </a>
            </div>
            <div class="item-message">
              <?php
echo $_obj['message'];
?>

            </div>
            <?php
if ($_obj['smallimg'] != ""){
?>
            <div class="item-image">
              <img src="<?php
echo $_obj['smallimg'];
?>
" rel="<?php
echo $_obj['bigimage'];
?>
">
            </div>
            <?php
}
?>
            <div class="item-info">
              <a href="t.php?id=<?php
echo $_obj['tid'];
?>
<?php
if ($_obj['pid'] > "0"){
?>#reply<?php
echo $_obj['pid'];
?>
<?php
}
?>"<?php
if ($_obj['isread'] == "0"){
?> class="unread"<?php
}
?>><?php
echo $_obj['posttime'];
?>
</a>
			  <a href="t.php?id=<?php
echo $_obj['tid'];
?>
<?php
if ($_obj['pid'] > "0"){
?>#reply<?php
echo $_obj['pid'];
?>
<?php
}
?>" class="reply notification">回复</a>
              <a onClick="deleteNotify(<?php
echo $_obj['nid'];
?>
);" class="delete">删除</a>
            </div>
          </div>
        </div>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <div class="inner">
          <?php
if ($_obj['notificationList']['page']['Total'] > "1"){
?>
            <?php
if ($_obj['notificationList']['page']['Prev'] != ""){
?>
            <a href="<?php
echo $_obj['notificationList']['page']['Prefix'];
?>
<?php
echo $_obj['notificationList']['page']['Prev'];
?>
" class="page prev">上一页</a>
            <?php
}
?>
            <?php
if ($_obj['notificationList']['page']['Next'] != ""){
?>
            <a href="<?php
echo $_obj['notificationList']['page']['Prefix'];
?>
<?php
echo $_obj['notificationList']['page']['Next'];
?>
" class="page next">下一页</a>
            <?php
}
?>
            <strong><?php
echo $_obj['notificationList']['page']['Current'];
?>
 / <?php
echo $_obj['notificationList']['page']['Total'];
?>
</strong>
          <?php
} else {
?>
            <strong><?php
if ($_obj['notificationList']['count'] > "0"){
?>已加载全部提醒<?php
} else {
?>暂无提醒<?php
}
?></strong>
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
  <div class="hidden">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F12c7ebc0d5e268e34feb51b6c41feead' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>