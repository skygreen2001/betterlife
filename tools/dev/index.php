<?php
require_once ("../../init.php");
/**
 * 重用类型
 */
class EnumReusePjType extends Enum
{
    /**
     * 完整版【同现有版本一样】
     */
    const FULL    = 1;
    /**
     * 精简版【只包括框架核心-包括MVC,前后台】
     */
    const SIMPLE  = 2;
    /**
     * MINI版【只包括框架核心-只包括了DAO,不包括显示组件、Service层等】
     */
    const MINI    = 3;
}

/**
 * Web项目代码重用
 * 在项目开发中，往往商业模式是可以重用的
 * 只要在原有的代码基础上稍作修改即可，一般不需要高级开发者花费太多的时间
 * 在公司运作中，只需初级开发者找到文字修改或者换肤即可很快重用代码变身成新的项目
 * 本开发工具提供图像化界面方便开发者快速重用现有代码生成新的项目
 * 输入:
 *        项目路径|项目名称【中文-英文】|项目别名
 *        重用类型
 *            1.完整版【同现有版本一样】
 *            2.精简版【只包括框架核心-包括MVC,前后台】
 *            3.MINI版【只包括框架核心-只包括了DAO,不包括显示组件、Service层等】
 * 处理流程操作:
 *        1.复制整个项目到新的路径
 *        2.修改Gc.php相关配置
 *        3.修改Config_Db.php[数据库名称|数据库表名前缀]
 *        4.修改帮助地址
 *        5.修改应用文件夹名称
 *        6.重命名后台Action_Betterlife为新应用类
 *      精简版还执行了以下操作
 *            1.清除在大部分项目中不需要的目录
 *            2.清除在大部分项目中不需要的文件
 *            3.清除缓存相关的文件
 *            4.清除mysql|sqlite|postgres以外的其他数据库引擎
 *            5.清除common大部分工程无需的文件
 * @author skygreen2001@gmail.com
 */
class Project_Refactor
{
    /**
     * 重用类型
     */
    public static $reuse_type    = EnumReusePjType::FULL;
    /**
     * 保存新Web项目路径
     */
    public static $save_dir      = "";
    /**
     * 新Web项目名称【中文】
     */
    public static $pj_name_cn    = "";
    /**
     * 新Web项目名称【英文】
     */
    public static $pj_name_en    = "";
    /**
     * 新Web项目名称别名【最好两个字母,头字母大写】
     */
    public static $pj_name_alias = "";
    /**
     * 数据库名称
     */
    public static $db_name       = "";
    /**
     * 数据库表名前缀
     */
    public static $table_prefix  = "";
    /**
     * Git版本地址
     */
    public static $git_name      = "";
    /**
     * 需要忽略的目录【在大部分的项目中都不会用到】
     */
    public static $ignore_dir    = array(
        "docs",
        "log",
        "model",
        "upload",
    );
    /**
     * 需要忽略的文件【在大部分的项目中都不会用到】
     */
    public static $ignore_files  = array(
        ".DS_Store",
        // ".gitignore",
        ".project"
    );

    /**
     * 清除无关的目录
     */
    private static function IgnoreDir()
    {
        foreach (self::$ignore_dir as $ignore_dir) {
            $toDeleteDir = self::$save_dir.$ignore_dir;
            if ( is_dir($toDeleteDir) ) UtilFileSystem::deleteDir( $toDeleteDir );
            @mkdir($toDeleteDir);
        }

        if( is_dir(self::$save_dir.Gc::$module_root.DS."business") )
            UtilFileSystem::deleteDir( self::$save_dir.Gc::$module_root.DS."business" );
    }

    /**
     * 清除无关的文件
     */
    private static function IgnoreFiles()
    {
        foreach (self::$ignore_files as $ignore_file) {
            $toDeleteFile = self::$save_dir.$ignore_file;
            if ( file_exists($toDeleteFile) ) unlink($toDeleteFile);
        }
    }

    /**
     * 清除除Mysql以外的其他数据库引擎文件
     * 1.配置文件:config/db
     * 2.数据库引擎文件:core/db/
     * 3.数据库备份:db/
     */
    private static function IgnoreAllDbEngineExceptMysql()
    {
        $root_config = "config";
        //1.清除配置文件:config/db
        $ignore_config_db_dir = self::$save_dir.$root_config.DS."config".DS."db".DS;
        $toDeleteDir  = $ignore_config_db_dir."dal".DS;
        if( is_dir($toDeleteDir) ) UtilFileSystem::deleteDir( $toDeleteDir );
        $toDeleteFile = $ignore_config_db_dir."object".DS."Config_Mssql.php";
        if ( file_exists($toDeleteFile) ) unlink($toDeleteFile);
        $toDeleteFile = $ignore_config_db_dir."object".DS."Config_Odbc.php";
        if(file_exists($toDeleteFile))unlink($toDeleteFile);

        //2.数据库引擎文件:core/db/
        $root_core = "core";
        $ignore_core_db_dir=self::$save_dir . $root_core.DS."db".DS;
        $toDeleteDir=$ignore_core_db_dir."dal".DS;
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        $toDeleteDir=$ignore_core_db_dir."object".DS."odbc".DS;
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        $toDeleteDir=$ignore_core_db_dir."object".DS."sqlserver".DS;
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

        //3.数据库备份:db/
        $ignore_db_dirs=array(
            "microsoft access",
            "postgres",
            "sqlite",
            "sqlserver"
        );
        foreach ($ignore_db_dirs as $ignore_db_dir) {
            $toDeleteDir=self::$save_dir."db".DS.$ignore_db_dir;
            if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        }
    }

    /**
     * 清除common大部分工程无需的文件
     * 1.去除js框架
     */
    private static function IgnoreCommons()
    {
        $root_commons   = "misc";
        //1.去除js框架
        $ignore_js_dirs = array(
            "dojo",
            "ext4",
            "mootools",
            "prototype",
            "scriptaculous",
            "yui"
        );
        foreach ($ignore_js_dirs as $ignore_js_dir) {
            $toDeleteDir = self::$save_dir . $root_commons . DS . "js" . DS . "ajax" . DS . $ignore_js_dir;
            if ( is_dir($toDeleteDir) ) UtilFileSystem::deleteDir( $toDeleteDir );
        }
    }

    /**
     * 运行生成Web项目代码重用
     */
    public static function Run()
    {
        if ( isset($_REQUEST["save_dir"]) && !empty($_REQUEST["save_dir"]) ) self::$save_dir = $_REQUEST["save_dir"];
        if ( isset($_REQUEST["pj_name_cn"]) && !empty($_REQUEST["pj_name_cn"]) )
        {
            self::$pj_name_cn = $_REQUEST["pj_name_cn"];
        } else {
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称【中文】</font></div>");
        }
        if ( isset($_REQUEST["pj_name_en"]) && !empty($_REQUEST["pj_name_en"]) )
        {
            self::$pj_name_en = $_REQUEST["pj_name_en"];
        } else {
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称【英文】</font></div>");
        }
        if ( isset($_REQUEST["pj_name_alias"]) && !empty($_REQUEST["pj_name_alias"]) )
        {
            self::$pj_name_alias = $_REQUEST["pj_name_alias"];
        } else {
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称别名</font></div>");
        }
        if( isset($_REQUEST["dbname"]) && !empty($_REQUEST["dbname"]) )
        {
            self::$db_name = $_REQUEST["dbname"];
        } else {
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:数据库名称</font></div>");
        }

        if ( isset($_REQUEST["table_prefix"]) && !empty($_REQUEST["table_prefix"]) )
        {
            self::$table_prefix = $_REQUEST["table_prefix"];
        }

        if ( isset($_REQUEST["reuse_type"])&&!empty($_REQUEST["reuse_type"]) )
            self::$reuse_type   = $_REQUEST["reuse_type"];

        if ( isset($_REQUEST["git_name"])&&!empty($_REQUEST["git_name"]) )
            self::$git_name     = $_REQUEST["git_name"];

        $default_dir    = Gc::$nav_root_path;
        $domain_root    = str_replace(Gc::$appName . DS, "", $default_dir);
        $domain_root    = str_replace(Gc::$appName_alias . DS, "", $domain_root);
        $save_dir       = self::$save_dir;
        self::$save_dir = $domain_root . self::$save_dir . DS;

        if ( is_dir(self::$save_dir . "core" . DS) )
        {
            self::$save_dir = $save_dir;
            self::UserInput();
            die("<div align='center'><font color='red'>该目录已存在!为防止覆盖您现有的代码,请更名!</font></div>");
        }

        if ( self::$reuse_type == EnumReusePjType::MINI )
        {
            $include_dirs = array(
                "install",
                "core",
                "include",
                "tools"
            );

            UtilFileSystem::createDir( self::$save_dir );
            foreach ($include_dirs as $include_dir) {
                if( is_dir( Gc::$nav_root_path . $include_dir . DS ) ) {
                    smartCopy(Gc::$nav_root_path . $include_dir . DS, self::$save_dir . $include_dir . DS);
                }
            }

            $homeAppDir = self::$save_dir . Gc::$module_root . DS . self::$pj_name_en;
            UtilFileSystem::createDir( $homeAppDir . DS . "src" . DS . "domain" . DS );

            //修改Initializer.php初始化文件
            $init_file = self::$save_dir . "core" . DS . "main" . DS . "Initializer.php";
            $content   = file_get_contents($init_file);
            file_put_contents($init_file, $content);

            $include_files = array(
                ".htaccess",
                "favicon.ico",
                "Gc.php",
                "index.php",
                "init.php",
                "test.php",
                "welcome.php"
            );
            foreach ($include_files as $include_file) {
                copy(Gc::$nav_root_path . $include_file, self::$save_dir . $include_file);
            }

            //修改Gc.php配置文件
            $gc_file = self::$save_dir."Gc.php";
            $content = file_get_contents($gc_file);
            $content = preg_replace('/\''.Config_Db::$table_prefix.'\'/', "'".self::$table_prefix."'", $content);
            $content = str_replace("'admin',", "", $content);
            $content = str_replace("'model',", "", $content);
            $content = str_replace(Gc::$site_name, self::$pj_name_cn, $content);
            $content = str_replace(Gc::$appName, self::$pj_name_en, $content);
            $content = str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
            file_put_contents($gc_file, $content);

            //修改Config_Db.php配置文件
            $conf_db_file = self::$save_dir . "core" . DS . "config" . DS . "config" . DS . "Config_Db.php";
            $content      = file_get_contents($conf_db_file);
            $content      = preg_replace('/dbname(\s)*=(\s)*"(\s)*' . Config_Db::$dbname . '(\s)*"/', 'dbname = "' . self::$db_name . '"', $content);
            $content      = preg_replace('/table_prefix(\s)*=(\s)*"' . Config_Db::$table_prefix . '(\s)*"/', 'table_prefix = "' . self::$table_prefix . '"', $content);
            file_put_contents($conf_db_file, $content);

            //修改Welcome.php文件
            $welcome_file = self::$save_dir."welcome.php";
            $content      = file_get_contents($welcome_file);
            if ( !empty(self::$git_name) ) {
                $ctrl    = substr($content, 0, strpos($content, "<?php \$help_url = \"") + 17);
                $ctrr    = substr($content, strpos($content, "<?php \$help_url = \"") + 18);
                $ctrr    = substr($ctrr, strpos($ctrr, "\""));
                $content = $ctrl.self::$git_name . $ctrr;
            }
            $content = str_replace("网站后台", "", $content);
            // $content = str_replace("通用模版", "", $content);
            $content = str_replace("工程重用</a>|", "", $content);
            file_put_contents($welcome_file, $content);

            self::IgnoreAllDbEngineExceptMysql();

            //修改Config_AutoCode.php配置文件
            $config_autocode_file = self::$save_dir . "core" . DS . "config" . DS . "config" . DS . "Config_AutoCode.php";
            $content = file_get_contents($config_autocode_file);
            $content = str_replace("const ONLY_DOMAIN=false;", "const ONLY_DOMAIN=true;", $content);
            file_put_contents($config_autocode_file, $content);
        } else {
            //生成新项目目录
            UtilFileSystem::createDir( self::$save_dir );
            if ( !is_dir(self::$save_dir) ) {
                system_dir_info( self::$save_dir );
            }
            smartCopy(Gc::$nav_root_path, self::$save_dir);

            //修改Gc.php配置文件
            $gc_file = self::$save_dir . "Gc.php";
            $content = file_get_contents($gc_file);
            $content = preg_replace('/\'' . Config_Db::$table_prefix . '\'/', "'" . self::$table_prefix . "'", $content);
            $content = str_replace(Gc::$site_name, self::$pj_name_cn, $content);
            $content = str_replace(Gc::$appName, self::$pj_name_en, $content);
            $content = str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
            file_put_contents($gc_file, $content);

            //修改Config_Db.php配置文件
            $conf_db_file = self::$save_dir . "core" . DS . "config" . DS . "config" . DS . "Config_Db.php";
            $content      = file_get_contents($conf_db_file);
            $content      = preg_replace('/dbname(\s)*=(\s)*"(\s)*' . Config_Db::$dbname . '(\s)*"/', 'dbname = "'.self::$db_name.'"', $content);
            $content      = preg_replace('/table_prefix(\s)*=(\s)*"' . Config_Db::$table_prefix . '(\s)*"/', 'table_prefix = "' . self::$table_prefix . '"', $content);
            file_put_contents($conf_db_file, $content);

            //修改Welcome.php文件
            if ( !empty(self::$git_name) ) {
                $welcome_file = self::$save_dir . "welcome.php";
                $content = file_get_contents($welcome_file);
                if ( file_exists($welcome_file) ) {
                    $ctrl    = substr($content,0,strpos($content,"<?php \$help_url=\"")+17);
                    $ctrr    = substr($content,strpos($content,"<?php \$help_url=\"")+18);
                    $ctrr    = substr($ctrr,strpos($ctrr,"\""));
                    $content = $ctrl.self::$git_name.$ctrr;
                    if ( self::$reuse_type != EnumReusePjType::FULL ) {
                        $content = str_replace("通用模版", "", $content);
                    }
                    file_put_contents($welcome_file, $content);
                }
            }

            //修改.gitignore文件
            $gitignore_file = self::$save_dir . ".gitignore";
            if ( file_exists($gitignore_file) ) {
                $content = file_get_contents($gitignore_file);
                $content = str_replace(Gc::$appName, self::$pj_name_en, $content);
                file_put_contents($gitignore_file, $content);
            }

            //修改应用文件夹名称
            $old_name = self::$save_dir . Gc::$module_root . DS . Gc::$appName . DS;
            $new_name = self::$save_dir . Gc::$module_root . DS . self::$pj_name_en . DS;
            if ( is_dir($old_name) ) {
                $to_delet_subdirs = array(
                    "src" . DS . "domain" . DS,
                    "src" . DS . "services" . DS,
                    "view" . DS . "bootstrap" . DS . "js" . DS . "core" . DS,
                    "view" . DS . "default" . DS . "tmp" . DS . "templates_c" . DS,
                    "view" . DS . "bootstrap" . DS . "tmp" . DS . "templates_c" . DS
                );
                foreach ($to_delet_subdirs as $to_delet_subdir) {
                    $toDeleteDir = $old_name . $to_delet_subdir;
                    UtilFileSystem::deleteDir( $toDeleteDir );
                    UtilFileSystem::createDir( $toDeleteDir );
                }

                $del_model_action_files = UtilFileSystem::getAllFilesInDirectory( $old_name . DS . "action" . DS, array("php") );
                foreach ($del_model_action_files as $del_model_action_file) {
                  if ( !endWith($del_model_action_file, 'Action.php')
                    && !endWith($del_model_action_file, 'Action_Auth.php')
                    && !endWith($del_model_action_file, 'Action_Index.php')
                    && !endWith($del_model_action_file, 'Action_Ajax.php')
                  ) {
                    @unlink($del_model_action_file);
                  }
                }

                rename($old_name, $new_name);
            }

            self::IgnoreInAdmin();

            //修改前台的注释:* @category 应用名称
            $frontActionDir = self::$save_dir . Gc::$module_root . DS . self::$pj_name_en . DS . "action" . DS;
            $actionFiles    = UtilFileSystem::getAllFilesInDirectory( $frontActionDir, array("php") );

            foreach ($actionFiles as $actionFile) {
                $content = file_get_contents($actionFile);
                $content = str_replace("* @category " . Gc::$appName, "* @category " . self::$pj_name_en, $content);
                file_put_contents($actionFile, $content);
            }

            $frontSrcDir = self::$save_dir . Gc::$module_root . DS . self::$pj_name_en . DS . "src" . DS;
            $srcFiles    = UtilFileSystem::getAllFilesInDirectory( $frontSrcDir, array("php") );

            foreach ($srcFiles as $srcFile) {
                $content = file_get_contents($srcFile);
                $content = str_replace("* @category " . Gc::$appName, "* @category " . self::$pj_name_en, $content);
                file_put_contents($srcFile, $content);
            }

            self::IgnoreInCommon();
            switch (self::$reuse_type) {
                case EnumReusePjType::SIMPLE:
                    $toDeleteDir = self::$save_dir . Gc::$module_root . DS . "model";
                    $del_model_action_files = UtilFileSystem::getAllFilesInDirectory( $toDeleteDir . DS . "action" . DS, array("php") );
                    foreach ($del_model_action_files as $del_model_action_file) {
                      if ( !endWith($del_model_action_file, 'ActionModel.php') && !endWith($del_model_action_file, 'Action_Index.php') ) {
                        @unlink($del_model_action_file);
                      }
                    }
                    $del_model_view_dir = $toDeleteDir . DS . "view" . DS . "default" . DS . "core";
                    if( is_dir( $del_model_view_dir ) ) UtilFileSystem::deleteDir( $del_model_view_dir);
                    UtilFileSystem::createDir( $del_model_view_dir );

                    self::IgnoreAllDbEngineExceptMysql();
                    self::IgnoreCommons();
                    break;
                default:
                    //修改Action控制器类的注释:* @category 应用名称
                    $modelActionDir = self::$save_dir . Gc::$module_root . DS . "model" . DS . "action" . DS;
                    $actionFiles    = UtilFileSystem::getAllFilesInDirectory( $modelActionDir, array("php") );

                    foreach ($actionFiles as $actionFile) {
                        $content = file_get_contents($actionFile);
                        $content = str_replace("* @category " . Gc::$appName, "* @category " . self::$pj_name_en, $content);
                        file_put_contents($actionFile, $content);
                    }
                    break;
            }
        }

        //修改数据库脚本表前缀
        $db_bak_dir  = self::$save_dir . "install" . DS . "db" . DS ."mysql" . DS;
        $db_bak_file = $db_bak_dir . "db_" . Gc::$appName . ".sql";
        if ( file_exists($db_bak_file) ) {
            $content = file_get_contents($db_bak_file);
            $content = str_replace(Config_Db::$table_prefix, self::$table_prefix , $content);
            @unlink($db_bak_file);
            $db_bak_file = $db_bak_dir . "db_" . self::$pj_name_en . ".sql";
            file_put_contents($db_bak_file, $content);
            copy($db_bak_dir . "dbdesign_" . Gc::$appName . ".mwb", $db_bak_dir . "dbdesign_" . self::$pj_name_en . ".mwb");
            @unlink($db_bak_dir . "dbdesign_" . Gc::$appName . ".mwb");
        }

        // 删除原来的代码生成配置文件
        $del_autocode_config_xml_file = self::$save_dir . "tools" . DS . "tools" . DS . "autocode". DS ."autocode.config.xml";
        @unlink($del_autocode_config_xml_file);

        self::$save_dir = $save_dir;
        self::UserInput();
        $default_dir    = Gc::$url_base;
        $domain_url     = str_replace(Gc::$appName . "/", "", $default_dir);
        $domain_url     = str_replace(Gc::$appName_alias . "/", "", $domain_url);


        if ( contain( strtolower(php_uname()), "darwin") ) {
            $domain_url   = UtilNet::urlbase();
            $file_sub_dir = str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])) . DS;
            if ( contain( $file_sub_dir, "tools" . DS ) )
                $file_sub_dir = substr($file_sub_dir, 0, strpos($file_sub_dir, "tools" . DS));
            $domainSubDir = str_replace($_SERVER["DOCUMENT_ROOT"] . "/", "", $file_sub_dir);
            if ( !endwith($domain_url,$domainSubDir) ) $domain_url .= $domainSubDir;
            $domain_url   = str_replace(Gc::$appName . "/", "", $domain_url);
            $domain_url   = str_replace(Gc::$appName_alias . "/", "", $domain_url);
        }
        die("<div align='center'><font color='green'><a href='" . $domain_url . self::$pj_name_en . "/' target='_blank'>生成新Web项目成功！</a></font><br/><a href='" . $domain_url . self::$pj_name_en . "/' target='_blank'>新地址</a></div><br><br><br><br><br><br><br><br>");
    }


    /**
     * 多数情况下在admin模块里都会清除的内容
     */
    private static function IgnoreInAdmin()
    {
        $admin_root_path = self::$save_dir . Gc::$module_root . DS . "admin" . DS;
        $admin_view_path = $admin_root_path . "view" . DS . "default" . DS;

        //删除 admin 模块里的临时生成文件
        $toDeleteDir = $admin_view_path . "tmp" . DS . "templates_c" . DS;
        UtilFileSystem::deleteDir( $toDeleteDir );
        UtilFileSystem::createDir( $toDeleteDir );

        //删除Action业务逻辑文件
        $action_needs = array(
            "Action_Auth",
            "Action_Index",
            "ActionAdmin"
        );

        $admin_action_path = $admin_root_path . "action" . DS;
        $ignore_files      = UtilFileSystem::getFilesInDirectory( $admin_action_path );

        foreach ($ignore_files as $ignore_file) {
            $compare_name = basename($ignore_file, Config_F::SUFFIX_FILE_PHP);
            if ( !in_array($compare_name, $action_needs) ) unlink($ignore_file);
        }

        //删除 admin 模块里的业务页面文件
        $require_dirs = array(
            "auth",
            "index"
        );
        $ignore_dirs      = UtilFileSystem::getSubDirsInDirectory( $admin_view_path . "core" . DS );
        $ignore_dirs_keys = array();
        if ( $ignore_dirs) $ignore_dirs_keys = array_keys($ignore_dirs);

        foreach ($ignore_dirs_keys as $ignore_dir) {
            if ( !in_array($ignore_dir, $require_dirs) ) UtilFileSystem::deleteDir( $ignore_dirs[$ignore_dir] );
        }

        //删除 admin 模块里的业务js文件
        $toDeleteDir = $admin_view_path . "js" . DS . "core" . DS;
        UtilFileSystem::deleteDir( $toDeleteDir );
        UtilFileSystem::createDir( $toDeleteDir );
    }

    /**
     * 多数情况下都会清除的内容
     */
    private static function IgnoreInCommon()
    {
        self::IgnoreDir();
        self::IgnoreFiles();
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {
        $title="一键重用Web项目代码";
        if( empty($_REQUEST["save_dir"]) ){
            $pj_name_cn    = Gc::$site_name;
            $pj_name_en    = Gc::$appName;
            $pj_name_alias = Gc::$appName_alias;
            $default_dir   = Gc::$nav_root_path;
            $domain_root   = str_replace($pj_name_en . DS, "", $default_dir);
            $domain_root   = str_replace(Gc::$appName_alias . DS, "", $domain_root);
            $default_dir   = $pj_name_en;
            $dbname        = Config_Db::$dbname;
            $table_prefix  = Config_Db::$table_prefix;
            $git_name      = "https://www.gitbook.com/book/skygreen2001/betterlife/";
        }else{
            $reuse_type    = self::$reuse_type;
            $pj_name_cn    = self::$pj_name_cn;
            $pj_name_en    = self::$pj_name_en;
            $pj_name_alias = self::$pj_name_alias;
            $default_dir   = Gc::$nav_root_path;
            $domain_root   = str_replace(Gc::$appName . DS, "", $default_dir);
            $domain_root   = str_replace(Gc::$appName_alias . DS, "", $domain_root);
            $default_dir   = self::$save_dir;
            $dbname        = self::$db_name;
            $table_prefix  = self::$table_prefix;
            $git_name      = self::$git_name;
        }
        $inputArr=array(
            EnumReusePjType::SIMPLE => "精简版",
            EnumReusePjType::FULL   => "完整版",
            EnumReusePjType::MINI   => "MINI版"
        );

        $url_base = UtilNet::urlbase();
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\r\n
                <html lang='zh-CN' xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>\r\n";
        echo "<head>\r\n";
        echo "<link rel=\"icon\" href=\"{$url_base}favicon.ico\" mce_href=\"favicon.ico\" type=\"image/x-icon\">\r\n";
        echo UtilCss::form_css() . "\r\n";
        echo "";
        echo "</head>";
        echo "<body class='prj-onekey'>";
        echo "<h1 align='center'>$title</h1>\r\n";
        echo "<div align='center' height='450'>\r\n";
        echo "<form>\r\n";
        echo "    <div style='line-height:1.5em;'>\r\n";
        echo "        <label>Web项目名称【中文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' placeholder='Web项目名称【中文】' name='pj_name_cn' value='$pj_name_cn' id='pj_name_cn' /><br/>\r\n";
        echo "        <label>Web项目名称【英文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' placeholder='Web项目名称【英文】' name='pj_name_en' value='$pj_name_en' id='pj_name_en' oninput=\"document.getElementById('dbname').value=this.value;document.getElementById('save_dir').value=this.value;\" /><br/>\r\n";
        echo "        <label title='最好两个字母,头字母大写'>&nbsp;&nbsp;&nbsp;&nbsp;Web项目别名:</label><input title='最好两个字母,头字母大写' style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_alias' value='$pj_name_alias' id='pj_name_alias' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;输出Web项目路径:</label><label style='width:400px;text-align:left;'>$domain_root</label><br><input style='width:190px;text-align:left;padding-left:10px;margin-left:40px;' type='text' name='save_dir' value='$default_dir' id='save_dir' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;数据库名称:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='dbname' value='$dbname' id='dbname' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;数据库表名前缀:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='table_prefix' value='$table_prefix' id='table_prefix' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帮助地址:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='git_name' value='$git_name' id='git_name' /><br/>\r\n";
        $selectd_str = "";
        if (!empty($inputArr)){
            echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;重用类型:</label><select name='reuse_type'>\r\n";
            foreach ($inputArr as $key=>$value) {
                if ( isset($reuse_type) ) {
                    if ( $key == $reuse_type ) $selectd_str = " selected"; else $selectd_str = "";
                }
                echo "        <option value='$key'$selectd_str>$value</option>\r\n";
            }
            echo "        </select>\r\n";
        }
        echo "    </div>\r\n";
        echo "    <input class='btnSubmit' type='submit' value='生成' /><br/><br><br><br><br><br><br>\r\n";
        echo "</form>\r\n";
        echo "</div>\r\n";
        echo "</body>\r\n";
        echo "</html>";
    }
}

/**
 * Copy file or folder from source to destination, it can do
 * recursive copy as well and is very smart
 * It recursively creates the dest file or directory path if there weren't exists
 * Situtaions :
 * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination
 * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it
 * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest
 * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest
 * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name
 * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name
 * @todo
 *     - Should have rollback technique so it can undo the copy when it wasn't successful
 *  - Auto destination technique should be possible to turn off
 *  - Supporting callback function
 *  - May prevent some issues on shared enviroments : http://us3.php.net/umask
 * @param $source //file or folder
 * @param $dest ///file or folder
 * @param $options //folderPermission,filePermission
 * @return boolean
 */
function smartCopy($source, $dest, $options = array('folderPermission' => 0755, 'filePermission' => 0755))
{
    $result = false;

    if ( is_file($source) ) {
        if ( $dest[strlen($dest)-1] == '/' ) {
            if ( !file_exists($dest) ) {
                cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
            }
            $__dest = $dest . "/" . basename($source);
        } else {
            $__dest = $dest;
        }
        $dest_dir = dirname($__dest);
        UtilFileSystem::createDir( $dest_dir );
        $result = copy($source, $__dest);
        chmod($__dest, $options['filePermission']);

    } elseif ( is_dir($source) ) {
        if ( $dest[strlen($dest)-1]=='/' ) {
            if ( $source[strlen($source)-1]=='/' ) {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest = $dest . basename($source);
                @mkdir($dest);
                chmod($dest,$options['filePermission']);
            }
        } else {
            if ( $source[strlen($source)-1] == '/' ) {
                //Copy parent directory with new name and all its content
                @mkdir($dest, $options['folderPermission']);
                chmod($dest, $options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest, $options['folderPermission']);
                chmod($dest, $options['filePermission']);
            }
        }

        $dirHandle   = opendir($source);
        while ($file = readdir($dirHandle))
        {
            if ( $file != "." && $file != ".." && $file != ".git" && $file != ".svn" && $file != ".DS_Store" )
            {
                if ( !is_dir($source . "/" . $file) ) {
                    $__dest = $dest . "/" . $file;
                } else {
                    $__dest = $dest . "/" . $file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $result = smartCopy($source . "/" . $file, $__dest, $options);
            }
        }
        closedir($dirHandle);

    } else {
        $result = false;
    }
    return $result;
}


//控制器:运行Web项目代码重用
if ( isset($_REQUEST["save_dir"]) && !empty($_REQUEST["save_dir"]) ) {
    Project_Refactor::Run();
} else {
    Project_Refactor::UserInput();
}
