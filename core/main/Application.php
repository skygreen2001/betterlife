<?php

use Symfony\Component\ErrorHandler\Debug;

/**
 * -----------| BetterLife框架应用开始 |-----------
 * @category Betterlife
 * @package core.main
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class Application
{
    /**
     * 其$key定义参考:
     *
     *      1. Gc.php文件内的静态变量名
     *      2. core/config/config/ConfigDb.php内的静态变量名
     * @param mixed $environment 应用运行具体环境变量
     * @return Application
     */
    public function __construct($environment = null)
    {
        self::init($environment);
    }

    /**
     * 其$key定义参考:
     *
     *      1. Gc.php文件内的静态变量名
     *      2. core/config/config/ConfigDb.php内的静态变量名
     * @param mixed $environment 应用运行具体环境变量
     * @return Application
     */
    public static function init($environment = null)
    {
        require_once("Gc.php");

        if (!empty($environment)) {
            foreach ($environment as $key => $value) {
                if (isset(Gc::$$key)) {
                    Gc::$$key = $value;
                }
            }
        }

        require_once("init.php");

        if (!empty($environment)) {
            foreach ($environment as $key => $value) {
                if (isset(ConfigDb::$$key)) {
                    ConfigDb::$$key = $value;
                }
            }
        }
    }

    public function run()
    {
        header("Content-Type:text/html; charset=\"" . Gc::$encoding . "\"");
        $router = new Router();
        DebugMe::start();
        Dispatcher::dispatch($router);
        ob_end_flush();
        $router = null;
        DebugMe::end();
        LogMe::showLogs();
        e_view();//Debug模式下打印异常
    }
}
