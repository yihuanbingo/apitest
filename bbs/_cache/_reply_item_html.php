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
<li class="reply-item" id="item-<?php
echo $_obj['pid'];
?>
">
  <div class="stream-content">
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
    <small class="time"><?php
echo $_obj['posttime'];
?>
</small>
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
        <a onclick="replyAt('<?php
echo $_obj['nickname'];
?>
');"><span class="web-icon icon-reply"></span>回复</a>
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
        <a onclick="favReply(<?php
echo $_obj['pid'];
?>
);" id="favReply-<?php
echo $_obj['pid'];
?>
"><span class="web-icon icon-fav"></span>收藏</a>
      </div>
    </div>
  </div>
</li>
<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>