<?php
require_once("../init.php");

// // 统计代码行数
// $file_path =  Gc::$nav_root_path;
// $f_files   = UtilFileSystem::getAllFilesInDirectory( $file_path, "php" );
// // $f_files   = UtilFileSystem::getAllFilesInDirectory($file_path, array("js", "php", "tpl", "css", "json"));
// echo "共计:" . count($f_files) . "个源文件<br/>";
// $lines = 0;
// foreach ($f_files as $key => $f_file) {
//     $content = file_get_contents($f_file);
//     $content = preg_split("/[\r\n]/i", $content, 0, PREG_SPLIT_NO_EMPTY);
//     $lines  += count($content);
//     echo $f_file . "<br/>";
// }
// echo "共计:" . $lines . "行<br/>";

// // 多数据库源
// // 默认数据库源: Gc::$database_config
// $sql  = "select * from bb_core_blog;";
// $blog = sqlExecute($sql);
// print_pre($blog, true);
// // 修改数据库源后，之后调用框架数据库函数都使用新的数据源
// Gc::$database_config = array(
//     'host' => '127.0.0.1',
//     'port' => '3306',
//     'database' => 'bb',
//     'username' => 'root',
//     'password' => ''
// );
// ConfigDb::initGc();
// Manager_Db::newInstance()->resetDao();
// $new_sql = "select * from bb_user_user;";
// $user    = sqlExecute($new_sql);
// print_pre($user, true);

// // 显示超过限制数量的表, 默认值是0，显示所有的表
// $_GET["l"] = $_GET["l"]??0;
// require_once("tools/db/db_countrows.php");

// 导出数据到Excel
$response = Manager_Service::blogService()->exportBlog();
echo $response["data"];
echo "<script>window.open('" . $response["data"] . "');</script>";