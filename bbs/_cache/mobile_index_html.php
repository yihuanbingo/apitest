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
  <link rel="stylesheet" type="text/css" media="screen" href="template/qqemotion/css/qqemotion.css">
  <link rel="apple-touch-icon" href="mobile_static/apple-touch-icon.png" />
  <script type="text/javascript" src="static/jquery.js"></script>
  <script type="text/javascript" src="mobile_static/jquery.form.js"></script>
  <script type="text/javascript" src="mobile_static/mobile.js"></script>
  <script type="text/javascript" src="template/qqemotion/js/jquery.qqFace.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.item-image img').click(imageZoom);
    <?php
if ($_obj['clubList']['current']['cid'] > "0"){
?>
    $(".add-topic").click(function(){
      $("#add-topic-form textarea[name=message]").focus();
    });
	/* 初始化qq表情插件 */
	$('.emotion').qqFace({
		id : 'facebox', 
		assign:'saytext', 
		path:'template/qqemotion/arclist/'	//表情存放的路径
	});
    <?php
}
?>
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
          <div class="notification">
          <?php
if ($_obj['notificationNumber'] > "0"){
?>
            <a href="notification.php" class="notify-highlight"><?php
echo $_obj['notificationNumber'];
?>
 条未读提醒</a>
          <?php
} else {
?>
            <span class="icon-notify"></span>
            <a href="notification.php" class="notify"><?php
echo $_obj['notificationNumber'];
?>
 条未读提醒</a>
          <?php
}
?>
          </div>
          <?php
if ($_obj['clubList']['current']['cid'] > "0"){
?>
          <a class="add-topic">发布新消息</a>
          <?php
}
?>
        </div>
        <div class="cell">
          <a class="tab<?php
if ($_obj['clubList']['current']['cid'] == "0"){
?> current<?php
}
?>" href="./">所有版块</a>
          <?php
if (!empty($_obj['clubList']['list'])){
if (!is_array($_obj['clubList']['list']))
$_obj['clubList']['list']=array(array('list'=>$_obj['clubList']['list']));
$_tmp_arr_keys=array_keys($_obj['clubList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['clubList']['list']=array(0=>$_obj['clubList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['clubList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
          <a class="tab<?php
if ($_stack[0]['clubList']['current']['cid'] == $_obj['cid']){
?> current<?php
}
?>" href="./?cid=<?php
echo $_obj['cid'];
?>
"><?php
echo $_obj['cname'];
?>
</a>
          <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        </div>
        <?php
if (!empty($_obj['topicList']['list'])){
if (!is_array($_obj['topicList']['list']))
$_obj['topicList']['list']=array(array('list'=>$_obj['topicList']['list']));
$_tmp_arr_keys=array_keys($_obj['topicList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['topicList']['list']=array(0=>$_obj['topicList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['topicList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
        <div class="cell">
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
"><?php
echo $_obj['nickname'];
?>
</a> 
			  <?php
if ($_obj['groupid'] == "2"){
?>
			   <span class="tag">管理员</span>
			  <?php
}
?>
            </div>
            <a href="t.php?id=<?php
echo $_obj['tid'];
?>
">
            <div class="item-message">
              <?php
echo $_obj['message'];
?>

            </div>
            </a>
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
              <?php
if ($_obj['clubname'] != ""){
?>
              <a href="./?cid=<?php
echo $_obj['cid'];
?>
"><?php
echo $_obj['clubname'];
?>
</a>
              <span class="point">•</span>
              <?php
}
?>
              <a href="t.php?id=<?php
echo $_obj['tid'];
?>
"><?php
echo $_obj['posttime'];
?>
</a>
              <span class="point">•</span>
              <a href="t.php?id=<?php
echo $_obj['tid'];
?>
" id="comment-<?php
echo $_obj['tid'];
?>
"><?php
if ($_obj['comments'] > "0"){
?><strong><?php
echo $_obj['comments'];
?>
</strong> <?php
} else {
?>暂无<?php
}
?>回复</a>
            </div>
          </div>
        </div>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <div class="inner">
          <?php
if ($_obj['topicList']['page']['Total'] > "1"){
?>
            <?php
if ($_obj['topicList']['page']['Prev'] != ""){
?>
            <a href="<?php
echo $_obj['topicList']['page']['Prefix'];
?>
<?php
echo $_obj['topicList']['page']['Prev'];
?>
" class="page prev">上一页</a>
            <?php
}
?>
            <?php
if ($_obj['topicList']['page']['Next'] != ""){
?>
            <a href="<?php
echo $_obj['topicList']['page']['Prefix'];
?>
<?php
echo $_obj['topicList']['page']['Next'];
?>
" class="page next">下一页</a>
            <?php
}
?>
            <strong><?php
echo $_obj['topicList']['page']['Current'];
?>
 / <?php
echo $_obj['topicList']['page']['Total'];
?>
</strong>
          <?php
} else {
?>
            <strong><?php
if ($_obj['topicList']['count'] > "0"){
?>已加载全部数据<?php
} else {
?>暂无数据<?php
}
?></strong>
          <?php
}
?>
        </div>
      </div>
      <?php
if ($_obj['clubList']['current']['cid'] > "0"){
?>
      <div class="sep5"></div>
      <div class="box">
        <div class="cell" id="post-header">
          <div class="navigation">发篇新的</div>
        </div>
        <div class="inner" id="post-form">
          <form action="post.php" method="post" id="add-topic-form" enctype="multipart/form-data">
		    <div class="post-button-left">
              <input type="file" name="picture" id="picture">
            </div>
            <div class="input-body">
              <textarea maxlength="200" id="saytext" name="message" rows="5"></textarea>
            </div>
			<input type="hidden" name="do" value="addTopic">
            <input type="hidden" name="cid" value="<?php
echo $_obj['clubList']['current']['cid'];
?>
">
			<div>
			 <span class="emotion">表情</span>
		     <input type="button" class="sub_btn" value="提 交" onClick="javascript:postTopic();">
            </div>
			<div style="clear:both"></div>
		  </form>
        </div>
      </div>
      <?php
}
?>
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
