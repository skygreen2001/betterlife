<?php
/**
 * 在线编辑器的类型
 */
class EnumOnlineEditorType extends Enum
{
    /**
     * @link http://ckeditor.com/
     */
    const CKEDITOR = 1;
    /**
     * @link http://ueditor.baidu.com/
     */
    const UEDITOR  = 4;
}

/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
 * class_alias("Action","Controller");<br/>
 +----------------------------------------------
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class ActionBasic extends BBObject
{
    /**
     * 规范要求：所有控制器要求的前缀
     */
    const ROUTINE_CLASS_PREFIX = "Action_";
    /**
     * SEO：keywords
     */
    public $keywords;
    /**
     * SEO：description
     */
    public $description;
    /**
     * 在线编辑器,参考:EnumOnlineEditorType
     * 1.CKEDITOR
     * 4.UEDITOR
     * @var mixed
     */
    public $online_editor = EnumOnlineEditorType::UEDITOR;//CKEDITOR
    /**
     * 访问应用名
     * @var string
     */
    protected $modulename;
    /**
     * 单例实体数据模型
     * @var Model
     */
    protected $model;
    /**
     * 显示器
     * @var object
     */
    public $view;
    /**
     * 来自用户请求里的数据
     * @var array
     */
    protected $data;
    /**
     * 其他系统提供的信息
     * @var array
     */
    protected $extras;
    /**
     * 是否在请求内部重导向->跳转
     * @var bool
     */
    public $isRedirected = false;

    /**
     * 构造器
     * @param $moduleName 访问应用名
     */
    public function __construct($moduleName)
    {
        $this->modulename = $moduleName;
        $this->model      = Loader::load( Loader::CLASS_MODEL );
        $this->model->setAction( $this );
    }

    /**
     * 设置显示器
     * @param View $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * 获取显示器
     * @return View 显示器
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * 设置用户请求数据
     * @param array $data 用户请求数据
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * 获取用户请求数据
     * @return array 用户请求数据
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 加载通用的Css<br/>
     * 默认:当前模板目录下:resources/css/index.css<br/>
     */
    public function loadCss($defaultCssFile = "resources/css/index.css")
    {
        if ( contain( $defaultCssFile, "misc/js/ajax/" ) ) {
            $defaultCssFile = Gc::$url_base . $defaultCssFile;
        } else {
            $defaultCssFile = $this->view->template_url . $defaultCssFile;
        }
        $viewObject = $this->view->viewObject;
        if ( empty($viewObject) ) {
            $this->view->viewObject = new ViewObject();
        }
        if ( $this->view->viewObject ) {
            UtilCss::loadCssReady( $this->view->viewObject, $defaultCssFile, true );
        } else {
            UtilCss::loadCss( $defaultCssFile, true );
        }
    }

    /**
     * 加载通用的Javascript库<br/>
     * 默认:当前模板目录下:js/index.js<br/>
     * @param string $defaultJsFile 默认需加载JS文件
     */
    public function loadJs($defaultJsFile = "js/index.js")
    {
        if ( startWith($defaultJsFile, "misc/js/ajax/" ) ) {
            $defaultJsFile = Gc::$url_base . $defaultJsFile;
        } else {
            $defaultJsFile = $this->view->template_url . $defaultJsFile;
        }
        $viewObject = $this->view->viewObject;
        if ( empty($viewObject) ){
            $this->view->viewObject = new ViewObject();
        }
        if ( $this->view->viewObject ) {
            UtilJavascript::loadJsReady( $this->view->viewObject, $defaultJsFile, true );
        } else {
            UtilJavascript::loadJs( $defaultJsFile, true );
        }
    }

    /**
     * 查看用户请求数据里是否存在某参数
     * @param $param 参数
     */
    public function isDataHave($param)
    {
        if ( array_key_exists($param, $this->data) ) {
             return true;
        }
        return false;
    }

    /**
     * 设置其他系统提供的信息
     * @param array $extras 其他信息
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;
    }

    /**
     * 内部转向到指定网页地址
     * @param mixed $url URL完整路径包括querystring
     * @link http://localhost/betterlife/index.php?g=betterlife&m=blog&a=display&pageNo=8
     */
    public function redirect_url($url)
    {
        if ( contain( $url, "http://") ) {
            header("Location:" . $url);
        } else {
            header("Location:http://" . $url);
        }
    }

    /**
     * 内部转向到另一网页地址
     *
     * @param mixed $action
     * @param mixed $method
     * @param array|string $querystringparam
     * 示例：
     *     index.php?g=betterlife&m=blog&a=write&pageNo=8&userId=5
     *     $action：blog
     *     $method：write
     *     $querystring：pageNo=8&userId=5
     *                   array('pageNo'=>8,'userId'=>5)
     */
    public function go($action, $method, $querystring = "")
    {
        $this->redirect($action, $method, $querystring);
    }

    /**
     * 内部转向到另一网页地址
     *
     * @param mixed $action
     * @param mixed $method
     * @param array|string $querystringparam
     * 示例：
     *     index.php?g=betterlife&m=blog&a=write&pageNo=8&userId=5
     *     $action：blog
     *     $method：write
     *     $querystring：pageNo=8&userId=5
     *                   array('pageNo'=>8,'userId'=>5)
     */
    public function redirect($action, $method, $querystring = "")
    {
        $urlMode       = Gc::$url_model;
        $extraUrlInfo  = "";
        $CONNECTOR     = Router::URL_SLASH;
        $CONNECTOR_VAR = Router::URL_SLASH;
        if ( $urlMode == Router::URL_COMMON ) {
            $CONNECTOR     = Router::URL_EQUAL;
            $CONNECTOR_VAR = Router::URL_CONNECTOR;
        }
        if ( !empty($this->extras) ) {
            foreach ($this->extras as $key => $value) {
                $extraUrlInfo .= $key . $CONNECTOR . $value;
            }
        }

        if ( !empty($querystring) ) {
            if ( is_array($querystring) || is_a($querystring, "DataObjectArray") ) {
                $querystring_tmp = "";
                foreach ($querystring as $key => $value) {
                    if ($key == Router::VAR_DISPATCH) {
                         $querystring_tmp .= $key . "=" . $this->modulename . "." . $action . "." . $method . Router::URL_CONNECTOR;
                    } else {
                        if ( $value == "undefined" ) $value = 0;
                        if ( !(is_array($value)) ) {
                            $querystring_tmp .= $key . "=" . $value . Router::URL_CONNECTOR;
                        }
                    }
                }
                $querystring = $querystring_tmp;
                $querystring = substr($querystring, 0, strlen($querystring)-1);
            }
        }

        $Header_Location = "Location:";
        $moreinfo        = $extraUrlInfo . $querystring;
        if ( empty($moreinfo) ) {
            $CONNECTOR_LAST = "";
        } else {
            if ( ($urlMode == Router::URL_REWRITE) || ($urlMode == Router::URL_PATHINFO) ) {
                $CONNECTOR_LAST = $CONNECTOR;
            } else {
                $CONNECTOR_LAST = $CONNECTOR_VAR;
            }
        }
        if ( $urlMode == Router::URL_REWRITE ) {
             $querystring = str_replace(Router::URL_CONNECTOR, Router::URL_SLASH, $querystring);
             $querystring = str_replace(Router::URL_EQUAL, Router::URL_SLASH, $querystring);
             if ( Router::URL_PATHINFO_MODEL == Router::URL_PATHINFO_NORMAL ) {
                header($Header_Location . Gc::$url_base . Router::VAR_GROUP . $CONNECTOR . $this->modulename . $CONNECTOR . Router::VAR_MODULE . $CONNECTOR . $action . $CONNECTOR .
                    Router::VAR_ACTION . $CONNECTOR . $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
             }else{
                header($Header_Location . Gc::$url_base . $this->modulename . $CONNECTOR . $action . $CONNECTOR .
                    $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
             }
        } elseif ( $urlMode == Router::URL_PATHINFO ) {
            $querystring = str_replace(Router::URL_CONNECTOR, Router::URL_SLASH, $querystring);
            $querystring = str_replace(Router::URL_EQUAL, Router::URL_SLASH, $querystring);
            if ( Router::URL_PATHINFO_MODEL == Router::URL_PATHINFO_NORMAL ) {
                header($Header_Location . Gc::$url_base . Router::URL_INDEX . $CONNECTOR . Router::VAR_GROUP . $CONNECTOR . $this->modulename . $CONNECTOR . Router::VAR_MODULE . $CONNECTOR . $action . $CONNECTOR .
                        Router::VAR_ACTION . $CONNECTOR . $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
            } else {
                header($Header_Location . Gc::$url_base . Router::URL_INDEX . $CONNECTOR . $this->modulename . $CONNECTOR . $action . $CONNECTOR .
                        $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
            }
        } elseif ( $urlMode == Router::URL_COMMON ) {
            if ( !empty($_GET[Router::VAR_DISPATCH]) ) {
                header($Header_Location . Gc::$url_base . Router::URL_INDEX . Router::URL_QUESTION . Router::VAR_DISPATCH . $CONNECTOR . $this->modulename . Router::VAR_DISPATCH_DEPR . $action . Router::VAR_DISPATCH_DEPR .
                        $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
            } else {
                header($Header_Location . Gc::$url_base . Router::URL_INDEX . Router::URL_QUESTION . Router::VAR_GROUP . $CONNECTOR . $this->modulename . $CONNECTOR_VAR . Router::VAR_MODULE . $CONNECTOR . $action . $CONNECTOR_VAR .
                        Router::VAR_ACTION . $CONNECTOR . $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
            }
        }elseif ( $urlMode == Router::URL_COMPAT ) {
            header($Header_Location . Gc::$url_base . Router::URL_INDEX . Router::URL_QUESTION . Router::VAR_PATHINFO . Router::URL_EQUAL . $CONNECTOR_VAR . $this->modulename . $CONNECTOR_VAR . $action . $CONNECTOR_VAR .
                    $method . $CONNECTOR_LAST . $extraUrlInfo . $querystring);
        }
        $this->isRedirected = true;
    }

    /**
     * 加载在线编辑器
     * @param array|string $textarea_ids Input为Textarea的名称name[一个页面可以有多个Textarea]
     */
    public function load_onlineditor($textarea_ids = "content")
    {
        switch ($this->online_editor) {
            case EnumOnlineEditorType::UEDITOR:
                $viewObject = $this->view->viewObject;
                if ( empty($viewObject) ) {
                    $this->view->viewObject = new ViewObject();
                }
                UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.config.js" );
                if ( UtilAjax::$IsDebug ) {
                    UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.all.js" );
                    UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.parse.js" );
                } else {
                    UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.all.min.js" );
                    UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/ueditor.parse.min.js" );
                }
                UtilJavascript::loadJsReady( $this->view->viewObject, "misc/js/onlineditor/ueditor/lang/zh-cn/zh-cn.js" );

                if ( is_array($textarea_ids) && ( count($textarea_ids) > 0 ) ) {
                    for ($i = 0; $i < count($textarea_ids); $i++) {
                        UtilUeditor::loadJsFunction( $textarea_ids[$i], $this->view->viewObject, null );
                    }
                } else {
                    UtilUeditor::loadJsFunction( $textarea_ids, $this->view->viewObject, null );
                }
                $this->view->online_editor = "UEditor";
                break;
            case EnumOnlineEditorType::CKEDITOR:
                if ( is_array($textarea_ids) && ( count($textarea_ids) > 0) ) {
                    $this->view->editorHtml = UtilCKEeditor::loadReplace( $textarea_ids[0] );
                    for ($i = 1; $i < count($textarea_ids); $i++) {
                        $this->view->editorHtml .= UtilCKEeditor::loadReplace( $textarea_ids[$i], false );
                    }
                } else {
                    $this->view->editorHtml = UtilCKEeditor::loadReplace( $textarea_ids );
                }
                $this->view->online_editor = "CKEditor";
                break;
        }
    }

    /**
     * 上传多个图片文件
     * @param array $files 上传多个文件的对象
     * @param array $uploadFlag 上传标识,上传文件的input组件的名称
     * @param array $upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
     * @param array $defaultId 上传文件所在的目录标识，一般为类实例名称; 如果包含有 . 则为指定文件名, 文件名后缀名会自动修改为上传文件的后缀名.
     * @param array $isReturnAll 是否返回所有值，当多个图片文件上传，有的是空的，是否也返回空；保证传入和返回的数组数量相等
     * @return array 是否创建成功。
     * @Example
     *     - $this->$this->uploadImgs($_FILES, "icon_url", "icon_url", "blog");
     *       [说明] 生成的图片会是 images/blog/icon_url/201806011225.png  类似的路径
     *     - $this->$this->uploadImgs($_FILES, "icon_url", "subdir", "test.png");
     *       [说明] 生成的图片会是 images/subdir/test.png  类似的路径
     *             如果上传文件为jpg后缀名，则会是 images/subdir/test.jpg  类似的路径
     */
    public function uploadImgs($files, $uploadFlag, $upload_dir, $defaultId = "default", $file_permit_upload_size = 10, $isReturnAll = false)
    {
        $result = array();
        if ( !empty($files[$uploadFlag]) && !empty($files[$uploadFlag]["name"]) ){
            if ( ( is_array($files[$uploadFlag]["name"]) ) && count($files[$uploadFlag]["name"]) > 0 ) {
                /**
                 *  允许同名name:$uploadFlag的多个文件上传,其上传的文件格式如下
                 *  Array
                 *   (
                 *       [upload_single_img] => Array
                 *           (
                 *               [name] => 
                 *               [type] => 
                 *               [tmp_name] => 
                 *               [error] => 4
                 *               [size] => 0
                 *           )
                 *       [upload_multiple_img] => Array
                 *           (
                 *               [name] => Array
                 *                   (
                 *                       [0] => 1.jpg
                 *                       [1] => 2.jpg
                 *                       [2] => 3.png
                 *                   )
                 *               [type] => Array()
                 *               [tmp_name] => Array()
                 *               [error] => Array()
                 *               [size] => Array()
                 *           )
                 *   )
                 */
                $files_upload = $files[$uploadFlag];
                $countFiles   = count($files[$uploadFlag]["name"]);
                $isExistUpload = false;
                for ($i = 0; $i < $countFiles; $i++) { 
                    if ( !empty($files[$uploadFlag]["name"][$i]) ) {
                        $isExistUpload = true;
                        break;
                    }
                }
                if ( $isExistUpload ) {
                    $file_keys = array_keys($files_upload);
                    for ($i = 0; $i < $countFiles; $i++) { 
                        if ( !empty($files[$uploadFlag]["name"][$i]) ) {
                            $files_single = array();
                            $files_single[$uploadFlag] = array();
                            $file_single  = $files_single[$uploadFlag];
                            foreach ($file_keys as $key) {
                                $file_single[$key] = $files_upload[$key][$i];
                            }
                            $files_single[$uploadFlag] = $file_single;
                            $result_one = $this->uploadImg( $files_single, $uploadFlag, $upload_dir, $defaultId, $file_permit_upload_size );
                            if ( $result_one && ( $result_one['success'] == true ) ) {
                                $result["success"] = true;
                                $result['file_name'][] = $result_one['file_name'];
                            } else {
                                $result = $result_one;
                                break;
                            }
                        } else {
                            if ( $isReturnAll ) {
                                $result['file_name'][] = "";
                            }
                        }
                    }
                } else {
                    $result["success"] = true;
                }
            }
        }
        return $result;
    }
 
    /**
     * 上传图片文件
     * @param array  $files 上传的文件对象
     * @param string $uploadFlag 上传标识,上传文件的input组件的名称
     * @param string $upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
     * @param string $defaultId 上传文件所在的目录标识，一般为类实例名称; 如果包含有 . 则为指定文件名, 文件名后缀名会自动修改为上传文件的后缀名.
     * @param int    $file_permit_upload_size 允许上传的文件尺寸大小: 默认10M
     * @param boolean $is_permit_same_filename 是否允许用同一个名字
     * @return array 是否创建成功。
     * @Example
     *     - $this->$this->uploadImg($_FILES, "icon_url", "icon_url", "blog");
     *       [说明] 生成的图片会是 images/blog/icon_url/201806011225.png  类似的路径
     *     - $this->$this->uploadImg($_FILES, "icon_url", "subdir", "test.png");
     *       [说明] 生成的图片会是 images/subdir/test.png  类似的路径
     *             如果上传文件为jpg后缀名，则会是 images/subdir/test.jpg  类似的路径
     */
    public function uploadImg($files, $uploadFlag, $upload_dir, $defaultId = "default", $file_permit_upload_size = 10, $is_permit_same_filename = false)
    {
        $result = array();
        if ( !empty($files[$uploadFlag]) && !empty($files[$uploadFlag]["name"]) ){
            $path_r     = explode('.', $files[$uploadFlag]["name"]);
            $tmptail    = end($path_r);
            $upload_url = $upload_dir;
            if ( contain($defaultId, ".") ) {
                if ( !empty($upload_dir) ) $upload_dir .= DS;
                $defaultId  = substr($defaultId, 0, strpos($defaultId, "."));
                $uploadPath = Gc::$upload_path . "images" . DS . $upload_dir . $defaultId. "." . $tmptail;
                if ( !empty($upload_url) ) $upload_url .= "/";
                $file_name  = $upload_url . $defaultId . "." . $tmptail;
                $is_permit_same_filename = true;
            } else {
                $diffpart   = date("YmdHis") . UtilString::rand_string();
                if ( !empty($upload_dir) ) $upload_dir .= DS;
                $uploadPath = Gc::$upload_path . "images" . DS . $defaultId . DS . $upload_dir . $diffpart . "." . $tmptail;
                if ( !empty($upload_url) ) $upload_url .= "/";
                $file_name  = "$defaultId/" . $upload_url . "$diffpart.$tmptail";
            }
            // print_pre($file_name, true);
            $result = UtilFileSystem::uploadFile( $files, $uploadPath, $uploadFlag, $is_permit_same_filename, $file_permit_upload_size );
            if ( $result && ( $result['success'] == true ) ){
                $result['file_name'] = $file_name;
            } else {
                return $result;
            }
        }
        return $result;
    }

    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        $this->keywords    = Gc::$site_name;
        $this->description = Gc::$site_name;
        /**
         * 设定网站语言，最终需由用户设置
         */
        class_alias(ucfirst(Gc::$language), Config_C::WORLD_LANGUAGE);
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        $this->view->set("keywords", $this->keywords);
        $this->view->set("description", $this->description);
    }
}

?>
