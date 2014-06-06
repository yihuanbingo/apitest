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
  <script type="text/javascript" src="mobile_static/jquery.form.js"></script>
  <script type="text/javascript" src="mobile_static/mobile.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.item-image img').click(imageZoom);
   // $("#reply-topic-form").submit(replyTopic);
//    $("#reply-topic-form textarea[name=message]").focusin(function(){
//      $(this).attr("rows","6");
//    }).focusout(function(){
//      $(this).attr("rows","3");
//    });
    $(".favorite").click(favoriteMsg);
    $(".favorited").click(unFavoriteMsg);
    <?php
if ($_obj['loginInfo']['group'] < "2"){
?>
    $(".reply").css({"right":"30px"}).fadeIn();
    $(".favorite").css({"right":"0"}).fadeIn();
    $(".favorited").css({"right":"0"}).fadeIn();
    <?php
} else {
?>
    $(".reply").fadeIn();
    $(".favorite").fadeIn();
    $(".favorited").fadeIn();
    <?php
}
?>
    locationHash();
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
            <a href="./">首页</a>
            <span class="chevron">›</span>
            <a href="./?cid=<?php
echo $_obj['topicInfo']['cid'];
?>
"><?php
echo $_obj['topicInfo']['clubname'];
?>
</a>
          </div>
        </div>
        <div class="cell">
          <div class="item">
            <div class="item-avatar">
              <a href="user.php?id=<?php
echo $_obj['topicInfo']['uid'];
?>
"><img src="<?php
echo $_obj['topicInfo']['avatar'];
?>
"></a>
            </div>
            <div class="item-nickname">
              <a href="user.php?id=<?php
echo $_obj['topicInfo']['uid'];
?>
">
			  <?php
echo $_obj['topicInfo']['nickname'];
?>

			  <?php
if ($_obj['topicInfo']['groupid'] == "2"){
?>
			    <span class="tag">小区管理员</span>
			   <?php
}
?>
			  </a>
            </div>
            <div class="item-message">
              <?php
echo $_obj['topicInfo']['message'];
?>

            </div>
            <?php
if ($_obj['topicInfo']['smallimg'] != ""){
?>
            <div class="item-image">
              <img src="<?php
echo $_obj['topicInfo']['smallimg'];
?>
" rel="<?php
echo $_obj['topicInfo']['bigimage'];
?>
">
            </div>
            <?php
}
?>
            <div class="item-info">
              <span class="time"><?php
echo $_obj['topicInfo']['posttime'];
?>
</span>
              <a onClick="replyAt('<?php
echo $_obj['topicInfo']['nickname'];
?>
');" class="reply hidden">回复</a>
              <a class="favorite<?php
if ($_obj['favoriteId'] != ""){
?>d<?php
}
?> hidden" data="topic-<?php
echo $_obj['topicInfo']['tid'];
?>
">收藏</a>
              <?php
if ($_obj['loginInfo']['group'] > "1"){
?>
                <a onClick="deleteTopic(<?php
echo $_obj['topicInfo']['tid'];
?>
,<?php
echo $_obj['topicInfo']['cid'];
?>
);" class="delete">删除</a>
              <?php
}
?>              
            </div>
          </div>
        </div>
        <div class="inner" id="reply-topic">
          <form action="post.php" method="post" id="reply-topic-form" enctype="multipart/form-data">
            <div class="post-button-left">
              <input type="file" name="picture" id="picture">
            </div>
			<div class="input-body">
              <textarea maxlength="200" name="message" rows="5"></textarea>
            </div>
			<input type="hidden" name="do" value="replyTopic">
            <input type="hidden" name="tid" value="<?php
echo $_obj['topicInfo']['tid'];
?>
">
            <input class="submit-button" type="button" value="发表回复" onClick="javascript:replyTopic();">
          </form>
        </div>
      </div>
      <div class="sep5"></div>
      <div class="box">
        <?php
if (!empty($_obj['replyList']['list'])){
if (!is_array($_obj['replyList']['list']))
$_obj['replyList']['list']=array(array('list'=>$_obj['replyList']['list']));
$_tmp_arr_keys=array_keys($_obj['replyList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['replyList']['list']=array(0=>$_obj['replyList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['replyList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
        <div class="cell" id="reply-<?php
echo $_obj['pid'];
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
"><?php
echo $_obj['nickname'];
?>
 
			   <?php
if ($_obj['groupid'] == "2"){
?>
			    <span class="tag">小区管理员</span>
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
              <span class="time"><?php
echo $_obj['posttime'];
?>
</span>
              <a onClick="replyAt('<?php
echo $_obj['nickname'];
?>
');" class="reply hidden">回复</a>
              <a class="favorite hidden" data="reply-<?php
echo $_obj['pid'];
?>
">收藏</a>
              <?php
if ($_stack[0]['loginInfo']['group'] > "1"){
?>
                <a onClick="deleteReply(<?php
echo $_obj['pid'];
?>
);" class="delete">删除</a>
              <?php
}
?>
            </div>
          </div>
        </div>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <div class="inner">
          <?php
if ($_obj['replyList']['page']['Total'] > "1"){
?>
            <?php
if ($_obj['replyList']['page']['Prev'] != ""){
?>
            <a href="<?php
echo $_obj['replyList']['page']['Prefix'];
?>
<?php
echo $_obj['replyList']['page']['Prev'];
?>
" class="page prev">上一页</a>
            <?php
}
?>
            <?php
if ($_obj['replyList']['page']['Next'] != ""){
?>
            <a href="<?php
echo $_obj['replyList']['page']['Prefix'];
?>
<?php
echo $_obj['replyList']['page']['Next'];
?>
" class="page next">下一页</a>
            <?php
}
?>
            <strong><?php
echo $_obj['replyList']['page']['Current'];
?>
 / <?php
echo $_obj['replyList']['page']['Total'];
?>
</strong>
          <?php
} else {
?>
            <strong><?php
if ($_obj['replyList']['count'] > "0"){
?>已加载全部回复<?php
} else {
?>暂无回复<?php
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
  <script type="text/javascript">

</script>
</body>
</html>
