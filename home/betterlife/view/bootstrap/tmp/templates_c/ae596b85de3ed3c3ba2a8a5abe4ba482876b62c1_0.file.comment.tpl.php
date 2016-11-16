<?php
/* Smarty version 3.1.31-dev/44, created on 2016-11-16 16:10:11
  from "/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/comment/comment.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31-dev/44',
  'unifunc' => 'content_582c1463adef25_74237501',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ae596b85de3ed3c3ba2a8a5abe4ba482876b62c1' => 
    array (
      0 => '/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/comment/comment.tpl',
      1 => 1478652972,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582c1463adef25_74237501 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/Volumes/Macintosh HD 2/www/bb/install/vendor/smarty/smarty/libs/plugins/modifier.date_format.php';
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1332216374582c1463a1def0_87951128', 'body');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['templateDir']->value)."/layout/normal/layout.tpl");
}
/* {block 'body'} */
class Block_1332216374582c1463a1def0_87951128 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_1332216374582c1463a1def0_87951128',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php if (isset($_smarty_tpl->tpl_vars['online_editor']->value)) {?>
        <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'KindEditor')) {?>
        <?php echo '<script'; ?>
>
        KindEditor.ready(function(KE) {
            KE.create('textarea[name="comment"]',<?php echo $_smarty_tpl->tpl_vars['keConfig']->value;?>
);
        });<?php echo '</script'; ?>
>
        <?php }?>
        <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'CKEditor')) {?>
        <?php echo $_smarty_tpl->tpl_vars['editorHtml']->value;?>

        <?php echo '<script'; ?>
>$(function(){
             ckeditor_replace_comment();});<?php echo '</script'; ?>
>
         <?php }?>
         <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'xhEditor')) {?>
        <?php echo '<script'; ?>
>$(function(){
            pageInit_comment();});<?php echo '</script'; ?>
>
        <?php }?>
    <?php }?>
    <div id="content" class="contentBox">
        <my:a href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.auth.logout'><b>退出</b></my:a><br/>
        <my:a href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.display&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'><b>博客列表</b></my:a>
        <div id='blog<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
' >
            <h3><?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_name'];?>
</h3>
            <p><?php echo nl2br($_smarty_tpl->tpl_vars['blog']->value['blog_content']);?>
</p>
            评论数:<?php echo $_smarty_tpl->tpl_vars['blog']->value['count_comments'];?>

        </div>
        <?php if (!isset($_GET['comment_id'])) {?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['blog']->value['comments'], 'comment');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->value) {
?>
            <div>
                <blockquote><?php echo nl2br($_smarty_tpl->tpl_vars['comment']->value['comment']);?>
 <br/>
                由 <?php echo $_smarty_tpl->tpl_vars['comment']->value['user']['username'];?>
 在 <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['commitTime'],'%Y-%m-%d %H:%M');?>
 提交<br/><span></span>
                </blockquote>
                <b>
                <?php if ($_smarty_tpl->tpl_vars['comment']->value['canEdit']) {?>[<my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.comment.comment&comment_id=<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
&blog_id=<?php echo $_smarty_tpl->tpl_vars['comment']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">改</my:a>]<?php }?>
                <?php if ($_smarty_tpl->tpl_vars['comment']->value['canDelete']) {?>[<my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.comment.delete&comment_id=<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
&blog_id=<?php echo $_smarty_tpl->tpl_vars['comment']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">删</my:a>]<?php }?>
                </b>
            </div>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        <?php }?>
        <?php if (!$_smarty_tpl->tpl_vars['blog']->value['canEdit']) {?>
        <div>
            <font color="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['color']->value)===null||$tmp==='' ? 'white' : $tmp);?>
"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['message']->value)===null||$tmp==='' ? '' : $tmp);?>
</font><br/>
            <?php if (!isset($_GET['comment_id'])) {?><h2>提交新评论</h2> <?php } else { ?><h2>修改评论</h2><?php }?>
            <form name="commentForm" method="post">
                我要发言: <br/><input type="hidden" name="blog_id" value="<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
"/>
                <textarea name="comment" id="comment" style="width:710px;height:300px;"><?php if (isset($_smarty_tpl->tpl_vars['comment_content']->value)) {
echo $_smarty_tpl->tpl_vars['comment_content']->value;
}?></textarea><br/>
                <input type="submit" value="提交" class="btnSubmit" /> | <input class="btnSubmit" onclick="location.href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.comment.comment&blog_id=<?php echo $_GET['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'" type="button" value="返回" />
            </form>
        </div>
        <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'UEditor')) {?>
        <?php echo '<script'; ?>
>pageInit_ue_comment();<?php echo '</script'; ?>
>
        <?php }?>
        <?php }?>
    </div>
<?php
}
}
/* {/block 'body'} */
}
