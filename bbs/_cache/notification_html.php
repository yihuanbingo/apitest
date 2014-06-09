<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>我的提醒<?php
if ($_obj['notificationNumber'] > "0"){
?>（<?php
echo $_obj['notificationNumber'];
?>
）<?php
}
?> - <?php
echo $_obj['PHPSayConfig']['sitename'];
?>
</title>
  <link rel="stylesheet" type="text/css" media="screen" href="static/say.css" />
  <script type="text/javascript" src="static/jquery.js"></script>
  <script type="text/javascript" src="static/jquery.flyout.js"></script>
  <script type="text/javascript" src="static/say.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $(".stream-items .stream-item .stream-content").click(quickReply);
  });
  </script>  
</head>
<body>
  <div class="header web-bg">
  <div class="top-nav">
    <ul class="left-nav">
      <li>
        <a<?php
if ($_obj['headerNavi'] == "home"){
?> class="home"<?php
}
?> title="主页" href="/"><span class="web-icon nav-home"></span>主页</a>
      </li>
      <li>
        <a<?php
if ($_obj['headerNavi'] == "at"){
?> class="home"<?php
}
?> title="提醒" href="notification.php"><span class="web-icon nav-at"></span>提醒</a>
      </li>
      <li>
        <a<?php
if ($_obj['headerNavi'] == "me"){
?> class="home"<?php
}
?> title="我" href="user.php"><span class="web-icon nav-me"></span>我</a>
      </li>
    </ul>
    <div class="right-nav">
      <div class="search">
        <form method="get" action="search.php" onsubmit="return searchPHPSay();">
          <input id="search-query" class="search-input" type="text" spellcheck="false" autocomplete="off" name="q" placeholder="搜索">
          <span class="search-icon">
            <button class="web-icon nav-search" type="submit" tabindex="-1"></button>
          </span>
          <input id="search-query-hint" class="search-input search-hinting-input" type="text" spellcheck="false" autocomplete="off" disabled="disabled">
        </form>
      </div>
      <i class="web-icon topbar-divider"></i>
      <div class="settings">
        <a<?php
if ($_obj['headerNavi'] == "settings"){
?> class="home"<?php
}
?> title="设置" href="settings.php"><span class="web-icon nav-settings"></span></a>
      </div>
      <i class="web-icon topbar-divider"></i>
      <div class="web-bg logout">
        <a class="web-bg logout-btn" title="退出" onclick="location.href='connect.php'"><i class="nav-logout web-icon"></i></a>
      </div>
    </div>
  </div>
</div>
  <div class="container">
    <div class="dashboard ">
      <div class="mini-profile">
  <div class="profile-summary">
    <a href="user.php">
      <div class="profile-content">
        <img src="<?php
echo $_obj['loginInfo']['avatar'];
?>
"><b><?php
echo $_obj['loginInfo']['nickname'];
?>
</b><small>查看我的个人页</small>
      </div>
    </a>
  </div>
  <div class="profile-bottom">
    <a href="notification.php?status=0" class="notify-count"><strong><?php
echo $_obj['notificationNumber'];
?>
</strong>提醒</a>
    <a href="user.php?list=favorite" class="favorite-count"><strong><?php
echo $_obj['favoriteNumber'];
?>
</strong>收藏</a>
  </div>
</div>
      <div class="bar-nav">
        <ul class="nav-links">
          <li>
            <a class="first-child<?php
if ($_obj['notifyStatus'] == "0"){
?> active<?php
}
?>" href="notification.php?status=0">
              未读<i class="web-icon chev-right"></i>
            </a>
          </li>
          <li>
            <a class="last-child<?php
if ($_obj['notifyStatus'] == "1"){
?> active<?php
}
?>" href="notification.php?status=1">
              已读<i class="web-icon chev-right"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="site-footer">
  <div class="footer-inner">
    <div class="footer-copyright">&copy; 2014 PHPSay. Version: <?php
echo $_obj['PHPSayConfig']['version'];
?>
</div>
  </div>
</div>
    </div>
    <div class="content">
      <div class="content-header">
        <div class="header-inner">
          <h2>@<?php
echo $_obj['loginInfo']['nickname'];
?>
</h2>
        </div>
      </div>
      <ol class="stream-items">
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
        <li class="stream-item" id="item-<?php
echo $_obj['nid'];
?>
">
          <div class="stream-content" data="<?php
echo $_obj['tid'];
?>
-<?php
echo $_obj['nickname'];
?>
">
            <a href="user.php?id=<?php
echo $_obj['uid'];
?>
" class="item-user"><img class="item-avatar" src="<?php
echo $_obj['avatar'];
?>
" alt="<?php
echo $_obj['nickname'];
?>
"><strong class="item-nickname"><?php
echo $_obj['nickname'];
?>
</strong></a>
            <small class="time<?php
if ($_obj['isread'] == "0"){
?> unread<?php
}
?>"><a href="t.php?id=<?php
echo $_obj['tid'];
?>
<?php
if ($_obj['pid'] > "0"){
?>#reply<?php
echo $_obj['pid'];
?>
<?php
}
?>"><?php
echo $_obj['posttime'];
?>
</a></small>
            <p class="item-message"><?php
echo $_obj['message'];
?>
</p>
            <?php
if ($_obj['smallimg'] != ""){
?>
            <div class="item-picture">
              <a href="<?php
echo $_obj['bigimage'];
?>
" class="zoom"><img src="<?php
echo $_obj['smallimg'];
?>
" alt="" title=""></a>
            </div>
            <?php
}
?>
            <div class="stream-item-footer">
              <div class="item-actions">
                <a onclick="deleteNotification(<?php
echo $_obj['nid'];
?>
);" id="deleteNotification-<?php
echo $_obj['nid'];
?>
"><span class="web-icon icon-trash"></span>删除</a>
              </div>
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
?>">查看</a>
            </div>
          </div>
        </li>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
      </ol>
      <div class="stream-footer">
        <div class="stream-end-inner">
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
">上一页</a>
            <?php
if ($_obj['notificationList']['page']['Next'] != ""){
?>
            <span class="pagination"><?php
echo $_obj['notificationList']['page']['Current'];
?>
 / <?php
echo $_obj['notificationList']['page']['Total'];
?>
</span>
            <?php
}
?>
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
">下一页</a>
            <?php
}
?>
          <?php
} else {
?>
            <span class="pagination"><?php
if ($_obj['notificationList']['count'] > "0"){
?>已加载全部提醒<?php
} else {
?>暂无提醒<?php
}
?></span>
          <?php
}
?>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="alert-messages">
    <div class="message">
      <span class="message-text"></span>
    </div>
  </div>
  <script type="text/javascript">

</script>
</body>
</html>