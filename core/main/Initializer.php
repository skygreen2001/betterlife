<?php

/**
 * -----------| 初始化工作 |-----------
 * @category betterlife
 * @package core.main
 * @author skygreen <skygreen2001@gmail.com>
 */
class Initializer
{
    /**
     * 是否CGI
     * @var boolean
     * @static
     */
    public static $IS_CGI = false;
    /**
     * 是否WIN
     * @var boolean
     * @static
     */
    public static $IS_WIN = true;
    /**
     * 是否CLI
     * @var boolean
     * @static
     */
    public static $IS_CLI = false;
    /**
     * 框架核心文件夹所在的路径
     * @var string
     * @static
     */
    public static $NAV_CORE_PATH;
    /**
     * PHP文件名后缀
     * @var string
     */
    const SUFFIX_FILE_PHP = ".php";
    /**
     * 框架核心所有的对象类对象文件
     *
     * @var array 二维数组
     *
     * 一维:模块名称
     *
     * 二维: 对象类名称
     * @static
     */
    public static $coreFiles;
    /**
     * 开发者自定义所有类对象文件
     *
     * @var array 二维数组
     *
     * 一维: 模块名称
     *
     * 二维: 对象类名称
     *
     * @static
     */
    public static $moduleFiles;
    /**
     * 框架核心类之外可直接加载加载类的路径
     *
     * [在core之外的其他根路径下的路径需autoload自动认知的]
     *
     * @var array
     * @static
     */
    public static $core_include_paths = array();
    /**
     * 初始化错误，网站应用的设置路径有误提示信息。
     * @var string
     */
    const ERROR_INFO_INIT_DIRECTORY = "<table><tr><td>网站应用放置的目录路径设置不正确!</td></tr><tr><td>请查看全局变量设置文件Gc.php的\$nav_root_path和\$nav_framework_path配置!</td></tr></table>";
    /**
     * 自动加载指定的类对象
     * @param <type> $class_name
     */
    public static function autoload($class_name)
    {
        if (!empty(self::$coreFiles)) {
            foreach (self::$coreFiles as $coreFile) {
                if (array_key_exists($class_name, $coreFile)) {
                    class_exists($class_name) || require($coreFile[$class_name]);
                    return;
                }
            }
        } else {
            class_exists($class_name) || require($class_name . self::SUFFIX_FILE_PHP);
            return;
        }

        if (!empty(self::$moduleFiles)) {
            foreach (self::$moduleFiles as $moduleFile) {
                if (array_key_exists($class_name, $moduleFile)) {
                    //该语句比require_once快4倍
                    class_exists($class_name) || require($moduleFile[$class_name]);
//                    require_once($moduleFile[$class_name]);
                    return;
                }
            }
        }
        // 加载拥有命名空间路径的类名
        if (strpos($class_name, "\\") > 0) {
            $parts = explode('\\', $class_name);
            $path  = implode('/', $parts) . '.php';
            $path  = Gc::$nav_root_path . $path;
            // echo $path;
            if (file_exists($path)) {
                require_once($path);
            }
        }
    }

    /**
     * 初始化
     * @param array $moduleNames 模块名
     */
    public static function initialize()
    {
        if (!file_exists(Gc::$nav_root_path) || !file_exists(Gc::$nav_framework_path)) {
            die(self::ERROR_INFO_INIT_DIRECTORY);
        }

        if (Gc::$dev_profile_on) {
            require_once 'helper/Profiler.php';
            Profiler::init();
            set_include_path(get_include_path() . PATH_SEPARATOR . Gc::$nav_root_path . "core" . DS . "lang");
            Profiler::mark(Wl::LOG_INFO_PROFILE_RUN);
            Profiler::mark(Wl::LOG_INFO_PROFILE_INIT);
        }
        /**
         * 初始检验闸门
         */
        self::init();
        /**
         * 加载include_path路径
         */
        self::setIncludePath();
        /**
         * 加载通用函数库
         */
        self::loadCommonFunctionLibrarys();
        /**
         * 记录框架核心所有的对象类加载进来
         */
        self::recordCoreClasses();
        /**
         * 加载第三方库
         */
        self::loadLibrary();
        /**
         * 加载异常处理
         */
        self::loadException();
        /**
         * 加载自定义标签库
         */
        self::loadTaglibrary();
        /**
         * 记录所有开发者新开发功能模块下的文件路径
         */
        self::recordModuleClasses();
        /**
         * 其他需要初始化的工作
         */
        if (Gc::$dev_profile_on) {
            Profiler::unmark(Wl::LOG_INFO_PROFILE_INIT);
        }
    }

    /**
     * 加载自定义标签库
     */
    private static function loadTaglibrary()
    {
        $root_taglib   = "taglib";
        $file_tag_root = Gc::$nav_root_path . $root_taglib;

        if (is_dir($file_tag_root)) {
            $tmps = UtilFileSystem::getAllFilesInDirectory($file_tag_root);
            foreach ($tmps as $tmp) {
                require_once($tmp);
            }
        }
    }

    /**
     * 判断是否框架运行所需的模块和配置是否按要求设置
     */
    private static function isCanRun()
    {
        $is_not_run_betterlife = false;
        $phpver = strtolower(phpversion());
        $pi     = '7.0';
        if ($phpver >= 7) {
            $pos1 = strpos($phpver, ".");
            $pos2 = strpos($phpver, ".", $pos1 + strlen("."));
            $pi = substr($phpver, 0, $pos2);
        }
        //if (ini_get('register_globals') != 1) {echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>请在php.ini配置文件里设置register_globals = On<br/></p>";$is_not_run_betterlife=true;}
        //if (ini_get('allow_call_time_pass_reference') != 1) {echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>请在php.ini配置文件里设置allow_call_time_pass_reference = On<br/></p>";$is_not_run_betterlife=true;}
        if (!function_exists("imagecreate")) {
            echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装GD模块支持,名称:php_gd2,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-gd && sudo apt-get install php5-gd php$pi-gd<br/></p>";
            $is_not_run_betterlife = true;
        }
        if (!function_exists("curl_init")) {
            echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装Curl模块支持,名称:php_curl,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-curl && sudo apt-get install php5-curl php$pi-curl<br/></p>";
            $is_not_run_betterlife = true;
        }
        if (!function_exists("mb_check_encoding")) {
            echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装mbstring模块支持,名称:php_mbstring,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-mbstring php$pi-mbstring<br/></p>";
            $is_not_run_betterlife = true;
        }
        if (!function_exists('mysqli_prepare')) {
            echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装mysqli模块支持,名称:php_mysqli,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-mysqli php$pi-mysqli</p>";
            $is_not_run_betterlife = true;
        }
        if (!class_exists("SimpleXmlIterator")) {
            echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装simplexml模块支持,名称:php_xml,请加载<br/>Ubuntu服务器下执行: sudo apt install php-zip php-xml php$pi-zip php$pi-xml<br/></p>";
            $is_not_run_betterlife = true;
        }
        if (version_compare(phpversion(), 5.3, '<')) {
            if (!function_exists("mysqli_stmt_fetch")) {
                echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装mysqli模块支持,名称:php_mysqli,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-mysqli php5-mysqli<br/></p>";
                $is_not_run_betterlife = true;
            }
        }
        if ($is_not_run_betterlife) {
            die();
        }
    }

    /**
     *  初始化PHP版本校验
     */
    private static function init()
    {
        $root_core           = "core";
        self::$NAV_CORE_PATH = Gc::$nav_framework_path . $root_core . DS;
        //设置时区为中国时区
        date_default_timezone_set('PRC');
        //初始化PHP版本校验
        if (version_compare(phpversion(), 5, '<')) {
            header("HTTP/1.1 500 Server Error");
            echo "<h1>需要PHP 5</h1><h2>才能运行Betterlife框架, 请安装PHP 5.0或者更高的版本.</h2><p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>我们已经探测到您正在运行 PHP 版本号: <b>" . phpversion() . "</b>.  为了能正常运行 BetterLife,您的电脑上需要安装PHP 版本 5.1 或者更高的版本, 并且如果可能的话，我们推荐安装 PHP 5.2 或者更高的版本.</p>";
            die();
        }
        /**
         * 判断是否框架运行所需的模块和配置是否按要求设置
         */
        self::isCanRun();
        //定义异常报错信息
        if (Gc::$dev_debug_on) {
            ini_set('display_errors', 1);
            if (defined('E_DEPRECATED')) {
                if (Gc::$dev_php_debug_on) {
                    error_reporting(E_ALL ^ E_DEPRECATED);
                } else {
                    error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING ^ E_NOTICE);
                }
            } else {
                error_reporting(E_ALL ^ E_WARNING);
            }
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
        }
        self::$IS_CGI = substr(PHP_SAPI, 0, 3) == 'cgi' ? 1 : 0;
        self::$IS_WIN = strstr(PHP_OS, 'WIN') ? 1 : 0;
        self::$IS_CLI = PHP_SAPI == 'cli' ? 1 : 0;

        /**
         * class_alias需要PHP 版本>=5.3低于5.3需要以下方法方可以使用
         */
        if (!function_exists('class_alias')) {
            function class_alias($original, $alias)
            {
                eval('class ' . $alias . ' extends ' . $original . ' {}');
            }
        }

        if (function_exists('mb_http_output')) {
            mb_http_output(Gc::$encoding);
            mb_internal_encoding(Gc::$encoding);
        }
    }

    /**
     * 加载通用函数库
     */
    public static function loadCommonFunctionLibrarys()
    {
        $dir_include_function = self::$NAV_CORE_PATH . ConfigF::ROOT_INCLUDE_FUNCTION . DS;
        $files = UtilFileSystem::getAllFilesInDirectory($dir_include_function);
        if (!class_exists("PEAR")) {
            require_once("helper/PEAR.php");
        }
        if (ini_get('allow_call_time_pass_reference') === 1) {
            require_once("helper/PEAR5.php");
        }
        foreach ($files as $file) {
            require_once($file);
        }
    }

    /**
     * 将所有需要加载类和文件的路径放置在set_include_path内
     */
    public static function setIncludePath()
    {
        $core_util     = "util";
        $include_paths = array(
                self::$NAV_CORE_PATH,
                self::$NAV_CORE_PATH . $core_util,
                self::$NAV_CORE_PATH . "log",
                self::$NAV_CORE_PATH . $core_util . DS . "common",
        );
        set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $include_paths));
        $dirs_root     = UtilFileSystem::getAllDirsInDriectory(self::$NAV_CORE_PATH);
        $include_paths = $dirs_root;
        $module_Dir    = Gc::$nav_root_path;
        if (strlen(Gc::$module_root) > 0) {
            $module_Dir .= Gc::$module_root . DS;
        }
        foreach (Gc::$module_names as $moduleName) {
            $moduleDir = $module_Dir . $moduleName . DS;
            if (is_dir($moduleDir)) {
                $modulesubdir = array_keys(UtilFileSystem::getSubDirsInDirectory($moduleDir));
                /**
                 * view主要为html,javascript,css文件；因此应该排除在外
                 */
                $modulesubdir = array_diff($modulesubdir, Gc::$module_exclude_subpackage);
                foreach ($modulesubdir as $subdir) {
                    $modulePath = $moduleDir;
                    if (is_dir($moduleDir . $subdir)) {
                        $modulePath .= $subdir . DS;
                    }
                    $tmps = UtilFileSystem::getAllDirsInDriectory($modulePath);
                    $include_paths = array_merge($include_paths, $tmps);
                }
            } else {
                $module = basename($moduleDir);
                echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>加载应用模块路径不存在:" . $moduleDir . "<br/>请去除Gc.php文件里\$module_names的模块: " . $module . "。<br/>再重新运行!</p>";
                die();
            }
        }
        set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $include_paths));
    }

    /**
     * 记录框架核心所有的对象类
     */
    private static function recordCoreClasses()
    {
        $dirs_root = array(
            self::$NAV_CORE_PATH
        );

        foreach (self::$core_include_paths as $core_include_path) {
            $dirs_root[] = Gc::$nav_root_path . $core_include_path;
        }

        $files = new AppendIterator();
        foreach ($dirs_root as $dir) {
            $tmp = new ArrayObject(UtilFileSystem::getAllFilesInDirectory($dir));
            if (isset($tmp)) {
                $files->append($tmp->getIterator());
            }
        }

        foreach ($files as $file) {
            self::$coreFiles[ConfigF::ROOT_CORE][basename($file, self::SUFFIX_FILE_PHP)] = $file;
        }
    }

    /**
     * 记录所有开发者新开发功能模块下的文件路径
     */
    public static function recordModuleClasses()
    {
        $module_dir = Gc::$nav_root_path;
        if (strlen(Gc::$module_root) > 0) {
            $module_dir .= Gc::$module_root . DS;
        }
        foreach (Gc::$module_names as $moduleName) {
            $moduleDir = $module_dir . $moduleName . DS;
            load_module($moduleName, $moduleDir, Gc::$module_exclude_subpackage);
        }
    }

    /**
     * 加载异常处理
     */
    public static function loadException()
    {
        if (Gc::$dev_debug_on) {
            switch (ConfigException::EXCEPTION_WAY) {
                case ConfigException::EW_WHOOPS:
                    $run     = new Whoops\Run();
                    $handler = new Whoops\Handler\PrettyPageHandler();
                    // 设置异常网页的head title
                    $handler->setPageTitle(Gc::$site_name);
                    $handler->setApplicationPaths(["/"]);
                    // [Open Files In An Editor](https://github.com/filp/whoops/blob/master/docs/Open%20Files%20In%20An%20Editor.md)
                    $handler->setEditor(ConfigException::WHOOPS_EDITOR);
                    $run->pushHandler($handler);
                    $run->register();
                    break;
                case ConfigException::EW_SYMFONY:
                    // Symfony ErrorHandler Component
                    Symfony\Component\ErrorHandler\Debug::enable();
                    break;
                default:
                    /**
                     * 设置处理所有未捕获异常的用户定义函数
                     */
                    set_exception_handler('e_me');
                    break;
            }
        }
    }

    /**
     * 加载自定义模块库
     */
    public static function loadLibrary()
    {
        $dir_library = Gc::$nav_root_path . ConfigF::ROOT_INSTALL . DS . ConfigF::ROOT_LIBRARY . DS;
        /**
         * 加载自定义模块库
         */
        $classname = "Library_Loader";
        require_once($dir_library . $classname . self::SUFFIX_FILE_PHP);
        Library_Loader::load_run();
    }
}
