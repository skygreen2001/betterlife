<?php

/**
 * -----------| View Egine |-----------
 *
 * 用于Template Engine
 *
 * 方便开发者在controller里通过$this->view->set(varname, value)控制
 *
 * 以便在显示层页面里任意访问使用变量varname
 *
 * @category Betterlife
 * @package core.main
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class View
{
    /**
     * 显示层文件所在目录名称
     */
    const VIEW_DIR_VIEW = "view";
    /**
     * 模板模式: 无
     */
    const TEMPLATE_MODE_NONE        = 0;
    /**
     * 模板模式: Smarty
     *
     * 默认已安装
     */
    const TEMPLATE_MODE_SMARTY      = 1;
    /**
     * 模板模式: Twig
     *
     * 需在 composer.json 配置安装
     */
    const TEMPLATE_MODE_TWIG        = 2;

    /**
     * 变量
     * @var array||object
     */
    private $vars = array();
    /**
     * 显示页面上使用的变量存储对象
     *
     * 目前模版是
     *
     *     Smarty:TEMPLATE_MODE_SMARTY
     */
    private $viewObject;
    /**
     * 访问应用名
     * @var string
     */
    private $moduleName;
    /**
     * 模板对象本身
     */
    private $template;
    /**
     * 模板模式
     */
    private $templateMode;
    /**
     * @var string 模板规范要求所在的目录
     */
    private $template_dir;
    /**
     * @var string 模板文件规范要求的文件后缀名称
     */
    private $template_suffix_name;
    /**
     * 指定页面目录路径
     * @var string
     */
    private $atPageDir;
    /**
     * 指定页面文件, 默认为action的方法名
     * @var string
     */
    private $atPageFile;
    /**
     * @param array 显示层需要使用的全局变量
     */
    protected static $view_global = array();
    private function initViewGlobal()
    {
        self::$view_global = array(
            "is_dev"        => Gc::$dev_debug_on,
            "app_name"      => Gc::$appName,
            "encoding"      => Gc::$encoding,
            "url_base"      => Gc::$url_base,
            "site_name"     => Gc::$site_name,
            "template_url"  => $this->templateUrlDir(),
            "upload_url"    => Gc::$upload_url,
            "uploadImg_url" => Gc::$upload_url . "images/",
            "template_dir"  => Gc::$nav_root_path . $this->getTemplateViewDir($this->moduleName)
        );

        if (contains($_SERVER['HTTP_HOST'], LS)) {
            self::$view_global["is_dev"] = true;
        }
    }
    /**
    * @param mixed $moduleName 访问应用名
    * @param mixed $templatefile 模板文件
    * @return View
    */
    public function __construct($moduleName, $templatefile = null)
    {
        $this->moduleName = $moduleName;
        $this->initViewGlobal();
        if (isset(Gc::$template_mode_every) && array_key_exists($this->moduleName, Gc::$template_mode_every)) {
            $this->initTemplate(Gc::$template_mode_every[$this->moduleName], $templatefile);
        } else {
            $this->initTemplate(Gc::$template_mode, $templatefile);
        }
        if (!empty(self::$view_global)) {
            foreach (self::$view_global as $key => $value) {
                $this->templateSet($key, $value);
                if (is_array($this->vars)) {
                    $this->vars[$key] = $value;
                } else {
                    $this->vars->$key = $value;
                }
            }
        }
    }

    public function set($key, $value, $template_mode = null)
    {
        $this->templateSet($key, $value, $template_mode);
    }

    public function get($key)
    {
        if (is_array($this->vars)) {
            return $this->vars[$key];
        } else {
            return $this->vars->$key;
        }
    }

    /**
     * 获取指定页面文件目录
     * @return string
     */
    public function getAtPageDir()
    {
        return $this->atPageDir;
    }

    /**
     * 获取指定页面文件目录
     * @return
     */
    public function getAtPageFile()
    {
        return $this->atPageFile;
    }

    /**
     * 自定义模版文件所在目录路径
     *
     * 指定页面目录路径
     *
     * @param string $dirPath 模版文件所在模版目录下的相对路径
     * @param string $filename 文件名, 不需要文件名后缀名
     *                         如果没有传递该参数，则为`action`名称
     * @return void
     */
    public function atPage($dirPath, $filename = "")
    {
        $this->atPageDir  = $dirPath;
        $this->atPageFile = $filename;
    }

    /***********************************魔术方法**************************************************/
    /**
     * 说明: 若每个具体的实现类希望不想实现set, get方法；
     *
     *      则将该方法复制到每个具体继承他的对象类内。
     *
     * 可设定对象未定义的成员变量[但不建议这样做]
     *
     * 可无需定义get方法和set方法
     *
     * 类定义变量访问权限设定需要是pulbic
     *
     * @param string $method
     * @argu
     */
    public function __call($method, $arguments)
    {
        if (contain($method, "set")) {
            $property = substr($method, strlen("set"), strlen($method));
            $property = lcfirst($property);
            if (property_exists($this, $property)) {
                $this->$property = $arguments[0];
            } else {
                $this->set($property, $arguments[0]);
            }
        } elseif (contain($method, "get")) {
            $property = substr($method, strlen("get"), strlen($method));
            $property = lcfirst($property);
            if (is_array($this->vars)) {
                return $this->vars[$property];
            } elseif (is_object($this->vars)) {
                return $this->vars->$property;
            }
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            if (
                (!empty($property)) && ($property == 'viewObject') &&
                !empty($this->viewObject->js_ready) && array_key_exists('js_ready', $value)
            ) {
                $value->js_ready = $this->viewObject->js_ready . $value->js_ready;
            }
            if (
                (!empty($property)) && ($property == 'viewObject') &&
                !empty($this->viewObject->css_ready) && array_key_exists('css_ready', $value)
            ) {
                $value->css_ready = $this->viewObject->css_ready . $value->css_ready;
            }
            $this->$property = $value;
        } else {
            $this->set($property, $value);
        }
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            if (is_array($this->vars)) {
                return $this->vars[$property];
            } else {
                return $this->vars->$property;
            }
        }
    }

    public function getVars()
    {
        return $this->vars;
    }

    /**
     * 获取模板
     * @return <type> huoqu
     */
    public function template()
    {
        return $this->template;
    }

    /**
     * 获取当前模板模式
     */
    public function templateMode()
    {
        return $this->templateMode;
    }

    /**
     * @return string 模板文件所在的目录
     */
    public function templateDir()
    {
        return $this->template_dir;
    }

    /**
     * @return string 模板文件所在的目录
     */
    public function templateUrlDir()
    {
        return @Gc::$url_base . str_replace("\\", "/", $this->getTemplateViewDir());
    }

    /**
     * @return string 模板文件的文件后缀名称
     */
    public function templateSuffixName()
    {
        return $this->template_suffix_name;
    }

    /**
     * 当在同一种网站里使用多个模板的时候
     *
     * 通过本函数进行指定
     */
    public function setTemplate($template_mode, $moduleName, $templatefile = null)
    {
        $this->moduleName = $moduleName;
        if (empty($template_mode)) {
            $template_mode = Gc::$template_mode;
        }
        $this->initTemplate($template_mode, $templatefile = null);
    }

    /**
    * 获取模板文件完整的路径
    *
    */
    private function getTemplateViewDir()
    {
        $result = "";
        if (strlen(Gc::$module_root) > 0) {
            $result .= Gc::$module_root . DS;
        }
        $result .= $this->moduleName . DS . self::VIEW_DIR_VIEW . DS;
        if (isset(Gc::$self_theme_dir_every) && array_key_exists($this->moduleName, Gc::$self_theme_dir_every)) {
            $result .= Gc::$self_theme_dir_every[$this->moduleName] . DS;
        } else {
            $result .= Gc::$self_theme_dir . DS;
        }
        return $result;
    }

    /**
     * 初始化模板文件
     * @var string $template_mode 模板模式
     */
    private function initTemplate($template_mode, $templatefile = null)
    {
        if (empty($template_mode)) {
            $template_mode = Gc::$template_mode;
        }
        $this->template_dir = $this->getTemplateViewDir($this->moduleName) . ConfigF::VIEW_CORE . DS;
        $template_tmp_dir   = $this->getTemplateViewDir($this->moduleName) . "tmp" . DS;

        if (isset(Gc::$template_file_suffix_every) && array_key_exists($this->moduleName, Gc::$template_file_suffix_every)) {
            $this->template_suffix_name = Gc::$template_file_suffix_every[$this->moduleName];
        } else {
            $this->template_suffix_name = Gc::$template_file_suffix;
        }

        switch ($template_mode) {
            case self::TEMPLATE_MODE_SMARTY:
                $this->templateMode = self::TEMPLATE_MODE_SMARTY;
                if (!class_exists("Smarty")) {
                    die("<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装Smarty,请通知管理员在服务器上按: install/README.md  文件中说明执行。</p>");
                }
                $this->template = new Smarty();
                if (Smarty::SMARTY_VERSION >= 3.1 && class_exists("SmartyBC")) {
                    $this->template = new SmartyBC();
                }
                $this->template->template_dir  = Gc::$nav_root_path . $this->template_dir;
                $this->template->compile_dir   = Gc::$nav_root_path . $template_tmp_dir . "templates_c" . DS;
                $this->template->config_dir    = Gc::$nav_root_path . $template_tmp_dir . "configs" . DS;
                $this->template->cache_dir     = Gc::$nav_root_path . $template_tmp_dir . "cache" . DS;
                $this->template->compile_check = true;
                $this->template->allow_php_templates = true;
                // 开启自定义安全机制
                if (class_exists("Smarty_Security")) {
                    $my_security_policy = new Smarty_Security($this->template);
                    $my_security_policy->secure_dir[] = Gc::$nav_root_path . $this->getTemplateViewDir($this->moduleName);
                    $my_security_policy->allow_php_tag = true;
                    $my_security_policy->php_functions = array();
                    $my_security_policy->php_handling = Smarty::PHP_PASSTHRU;
                    $my_security_policy->php_modifier = array();
                    $my_security_policy->modifiers = array();
                    $this->template->enableSecurity($my_security_policy);
                }

                // $my_security_policy = new Smarty_Security($this->template);
                // $my_security_policy->allow_php_tag = true;
                // $this->template->enableSecurity($my_security_policy);
                $this->template->debugging = Gc::$dev_smarty_on;
                $this->template->force_compile = false;
                $this->template->caching = Gc::$is_online_optimize;
                $this->template->cache_lifetime = 86400;//缓存一周
                UtilFileSystem::createDir($this->template->compile_dir);
                system_dir_info($this->template->compile_dir);
                break;
            case self::TEMPLATE_MODE_TWIG:
                $this->templateMode = self::TEMPLATE_MODE_TWIG;
                $this->template->template_dir = Gc::$nav_root_path . $this->template_dir;
                $this->template->cache_dir = $template_tmp_dir . "cache" . DS;
                UtilFileSystem::createDir($this->template->cache_dir);
                system_dir_info($this->template->cache_dir);
                $twig_loader = new \Twig\Loader\FilesystemLoader(dirname($this->template->template_dir));
                $this->template = new \Twig\Environment($twig_loader, [
                    'cache' => $this->template->cache_dir,
                ]);
                $this->template->template_dir = Gc::$nav_root_path . $this->template_dir;
                $this->template->temp_dir = $template_tmp_dir . "temp" . DS;
                $this->template->cache_dir = $template_tmp_dir . "cache" . DS;
                break;
            default:
                $this->templateMode = self::TEMPLATE_MODE_NONE;
                break;
        }
    }

    /**
     * 设置模板认知的变量
     */
    public function templateSet($key, $value, $template_mode = null)
    {
        if (empty($template_mode)) {
            if (isset(Gc::$template_mode_every) && array_key_exists($this->moduleName, Gc::$template_mode_every)) {
                $template_mode = Gc::$template_mode_every[$this->moduleName];
            } else {
                $template_mode = Gc::$template_mode;
            }
        }
        switch ($template_mode) {
            case self::TEMPLATE_MODE_NONE:
                break;
            case self::TEMPLATE_MODE_TWIG:
                break;
            case self::TEMPLATE_MODE_SMARTY:
                $this->template->assign($key, $value);
                break;
        }
        if (is_array($this->vars)) {
            $this->vars[$key] = $value;
        } elseif (is_object($this->vars)) {
            $this->vars->$key = $value;
        }
    }

    /**
     * 渲染输出
     * @param string $templatefile 模板文件名
     */
    public function output($templatefile, $template_mode, $controller = null)
    {
        if (empty($template_mode)) {
            $template_mode = Gc::$template_mode;
        }
        $templateFilePath = $templatefile . $this->template_suffix_name;
        switch ($template_mode) {
            case self::TEMPLATE_MODE_SMARTY:
                if (!empty($this->viewObject)) {
                    $view_array = UtilObject::object_to_array($this->viewObject, $this->vars);
                    foreach ($view_array as $key => $value) {
                        if (!array_key_exists($key, self::$view_global)) {
                            $this->set($key, $value);
                        }
                    }
                } else {
                    $this->viewObject = new ViewObject();
                }
                $name_viewObject = ViewObject::get_Class();
                $name_viewObject = lcfirst($name_viewObject);
                $this->template->assignByRef($name_viewObject, $this->viewObject);
                $this->template->display($templateFilePath);
                break;
            case self::TEMPLATE_MODE_TWIG:
                if (!empty($this->viewObject)) {
                    $view_array = UtilObject::object_to_array($this->viewObject, $this->vars);
                    foreach ($view_array as $key => $value) {
                        if (!array_key_exists($key, self::$view_global)) {
                            $this->set($key, $value);
                        }
                    }
                    $name_viewObject = ViewObject::get_Class();
                    $name_viewObject = lcfirst($name_viewObject);
                    $this->set($name_viewObject, $this->viewObject);
                    // print_r($this->viewObject);
                }
                $tplFilePath = ConfigF::VIEW_CORE . DS . $templateFilePath;
                echo $this->template->render($tplFilePath, $this->vars);

                break;
            default:
                $viewvars = $this->getVars();
                extract($viewvars);
                include_once(Gc::$nav_root_path . $this->templateDir() . $templateFilePath);
                break;
        }
    }
}
