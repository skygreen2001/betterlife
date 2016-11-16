<?php
/* Smarty version 3.1.31-dev/44, created on 2016-11-16 16:10:21
  from "/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/blog/write.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31-dev/44',
  'unifunc' => 'content_582c146d4d0544_56912251',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '88abb0147e9e029c39f8ebdae0d6205422ff0bb7' => 
    array (
      0 => '/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/blog/write.tpl',
      1 => 1478652972,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582c146d4d0544_56912251 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1364228858582c146d49ffb6_27302993', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['templateDir']->value)."/layout/normal/layout.tpl");
}
/* {block 'body'} */
class Block_1364228858582c146d49ffb6_27302993 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_1364228858582c146d49ffb6_27302993',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'KindEditor')) {?>
    <?php echo '<script'; ?>
>
    KindEditor.ready(function(KE) {
        KE.create('textarea[name="blog_content"]',<?php echo $_smarty_tpl->tpl_vars['keConfig']->value;?>
);
    });<?php echo '</script'; ?>
>
    <?php }?>
    <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'CKEditor')) {?>
    <?php echo $_smarty_tpl->tpl_vars['editorHtml']->value;?>

    <?php echo '<script'; ?>
>$(function(){
         ckeditor_replace_blog_content();});<?php echo '</script'; ?>
>
     <?php }?>
     <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'xhEditor')) {?>
    <?php echo '<script'; ?>
>$(function(){
        pageInit_blog_content();});<?php echo '</script'; ?>
>
    <?php }?>

    <div class="contentBox" >
        <b><my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.auth.logout">退出</my:a></b><br/>
        <my:a href="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.blog.display&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">博客列表</my:a>
        <br/><font color="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
"><?php echo (($tmp = @nl2br($_smarty_tpl->tpl_vars['message']->value))===null||$tmp==='' ? '' : $tmp);?>
</font><br/>
        <form name="postForm" method="POST">
            博文名:<br/>
            <input type="text" class="inputNormal" style="width: 710px; margin-left: 0px;text-align: left;" name="blog_name" value="<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_name'];?>
"/><br/>
            内容: <br/>
            <textarea id="blog_content" name="blog_content" style="width:710px;height:300px;"><?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_content'];?>
</textarea><br/>
            <input type="submit" value="提交" class="btnSubmit" />
        </form>
    </div>
    <?php if (($_smarty_tpl->tpl_vars['online_editor']->value == 'UEditor')) {?>
    <?php echo '<script'; ?>
>pageInit_ue_blog_content();<?php echo '</script'; ?>
>
    <?php }
}
}
/* {/block 'body'} */
}
