<?php /* Smarty version Smarty-3.1.7, created on 2014-06-04 10:31:13
         compiled from "./templates/notbinded.htm" */ ?>
<?php /*%%SmartyHeaderCode:35181500053758201363fb3-27428527%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1dbead45c7bec28fedaa87a7144e714b38ac51ff' => 
    array (
      0 => './templates/notbinded.htm',
      1 => 1401849070,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35181500053758201363fb3-27428527',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_537582013a961',
  'variables' => 
  array (
    'bind' => 0,
    'redirectUrl' => 0,
    'openid' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537582013a961')) {function content_537582013a961($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("library/page_header.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="gheader">
  <div class="gtitle">操作提示</div>
</div>

<div class="gcontent">
<div class="gbox">
<div class="gmain">

<div class="cardexplain">
<?php if ($_smarty_tpl->tpl_vars['bind']->value=='community'){?>
<div class="clear height10"></div>
亲，您还未绑定小区哦，请先绑定小区！
<div class="clear height10"></div>
<a href="bind.html?redirectUrl=<?php echo $_smarty_tpl->tpl_vars['redirectUrl']->value;?>
"><div class="submit">立即绑定小区</div></a>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['bind']->value=='householder'){?>
<div class="clear height10"></div>
亲，您还未绑定物业哦，请先绑定物业！
<div class="clear height10"></div>
<a href="bind.html?act=householder&redirectUrl=<?php echo $_smarty_tpl->tpl_vars['redirectUrl']->value;?>
"><div class="submit">立即绑定物业</div></a>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['bind']->value=='notExistshouseholder'){?>
<div class="clear height10"></div>
Sorry，您所在小区还没有提供<span class="red">物管费、停车费、包裹快递</span>等物业信息的查询！
<div class="clear height5"></div>
请联系您的小区物业进行开通，谢谢！
<div class="clear height10"></div>
<a href="bbs/?openid=<?php echo $_smarty_tpl->tpl_vars['openid']->value;?>
"><div class="submit">去小区论坛逛逛</div></a>
<?php }?>
</div>

</div>
</div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("library/page_footer.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>