<?php

header("Content-Type:text/html; charset=UTF-8");

define('__DIR__', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
// 框架中对于换行符的定义，HH是`换行`中文拼音huan hang的头字母缩写
define('HH', PHP_EOL);
// 框架中对于浏览器中网页换行的定义
define('BR', "<br/>");
// 本地服务器常用关键词
define('LS', array("127.0.0.1", "localhost", "192.168.", '.test'));

require_once 'Gc.php';//加载全局变量文件
require_once 'core/main/Initializer.php';

/**
 * 相当于__autoload加载方式
 * 但是当第三方如Flex调用时__autoload无法通过其ZendFrameWork加载模式；
 * 需要通过spl_autoload_register的方式进行加载,方能在调用的时候进行加载
 * @param string $class_name 类名
 */
function class_autoloader($class_name)
{
    Initializer::autoload($class_name);
}

//使用composer的自动加载[必须放在spl_autoload_register的前面]
$autoload_file = file_exists(Gc::$nav_root_path . "install/vendor/autoload.php") ? Gc::$nav_root_path . "install/vendor/autoload.php" : Gc::$nav_root_path . "vendor/autoload.php";
require_once $autoload_file;

spl_autoload_register("class_autoloader");

Initializer::initialize();
