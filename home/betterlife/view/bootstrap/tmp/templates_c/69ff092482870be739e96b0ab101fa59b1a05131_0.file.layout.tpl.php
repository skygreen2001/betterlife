<?php
/* Smarty version 3.1.31-dev/44, created on 2016-11-16 16:10:01
  from "/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/layout/normal/layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31-dev/44',
  'unifunc' => 'content_582c14590bfd86_14897043',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '69ff092482870be739e96b0ab101fa59b1a05131' => 
    array (
      0 => '/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/layout/normal/layout.tpl',
      1 => 1479282910,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582c14590bfd86_14897043 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="zh">
  <head>
<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['templateDir']->value)."/layout/normal/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

    <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_url']->value;?>
resources/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['template_url']->value;?>
resources/css/bootswatch.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['template_url']->value;?>
resources/css/public.css" />
    <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
misc/js/ajax/jquery/jquery-1.6.1.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_url']->value;?>
js/public.js"><?php echo '</script'; ?>
>
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['viewObject']->value->css_ready)===null||$tmp==='' ? '' : $tmp);?>

    <!-- Latest compiled and minified CSS -->
<?php echo (($tmp = @$_smarty_tpl->tpl_vars['viewObject']->value->js_ready)===null||$tmp==='' ? '' : $tmp);?>

  </head>
  <?php 
     flush();
  ?>
  <body>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21963477582c14590bcbd7_86772527', 'body');
?>

  </body>
</html>
<?php }
/* {block 'body'} */
class Block_21963477582c14590bcbd7_86772527 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_21963477582c14590bcbd7_86772527',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'body'} */
}
