<?php
require_once("../../init.php");
$module_name = basename(dirname(__FILE__));
?>
<div style="margin:200px 0px 200px 320px;">
<h1>第一次使用通用模版说明</h1>
1.数据库设计并创建完成数据库表<br/><br/>
2.<a target="_blank" href="<?php echo Gc::$url_base?>tools/tools/autocode/db_onekey.php">一键生成代码</a><br/><br/>
3.从[ <?php echo Gc::$url_base?>model/<?php echo $module_name?> ] 复制代码到 [<?php echo Gc::$url_base . Gc::$module_root . DS . $module_name ?>]<br/><br/>
4.访问<span><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=model.index.index">通用模版</a></span><br/>
</div>
