<?php
require_once ("Gc.php");
require_once("core/include/common.php");
if(!contains($_SERVER['HTTP_HOST'],array("127.0.0.1","localhost"))){
    header("location:".Gc::$url_base."index.php?go=".Gc::$appName.".index.index");
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="zh_CN">
<meta name="author" content="skygreen">
<meta http-equiv="Reply-to" content="skygreen2001@gmail.com">
<?php require_once ("Gc.php");?>
<meta name="description" content="<?php echo Gc::$site_name?>">
<meta name="keywords" content="<?php echo Gc::$site_name?>">
<meta name="creation-date" content="12/01/2010">
<meta name="revisit-after" content="15 days">
<title><?php echo Gc::$site_name ?></title>

<style type="text/css">
body {
  font-size: 13px;
  font-family:'Microsoft YaHei',"微软雅黑",Arial, sans-serif,'Open Sans';
  margin:0;
  padding:0;
  border:0 none;
}
p {
  margin:5px;
}
.en{
  font-family:Arial,verdana,Geneva,Helvetica,sans-serif;
}
h1{
  margin: 120px auto 20px auto;
  font-size: 40px;
  font-weight: lighter;
}
a {
  cursor: pointer;
}
a:link {
  text-decoration: none;
}
a:visited {
  text-decoration: none;
}
a:hover {
  text-decoration: none;
}
.main {
  width : 100%;
  height: 100%;
  align : center;
}
.inbox {
  width: 360px;
  margin: 0 auto;
}
div.content-container{
  border: 2px solid #eee;
  font-size: 24px;
  width: 360px;
  height: 360px;
  border-radius: 100%;
}
div.content{
  position: absolute;
  top:42%;
  left:46%;
}
div.content a{
  color: #666;
}
div.content a:hover{
  color: #77cc6d;
}
.content-down{
  color: #999;
  width: 300px;
  text-align: center;
  margin: 10px auto 0px auto;
}

footer {
  position: absolute;
  bottom: 10px;
  width: 100%;
  text-align: center;
  margin: 10px auto 0px auto;
  color: #888;
}
footer a{
  color: #888;
}
footer a:hover{
  color: #77cc6d;
}
</style>
<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="main">
        <h1 align="center">欢迎来到 <span class="en"><?php echo Gc::$site_name ?></span> 框架</h1>
        <div class="inbox">
            <div class="content-container">
                <div class="content" align="center">
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=<?php echo Gc::$appName ?>.index.index">网站前台</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=admin.index.index">管理后台</a></p>
                    <p><a target="_blank" href="#">手机模版</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=model.index.index">通用模板</a></p>
                </div>
            </div>
        </div>
        <div class="content-down">
          <p><?php echo UtilDateTime::now() ?></p>
        </div>
        <footer><?php $help_url="http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html" ?>
            <a href="<?php echo Gc::$url_base?>tools/dev/index.php" target="_blank">工程重用</a>|<a href="<?php echo Gc::$url_base?>tools/tools/db/manual/db_normal.php" target="_blank">数据库说明书</a>|<a href="<?php echo Gc::$url_base?>tools/tools/autocode/db_onekey.php" target="_blank">一键生成</a>|<a href="<?php echo $help_url ?>" target="_blank">帮助</a>
        </footer>
    </div>
</body>
</html>
