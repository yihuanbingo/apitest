<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title><?php
echo $_obj['userInfo']['nickname'];
?>
 - <?php
echo $_obj['PHPSayConfig']['sitename'];
?>
</title>
  <link rel="stylesheet" type="text/css" media="screen" href="static/say.css" />
  <script type="text/javascript" src="static/jquery.js"></script>
  <script type="text/javascript" src="static/jquery.flyout.js"></script>
  <script type="text/javascript" src="static/say.js"></script>
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
          <div class="profile-content">
            <img src="<?php
echo $_obj['userInfo']['avatar'];
?>
">
            <b><?php
echo $_obj['userInfo']['nickname'];
?>
</b>
            <small>第<em><?php
echo $_obj['userInfo']['uid'];
?>
</em>位会员</small>
          </div>
          <?php
if ($_obj['loginInfo']['group'] > "1"){
?>
            <?php
if ($_obj['userInfo']['groupid'] < "2"){
?>
              <a id="block" class="block<?php
if ($_obj['userInfo']['groupid'] == "0"){
?>ed<?php
}
?>" href="javascript:;" onclick="blockUser(<?php
echo $_obj['userInfo']['uid'];
?>
);"></a>
            <?php
}
?>
          <?php
}
?>          
        </div>
        <div class="profile-bottom">
          <div class="profile-join">
            加入时间：<?php
echo $_obj['userInfo']['regtime'];
?>

          </div>
        </div>
      </div>
      <?php
if ($_obj['userInfo']['uid'] == $_obj['loginInfo']['uid']){
?>
      <div class="bar-nav">
        <ul class="nav-links">
          <li>
            <a class="first-child last-child<?php
if ($_obj['postType'] == "favorite"){
?> active<?php
}
?>" href="user.php?list=favorite">
              我的收藏<i class="web-icon chev-right"></i>
            </a>
          </li>
        </ul>
      </div>
      <?php
}
?>
      <div class="bar-nav">
        <ul class="nav-links">
          <li>
            <a class="first-child<?php
if ($_obj['postType'] == "topic"){
?> active<?php
}
?>" href="user.php<?php
if ($_obj['userInfo']['uid'] != $_obj['loginInfo']['uid']){
?>?id=<?php
echo $_obj['userInfo']['uid'];
?>
<?php
}
?>">
              <?php
if ($_obj['userInfo']['uid'] == $_obj['loginInfo']['uid']){
?>我<?php
} else {
?><?php
echo $_obj['userInfo']['nickname'];
?>
<?php
}
?>发布的主题<i class="web-icon chev-right"></i>
            </a>
          </li>
          <li>
            <a class="last-child<?php
if ($_obj['postType'] == "reply"){
?> active<?php
}
?>" href="user.php?list=reply<?php
if ($_obj['userInfo']['uid'] != $_obj['loginInfo']['uid']){
?>&id=<?php
echo $_obj['userInfo']['uid'];
?>
<?php
}
?>">
              <?php
if ($_obj['userInfo']['uid'] == $_obj['loginInfo']['uid']){
?>我<?php
} else {
?><?php
echo $_obj['userInfo']['nickname'];
?>
<?php
}
?>发表的回复<i class="web-icon chev-right"></i>
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
          <h2>
            <?php
if ($_obj['postType'] == "topic"){
?>
              <?php
if ($_obj['userInfo']['uid'] == $_obj['loginInfo']['uid']){
?>我<?php
} else {
?><?php
echo $_obj['userInfo']['nickname'];
?>
<?php
}
?>发布的主题
            <?php
} elseif ($_obj['postType'] == "reply"){
?>
              <?php
if ($_obj['userInfo']['uid'] == $_obj['loginInfo']['uid']){
?>我<?php
} else {
?><?php
echo $_obj['userInfo']['nickname'];
?>
<?php
}
?>发表的回复
            <?php
} elseif ($_obj['postType'] == "favorite"){
?>
              我的收藏
            <?php
}
?>
          </h2>
        </div>
      </div>
      <ol class="user-items">
        <?php
if ($_obj['postType'] == "topic"){
?>
          <?php
if (!empty($_obj['postList']['list'])){
if (!is_array($_obj['postList']['list']))
$_obj['postList']['list']=array(array('list'=>$_obj['postList']['list']));
$_tmp_arr_keys=array_keys($_obj['postList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['postList']['list']=array(0=>$_obj['postList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['postList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
          <li class="user-item" id="item-<?php
echo $_obj['tid'];
?>
">
            <div class="stream-content">
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
                  <a onclick="favTopic(<?php
echo $_obj['tid'];
?>
);" id="favTopic-<?php
echo $_obj['tid'];
?>
"><span class="web-icon icon-fav"></span>收藏</a>
                  <?php
if ($_stack[0]['loginInfo']['group'] > "1"){
?>
                  <a onclick="deleteTopic(<?php
echo $_obj['tid'];
?>
,0);" id="deleteTopic-<?php
echo $_obj['tid'];
?>
"><span class="web-icon icon-trash"></span>删除</a>
                  <?php
}
?>
                </div>
                <a href="./?cid=<?php
echo $_obj['cid'];
?>
"><?php
echo $_obj['clubname'];
?>
</a>
                <span class="point">•</span>
                <a href="./t.php?id=<?php
echo $_obj['tid'];
?>
"><?php
echo $_obj['posttime'];
?>
</a>
                <span class="point">•</span>
                <a href="./t.php?id=<?php
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
          </li>
          <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <?php
} elseif ($_obj['postType'] == "reply"){
?>
          <?php
if (!empty($_obj['postList']['list'])){
if (!is_array($_obj['postList']['list']))
$_obj['postList']['list']=array(array('list'=>$_obj['postList']['list']));
$_tmp_arr_keys=array_keys($_obj['postList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['postList']['list']=array(0=>$_obj['postList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['postList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
          <li class="user-item" id="item-<?php
echo $_obj['pid'];
?>
">
            <div class="stream-content">
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
                  <a onclick="favReply(<?php
echo $_obj['pid'];
?>
);" id="favReply-<?php
echo $_obj['pid'];
?>
"><span class="web-icon icon-fav"></span>收藏</a>
                  <?php
if ($_stack[0]['loginInfo']['group'] > "1"){
?>
                  <a onclick="deleteReply(<?php
echo $_obj['pid'];
?>
);" id="deleteReply-<?php
echo $_obj['pid'];
?>
"><span class="web-icon icon-trash"></span>删除</a>
                  <?php
}
?>
                </div>
                <a href="./t.php?id=<?php
echo $_obj['tid'];
?>
#reply<?php
echo $_obj['pid'];
?>
"><?php
echo $_obj['posttime'];
?>
</a>
              </div>
            </div>
          </li>
          <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <?php
} elseif ($_obj['postType'] == "favorite"){
?>
          <?php
if (!empty($_obj['postList']['list'])){
if (!is_array($_obj['postList']['list']))
$_obj['postList']['list']=array(array('list'=>$_obj['postList']['list']));
$_tmp_arr_keys=array_keys($_obj['postList']['list']);
if ($_tmp_arr_keys[0]!='0')
$_obj['postList']['list']=array(0=>$_obj['postList']['list']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['postList']['list'] as $rowcnt=>$v) {
if (is_array($v)) $list=$v; else $list=array();
$list['ROWVAL']=$v;
$list['ROWCNT']=$rowcnt;
$list['ROWBIT']=$rowcnt%2;
$_obj=&$list;
?>
          <li class="user-item" id="item-<?php
echo $_obj['tid'];
?>
">
            <div class="stream-content">
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
                  <?php
if ($_obj['pid'] == "0"){
?>
                  <a onclick="unFavTopic(<?php
echo $_obj['tid'];
?>
);" id="favTopic-<?php
echo $_obj['tid'];
?>
" class="favorite"><span class="web-icon icon-fav"></span>已收藏</a>
                  <?php
} else {
?>
                  <a onclick="unFavReply(<?php
echo $_obj['pid'];
?>
);" id="favReply-<?php
echo $_obj['pid'];
?>
" class="favorite"><span class="web-icon icon-fav"></span>已收藏</a>
                  <?php
}
?>
                </div>
                <a href="user.php?id=<?php
echo $_obj['uid'];
?>
"><?php
echo $_obj['nickname'];
?>
</a>
                <span class="point">发布于</span>
                <a href="./t.php?id=<?php
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
</a>
              </div>
            </div>
          </li>
          <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        <?php
}
?>
      </ol>
      <div class="stream-footer">
        <div class="stream-end-inner">
          <?php
if ($_obj['postList']['page']['Total'] > "1"){
?>
            <?php
if ($_obj['postList']['page']['Prev'] != ""){
?>
            <a href="<?php
echo $_obj['postList']['page']['Prefix'];
?>
<?php
echo $_obj['postList']['page']['Prev'];
?>
">上一页</a>
            <?php
if ($_obj['postList']['page']['Next'] != ""){
?>
            <span class="pagination"><?php
echo $_obj['postList']['page']['Current'];
?>
 / <?php
echo $_obj['postList']['page']['Total'];
?>
</span>
            <?php
}
?>
            <?php
}
?>
            <?php
if ($_obj['postList']['page']['Next'] != ""){
?>
            <a href="<?php
echo $_obj['postList']['page']['Prefix'];
?>
<?php
echo $_obj['postList']['page']['Next'];
?>
">下一页</a>
            <?php
}
?>
          <?php
} else {
?>
            <span class="pagination"><?php
if ($_obj['postList']['count'] > "0"){
?>已加载全部数据<?php
} else {
?>暂无数据<?php
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
  <div class="hidden">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F12c7ebc0d5e268e34feb51b6c41feead' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>