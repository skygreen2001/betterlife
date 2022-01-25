<?php

/**
 * -----------| 负责WEB URL的转发 |-----------
 * @category Betterlife
 * @package core.main
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class Dispatcher
{
    /**
     * 是否输出返回静态页面信息
     * @var bool
     */
    public static $isOutputStatic = false;
    /**
     * WEB URL的转发
     * @global Action $app
     * @param Router $router
     * @return void
     */
    public static function dispatch($router)
    {
        if (Gc::$dev_profile_on) {
            Profiler::mark(Wl::LOG_INFO_PROFILE_WEBURL);
        }
        $isValidRequet = false;
        $controller    = $router->getController();
        if ($controller == Router::URL_DEFAULT_CONTROLLER) {
            // include_once(Gc::$nav_root_path . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            // return;
            header("location:" . Gc::$url_base . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            die();
        }
        $moduleName = $router->getModule();
        if ($moduleName && array_key_exists($moduleName, Initializer::$moduleFiles)) {
            $moduleFile = Initializer::$moduleFiles[$moduleName];
        } else {
            // include_once(Gc::$nav_root_path . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            // return;
            header("location:" . Gc::$url_base . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            die();
        }
        $action_controller = ActionBasic::ROUTINE_CLASS_PREFIX . ucfirst($controller);
        if (array_key_exists($action_controller, $moduleFile)) {
            require_once($moduleFile[$action_controller]);
            /**
             * 当前运行的控制器Action Controller
             */
            $current_action = new $action_controller($moduleName);

            $view = self::modelBindView($moduleName, $router, $current_action);
            if ($current_action->isRedirected) {
                $isValidRequet = true;
            //break;
            } else {
                $output = self::output($router, $current_action);
                if (self::$isOutputStatic) {
                    $output = render_tag($output);
                    return $output;
                } else {
                    echo $output;
                }
                $isValidRequet = true;
            }
        } else {
            if (!is_dir($index_dir . "action") && file_exists($index_dir . "index.php")) {
                header("location:" . Gc::$url_base . Gc::$module_root . "/" . $moduleName . "/index.php");
                die();
            }

            include_once(Gc::$nav_root_path . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            return;
        }
        if (!$isValidRequet) {
            LogMe::record(Wl::ERROR_INFO_CONTROLLER_UNKNOWN);
        }
        if (Gc::$dev_profile_on) {
            Profiler::unmark(Wl::LOG_INFO_PROFILE_WEBURL);
        }
        if (Gc::$dev_profile_on) {
            Profiler::unmark(Wl::LOG_INFO_PROFILE_RUN);
            Profiler::show(true);
        }
    }

    /**
     * 将控制器与视图进行绑定
     */
    public static function modelBindView($moduleName, $router, &$current_action)
    {
        UnitTest::setUp();
        ob_start();
        $controller = $router->getController();
        $action     = $router->getAction();
        $extras     = $router->getExtras();
        $data       = $router->getData();
        if (method_exists($current_action, "setData")) {
            $current_action->setData($data);
        } else {
            die("请检查控制器定义类是否继承了Action!");
        }
        $current_action->setExtras($extras);
        /**
         * 将控制器与视图进行绑定
         */
        $templateFile = $controller . DS . $action;
        $view         = Loader::load(Loader::CLASS_VIEW, $moduleName, $templateFile);

        if (self::$isOutputStatic) {
            if (($view != null) && ($view->viewObject != null)) {
                $view->viewObject->css_ready = "";
                $view->viewObject->js_ready  = "";
            }
            UtilAjax::$JsLoaded = array();
            UtilCss::$CssLoaded = array();
        }
        $current_action->setView($view);
        ob_end_clean();
        if (method_exists($current_action, $action)) {
            if (method_exists($current_action, "beforeAction")) {
                $current_action->beforeAction();
            }
            $response = $current_action->$action();
            if (method_exists($current_action, "afterAction")) {
                $current_action->afterAction();
            }
            if (get_class($current_action) == "Action_Ajax") {
                // 原设计是MVC的方式，只在这个类下面放Ajax请求
            }
            // ajax请求 返回json数据对象
            if ($response && is_string($response)) {
                if (json_decode($response)) {
                    echo $response;
                    die();
                } else {
                    echo $response;
                    die();
                }
            }
            // ajax请求 返回数组
            if (is_array($response) || is_object($response)) {
                echo json_encode($response);
                die();
            }
        } else {
            include_once(Gc::$nav_root_path . Router::URL_DEFAULT_CONTROLLER . ConfigF::SUFFIX_FILE_PHP);
            return;
        }
        UnitTest::tearDown();
        return $view;
    }

    /**
     * 管理视图: 输出结果
     * @var View $view 视图
     */
    public static function output($router, $current_action)
    {
        ob_start();
        $view       = $current_action->getView();
        $controller = $router->getController();
        $action     = $router->getAction();
        if (!empty($view->getAtPageDir())) {
            $renderTemplateFile  = $view->getAtPageDir();
            $renderTemplateFile  = trim($renderTemplateFile, "\\/.");
            $renderTemplateFile  = str_replace([".", "\\", "/"], DS, $renderTemplateFile);
            $renderTemplateFile .= DS;
            if (!empty($view->getAtPageFile())) {
                $renderTemplateFile .= $view->getAtPageFile();
            } else {
                $renderTemplateFile .= $action;
            }
        } else {
            $templateFile    = $controller . DS . $action;//模板文件路径名称
            $controller_path = $router->getControllerPath();
            if (!empty($controller_path)) {
                if (endWith($controller_path, DS)) {
                    $templateFile = $controller_path . $templateFile;
                } else {
                    $templateFile = $controller_path . DS . $templateFile;
                }
            }
            $renderTemplateFile = $templateFile;
        }
        // die($renderTemplateFile);
        if (!file_exists(Gc::$nav_root_path . $view->templateDir() . $renderTemplateFile . $view->templateSuffixName())) {
            throw new Exception($view->template_dir . Wl::ERROR_INFO_VIEW_UNKNOWN . " '" . $renderTemplateFile . $view->templateSuffixName() . "'");
        }
        $view->output($renderTemplateFile, $view->templateMode(), $current_action);
        $output = ob_get_clean();
        return $output;
    }

    /**
     * URL重定向
     * @param string $url 跳转的URL路径
     * @param <type> $time 定时
     * @param <type> $msg 显示信息
     */
    public static function redirect($url, $time = 0, $msg = '')
    {
        //多行URL地址支持
        $url = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg)) {
            $msg =  Wl::INFO_REDIRECT_PART1 . $time . Wl::INFO_REDIRECT_PART2 . $url;
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header("Location: " . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo($msg);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
            exit($str);
        }
    }
}
