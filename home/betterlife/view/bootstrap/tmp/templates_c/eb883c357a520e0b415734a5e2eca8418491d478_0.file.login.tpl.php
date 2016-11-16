<?php
/* Smarty version 3.1.31-dev/42, created on 2016-11-13 21:12:41
  from "/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/auth/login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31-dev/42',
  'unifunc' => 'content_582866c9db14d1_47375283',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eb883c357a520e0b415734a5e2eca8418491d478' => 
    array (
      0 => '/Volumes/Macintosh HD 2/www/bb/home/betterlife/view/bootstrap/core/auth/login.tpl',
      1 => 1479042749,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582866c9db14d1_47375283 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2108106155582866c9da4725_29764119', 'body');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['templateDir']->value)."/layout/normal/layout.tpl");
}
/* {block 'body'} */
class Block_2108106155582866c9da4725_29764119 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_2108106155582866c9da4725_29764119',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <form method="POST">
    <div class="col-lg-6" style="position: absolute;width:600px;height:300px;left:50%;top:300px;margin-left:-300px;margin-top:-150px;">
        <h2></h2>
        <div class="bs-component">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title"><span style="font-family: Arial"><?php echo $_smarty_tpl->tpl_vars['site_name']->value;?>
</span> 框架前台</h3>
                        </div>
                        <div class="modal-body" style="height:120px;"><nobr>
                           <label>用户名</label><input class="inputNormal" type="text" name="username" style="width:80%;" /><br/><br/>
                           <label>密&nbsp;码</label><input class="inputNormal" type="password" name="password" style="width:80%;" /><br/>
                           <br/><br/><font style="margin-left:80px;" color="red"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</font></nobr>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="Submit" value="登录" class="btnSubmit" />
                            <button type="button" class="btn btn-primary" style="width:100px;margin-right: 20px;" onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['url_base']->value;?>
index.php?go=<?php echo $_smarty_tpl->tpl_vars['appName']->value;?>
.auth.register'">注册</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div align="center">[测试帐户]用户名:admin,密码:admin<br/>[测试帐户]用户名:china,密码:iloveu</div>
    </div>
    </form>
<?php
}
}
/* {/block 'body'} */
}
