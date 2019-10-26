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
        self::load_phpzip();
    }

    /**
     * 加载PHPExcel库<br/>
     * PHPExcel库：可解析Excel，PDF，CSV文件内容<br/>
     * PHPExcel解决内存占用过大问题-设置单元格对象缓存<br/>
     * @link http://luchuan.iteye.com/blog/985890
     */
    private static function load_phpexcel()
    {
        $dir_phpexcel   = Gc::$nav_root_path . "install" . DS . "vendor" . DS . "phpoffice" . DS . "phpexcel" . DS . "Classes" . DS;
        $class_phpexcel = "PHPExcel.php";
        if (file_exists($dir_phpexcel . $class_phpexcel)) {
            include($dir_phpexcel . $class_phpexcel);
            include($dir_phpexcel . 'PHPExcel' . DS . 'Writer' . DS . 'Excel2007.php');
        }
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
        $dir_smarty    = Gc::$nav_root_path . "install" . DS . "vendor" . DS . "smarty" . DS .  "smarty" . DS . "libs". DS;
        $file_smarty   = "Smarty.class.php";
        $file_smartybc = "SmartyBC.class.php";
        if (file_exists($dir_smarty . $file_smarty)) {
            include $dir_smarty . $file_smarty;
            include $dir_smarty . $file_smartybc;
        }
    }

    /**
     * 加载nelexa/zip库<br/>
     * 使用Zip方法<br/>
     * @link https://github.com/Ne-Lexa/php-zip
     */
    private static function load_phpzip()
    {
        $dir_phpzip   = Gc::$nav_root_path . "install" . DS . "vendor" . DS . "nelexa" . DS . "zip" . DS . "src" . DS;
        set_include_path(get_include_path() . PATH_SEPARATOR . $dir_phpzip);
    }
}
Library_Loader::load_run();
