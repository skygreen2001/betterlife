<?php
/**
 +------------------------------------------------<br/>
 * 在这里实现第三方库的加载<br/>
 +------------------------------------------------
 * @category betterlife
 * @package library
 * @author skygreen
 */
class Library_Loader
{
    /**
     * 加载第三方库:Smarty,PHPExcel
     */
    public static function load_run()
    {
        self::load_phpexcel();
        self::load_template_smarty();
    }

    /**
     * 加载PHPExcel库<br/>
     * PHPExcel库：可解析Excel，PDF，CSV文件内容<br/>
     * PHPExcel解决内存占用过大问题-设置单元格对象缓存<br/>
     * @link http://luchuan.iteye.com/blog/985890
     */
    private static function load_phpexcel()
    {
        $dir_phpexcel   = Gc::$nav_root_path . "install" . DS . "vendor" . DS . "vendor" . DS . "phpoffice" . DS . "phpexcel" . DS . "classes" . DS;
        $class_phpexcel = "PHPExcel.php";
        include($dir_phpexcel . $class_phpexcel);
        include($dir_phpexcel . 'PHPExcel' . DS . 'Writer' . DS . 'Excel2007.php');
    }

    /**
     * PHPExcel自动加载对象
     */
    public static function load_phpexcel_autoload($pObjectName)
    {
        if ( ( class_exists($pObjectName) ) || ( strpos($pObjectName, 'PHPExcel') === False ) ) {
            return false;
        }
        $pObjectFilePath = PHPEXCEL_ROOT.str_replace('_', DS, $pObjectName). '.php';
        if ( ( file_exists($pObjectFilePath) === false ) || ( is_readable($pObjectFilePath) === false ) ) {
            return false;
        }
        require($pObjectFilePath);
        return true;
    }

    /**
     * 加载Smarty模板库
     * @see http://www.smarty.net/
     */
    private static function load_template_smarty()
    {
        $dir_smarty    = Gc::$nav_root_path . "install" . DS . "vendor" . DS . "vendor" . DS . "smarty" . DS .  "smarty" . DS . "libs". DS;
        $file_smarty   = "Smarty.class.php";
        $file_smartybc = "SmartyBC.class.php";
        include $dir_smarty . $file_smarty;
        include $dir_smarty . $file_smartybc;
    }
}
