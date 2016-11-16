<?php
/* Smarty version 3.1.31-dev/42, created on 2016-11-13 21:30:51
  from "/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/blog/display.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31-dev/42',
  'unifunc' => 'content_58286b0be07c43_83736999',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8345fd024d430a4ec58f818f6217075febb1b323' => 
    array (
      0 => '/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/blog/display.tpl',
      1 => 1478652972,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58286b0be07c43_83736999 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/Volumes/Macintosh HD 2/www/bb/vendor/smarty/smarty/libs/plugins/modifier.date_format.php';
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18983855958286b0bc75509_24085199', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['templateDir']->value)."/layout/normal/layout.tpl");
}
/* {block 'body'} */
class Block_18983855958286b0bc75509_24085199 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_18983855958286b0bc75509_24085199',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="contentBox">
        <b><my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.auth.logout">退出</my:a></b><br/><br/>
        <b>共计<?php echo $_smarty_tpl->tpl_vars['countBlogs']->value;?>
 篇博客</b>
        <?php if ($_smarty_tpl->tpl_vars['blogs']->value) {?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['blogs']->value, 'blog');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['blog']->value) {
?>
        <div id='blog<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
' class="block">
            <b><my:a href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.comment.comment&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'><?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_name'];?>
</my:a>
            <?php if ($_smarty_tpl->tpl_vars['blog']->value['canEdit']) {?>[<my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.write&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">改</my:a>]<?php }?>
            <?php if ($_smarty_tpl->tpl_vars['blog']->value['canDelete']) {?>[<my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.delete&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">删</my:a>]<?php }?>
            </b><br/>
            <?php echo nl2br($_smarty_tpl->tpl_vars['blog']->value['blog_content']);?>
<br/><br/>
            由 <?php echo $_smarty_tpl->tpl_vars['blog']->value['user']['username'];?>
 在 <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['blog']->value['commitTime'],'%Y-%m-%d %H:%M');?>
 发表<br/>
            评论数:<?php echo $_smarty_tpl->tpl_vars['viewObject']->value->count_comments($_smarty_tpl->tpl_vars['blog']->value['blog_id']);?>
<br/>
        </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
<br/>
        <my:page src='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.display' /><br/>
        <b><my:a href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.write&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'>新建博客</my:a></b><br/>
        <?php } else { ?>
        无博客，您是第一位!
        <?php }?>
    </div>
<?php
}
}
/* {/block 'body'} */
}
