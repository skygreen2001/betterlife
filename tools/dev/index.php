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
     * 通用版【后台使用Jquery框架】
     */
    const LIKE    = 2;
    /**
     * 高级版【后台使用Extjs框架】
     */
    const HIGH    = 3;
    /**
     * 精简版【只包括框架核心-包括MVC,前后台】
     */
    const SIMPLE    = 4;
    /**
     * MINI版【只包括框架核心-只包括了DAO,不包括显示组件、Service层等】
     */
    const MINI    = 5;
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
 *            2.通用版【后台使用Jquery框架】
 *            3.高级版【后台使用Extjs框架】
 *            4.精简版【只包括框架核心-包括MVC,前后台】
 *            5.MINI版【只包括框架核心-只包括了DAO,不包括显示组件、Service层等】
 * 处理流程操作:
 *        1.复制整个项目到新的路径
 *        2.修改Gc.php相关配置
 *        3.修改Config_Db.php[数据库名称|数据库表名前缀]
 *        4.修改帮助地址
 *        5.修改应用文件夹名称
 *          6.重命名后台Action_Betterlife为新应用类
 *          7.替换Extjs的js文件里的命名空间
 *      精简版还执行了以下操作
 *            1.清除在大部分项目中不需要的目录
 *            2.清除在大部分项目中不需要的文件
 *            3.清除library下的不常用的库:
 *                adodb5|linq|mdb2|PHPUnit|yaml|template[EaseTemplate|SmartTemplate|TemplateLite]
 *            4.清除缓存相关的文件
 *              5.清除mysql|sqlite|postgres以外的其他数据库引擎
 *            6.清除module大部分工程无需的文件
 *            7.清除tools大部分工程无需的文件
 *            8.清除common大部分工程无需的文件
 *              9.清除util大部分工程无需的文件
 * @author skygreen2001@gmail.com
 */
class Project_Refactor
{
    /**
     * 重用类型
     */
    public static $reuse_type=EnumReusePjType::FULL;
    /**
     * 保存新Web项目路径
     */
    public static $save_dir="";
    /**
     * 新Web项目名称【中文】
     */
    public static $pj_name_cn="";
    /**
     * 新Web项目名称【英文】
     */
    public static $pj_name_en="";
    /**
     * 新Web项目名称别名【最好两个字母,头字母大写】
     */
    public static $pj_name_alias="";
    /**
     * 数据库名称
     */
    public static $db_name="";
    /**
     * 数据库表名前缀
     */
    public static $table_prefix="";
    /**
     * Git版本地址
     */
    public static $git_name="";
    /**
     * 需要忽略的目录【在大部分的项目中都不会用到】
     */
    public static $ignore_dir=array(
        "document",
        "log",
        "model",
        "upload",
    );
    /**
     * 需要忽略的文件【在大部分的项目中都不会用到】
     */
    public static $ignore_files=array(
        ".DS_Store",
        ".gitignore",
        ".project",
    );

    /**
     * 清除无关的目录
     */
    private static function IgnoreDir()
    {
        foreach (self::$ignore_dir as $ignore_dir) {
            $toDeleteDir=self::$save_dir.$ignore_dir;
            if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        }

        if(is_dir(self::$save_dir.Gc::$module_root.DS."business"))
            UtilFileSystem::deleteDir(self::$save_dir.Gc::$module_root.DS."business");
    }

    /**
     * 清除无关的文件
     */
    private static function IgnoreFiles()
    {
        foreach (self::$ignore_files as $ignore_file) {
            $toDeleteFile=self::$save_dir.$ignore_file;
            if(file_exists($toDeleteFile))unlink($toDeleteFile);
        }
    }

    /**
     * 清除除Mysql以外的其他数据库引擎文件
     * 1.配置文件:config/db
     * 2.数据库引擎文件:core/db/
     * 3.数据库备份:db/
     * 4.修改Manager_Db.php文件
     */
    private static function IgnoreAllDbEngineExceptMysql()
    {
        $root_config="config";
        //1.清除配置文件:config/db
        $ignore_config_db_dir=self::$save_dir.$root_config.DS."config".DS."db".DS;
        $toDeleteDir=$ignore_config_db_dir."dal".DS;
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        $toDeleteFile=$ignore_config_db_dir."object".DS."Config_Mssql.php";
        if(file_exists($toDeleteFile))unlink($toDeleteFile);
        $toDeleteFile=$ignore_config_db_dir."object".DS."Config_Odbc.php";
        if(file_exists($toDeleteFile))unlink($toDeleteFile);

        //2.数据库引擎文件:core/db/
        $root_core="core";
        $ignore_core_db_dir=self::$save_dir.$root_core.DS."db".DS;
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

        //4.修改Manager_Db.php文件
        $manager_db_content=<<<MANAGEDB
<?php
/**
 +---------------------------------<br/>
 * 数据库操作管理<br/>
 * 所有的数据库都通过这里进行访问<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db
 * @author skygreen <skygreen2001@gmail.com>
 */
class Manager_Db extends Manager {
    /**
     * @var IDao 默认Dao对象，采用默认配置
     */
    private \$dao_static;
    /**
     * @var mixed 实时指定的Dao或者Dal对象，实时注入配置
     */
    private \$dao_dynamic;
    /**
     * @var mixed 当前使用的Dao或者Dal对象
     */
    private \$currentdao;
    /**
     * @var IDbInfo 获取数据库表信息对象
     */
    private \$dbinfo_static;
    /**
     * @var Manager_Db 当前唯一实例化的Db管理类。
     */
    private static \$instance;

    /**
     * 构造器
     */
    private function __construct() {
    }

    public static function singleton() {
        return newInstance();
    }

    /**
     * 单例化
     * @return Manager_Db
     */
    public static function newInstance() {
        if (!isset(self::\$instance)) {
            \$c = __CLASS__;
            self::\$instance=new \$c();
        }
        return self::\$instance;
    }

    /**
     * 返回当前使用的Dao
     * @return mixed 当前使用的Dao
     */
    public function currentdao(){
        if (\$this->currentdao==null){
            \$this->dao();
        }
        return \$this->currentdao;
    }

    /**
     * 全局设定一个Dao对象；
     * 由开发者配置设定对象决定
     */
    public function dao() {
        switch (Config_Db::\$db) {
            case EnumDbSource::DB_MYSQL:
                switch (Config_Db::\$engine) {
                    case EnumDbEngine::ENGINE_OBJECT_MYSQL_MYSQLI:
                        if (\$this->dao_static==null)  \$this->dao_static=new Dao_MysqlI5();
                        break;
                    case EnumDbEngine::ENGINE_OBJECT_MYSQL_PHP:
                        if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
                        break;
                    default:
                    //默认：Config_Mysql::ENGINE_MYSQL_PHP
                        if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
                        break;
                }
                break;
            case EnumDbSource::DB_PGSQL:
                if (\$this->dao_static==null) \$this->dao_static=new Dao_Postgres();
                break;
            case EnumDbSource::DB_SQLITE2:
                if (\$this->dao_static==null) \$this->dao_static=new Dao_Sqlite2();
                break;
            case EnumDbSource::DB_SQLITE3:
                if (\$this->dao_static==null) \$this->dao_static=new Dao_Sqlite3();
                break;
            default:
            //默认：Config_Mysql::ENGINE_MYSQL_PHP
                if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
                break;
        }
        \$this->currentdao=\$this->dao_static;
        return \$this->dao_static;
    }

    /**
     * 获取数据库信息对对象
     * @param bool \$isUseDbInfoDatabase 是否使用获取数据库信息的数据库
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @return mixed 实时指定的Dbinfo对象
     */
    public function dbinfo(\$isUseDbInfoDatabase=false,\$forced=false,\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$engine=null) {
        if ((\$this->dbinfo_static==null)||\$forced) {
            switch (Config_Db::\$db) {
                case EnumDbSource::DB_MYSQL:
                    DbInfo_Mysql::\$isUseDbInfoDatabase=\$isUseDbInfoDatabase;
                    \$this->dbinfo_static=new DbInfo_Mysql(\$host,\$port,\$username,\$password,\$dbname,\$engine);
                    DbInfo_Mysql::\$isUseDbInfoDatabase=false;
                    break;
            }
        }
        return \$this->dbinfo_static;
    }

    /**
     * 使用PHP自带的MYSQL数据库访问方法函数
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @return mixed 实时指定的Dao对象
     */
    public function object_mysql_php5(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
        if ((\$this->dao_dynamic==null)||\$forced) {
            \$this->dao_dynamic=new Dao_Php5(\$host,\$port,\$username,\$password,\$dbname);
        }else if (!(\$this->dao_dynamic instanceof Dao_Php5)) {
            \$this->dao_dynamic=new Dao_Php5(\$host,\$port,\$username,\$password,\$dbname);
        }
        \$this->currentdao=\$this->dao_dynamic;
        return \$this->dao_dynamic;
    }

    /**
     * 使用经典的MYSQLI访问数据库方法函数
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @return mixed 实时指定的Dao对象
     */
    public function object_mysql_mysqli(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
        if ((\$this->dao_dynamic==null)||\$forced) {
            \$this->dao_dynamic=new Dao_MysqlI5(\$host,\$port,\$username,\$password,\$dbname);
        }else if (!(\$this->dao_dynamic instanceof Dao_MysqlI5)) {
            \$this->dao_dynamic=new Dao_MysqlI5(\$host,\$port,\$username,\$password,\$dbname);
        }
        \$this->currentdao=\$this->dao_dynamic;
        return \$this->dao_dynamic;
    }

    /**
     * 使用经典的Sqlite 2数据库方法函数
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @return mixed 实时指定的Dao对象
     */
    public function object_sqlite2(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
        if ((\$this->dao_dynamic==null)||\$forced) {
            \$this->dao_dynamic=new Dao_Sqlite2(\$host,\$port,\$username,\$password,\$dbname);
        }else if (!(\$this->dao_dynamic instanceof Dao_Sqlite2)) {
            \$this->dao_dynamic=new Dao_Sqlite2(\$host,\$port,\$username,\$password,\$dbname);
        }
        \$this->currentdao=\$this->dao_dynamic;
        return \$this->dao_dynamic;
    }

    /**
     * 使用经典的Sqlite 3数据库方法函数
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @return mixed 实时指定的Dao对象
     */
    public function object_sqlite3(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
        if ((\$this->dao_dynamic==null)||\$forced) {
            \$this->dao_dynamic=new Dao_Sqlite3(\$host,\$port,\$username,\$password,\$dbname);
        }else if (!(\$this->dao_dynamic instanceof Dao_Sqlite3)) {
            \$this->dao_dynamic=new Dao_Sqlite3(\$host,\$port,\$username,\$password,\$dbname);
        }
        \$this->currentdao=\$this->dao_dynamic;
        return \$this->dao_dynamic;
    }

    /**
     * 使用经典的Postgres 数据库方法函数
     * @param string \$host
     * @param string \$port
     * @param string \$username
     * @param string \$password
     * @param string \$dbname
     * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
     * @return mixed 实时指定的Dao对象
     */
    public function object_postgres(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
        if ((\$this->dao_dynamic==null)||\$forced) {
            \$this->dao_dynamic=new Dao_Postgres(\$host,\$port,\$username,\$password,\$dbname);
        }else if (!(\$this->dao_dynamic instanceof Dao_Postgres)) {
            \$this->dao_dynamic=new Dao_Postgres(\$host,\$port,\$username,\$password,\$dbname);
        }
        \$this->currentdao=\$this->dao_dynamic;
        return \$this->dao_dynamic;
    }
}
?>
MANAGEDB;
        $manager_db_file=$ignore_core_db_dir.DS."Manager_Db.php";
        file_put_contents($manager_db_file, $manager_db_content);
    }

    /**
     * 清除tools大部分工程无需的文件
     */
    private static function IgnoreTools()
    {
        $root_tools="tools";
        //3.数据库备份:db/
        $ignore_db_dirs=array(
            "probe",
            "timertask",
            "webservice"
        );
        foreach ($ignore_db_dirs as $ignore_db_dir) {
            $toDeleteDir=self::$save_dir.$root_tools.DS.$ignore_db_dir;
            if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        }
        $toDeleteDir=self::$save_dir.$root_tools.DS."dev".DS."install";
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        $toDeleteDir=self::$save_dir.$root_tools.DS."dev".DS."phpsetget";
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
    }

    /**
     * 清除common大部分工程无需的文件
     * 1.去除js框架
     * 2.去除在线编辑器
     * 3.去除ext-all-debug.js文件
     * 4.去除jquery下除1.7.1版本之外其他的文件
     */
    private static function IgnoreCommons()
    {
        $root_commons="common";
        //1.去除js框架
        $ignore_js_dirs=array(
            "dojo",
            "ext4",
            "mootools",
            "prototype",
            "scriptaculous",
            "yui"
        );
        foreach ($ignore_js_dirs as $ignore_js_dir) {
            $toDeleteDir=self::$save_dir.$root_commons.DS."js".DS."ajax".DS.$ignore_js_dir;
            if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        }
        //2.去除在线编辑器
        $ignore_oe_dirs=array(
            "ckeditor",
            "ckfinder",
            "kindeditor",
            "xheditor"
        );
        foreach ($ignore_oe_dirs as $ignore_oe_dir) {
            $toDeleteDir=self::$save_dir.$root_commons.DS."js".DS."onlineditor".DS.$ignore_oe_dir;
            if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
        }
        //3.去除ext-all-debug.js文件
        unlink(self::$save_dir.$root_commons.DS."js".DS."ajax".DS."ext".DS."ext-all-debug.js");
        //4.去除jquery下除1.7.1版本之外其他的文件
        $ignore_files=array(
            "jquery-1.11.0.js",
            "jquery-1.11.0.min.js",
            "jquery-1.4.4.js",
            "jquery-1.4.4.min.js",
            "jquery-1.6.1.js",
            "jquery-1.6.1.min.js",
            "jquery.js",
            "jquery.min.js",
            "microsoft-jquery-1.4.4.min.js",
        );
        foreach ($ignore_files as $ignore_file) {
            $toDeleteFile=self::$save_dir.$root_commons.DS."js".DS."ajax".DS."jquery".DS.$ignore_file;
            if(file_exists($toDeleteFile))unlink($toDeleteFile);
        }
    }

    /**
     * 清除util大部分工程无需的文件
     * 1.去除ucenter工具集
     * - 修改Action_Auth.php文件
     * 2.去除在线编辑器工具集
     * 3.去除js框架工具集
     */
    private static function IgnoreUtils()
    {
        $root_core="core";
        //1.去除ucenter工具集
        $toDeleteDir=self::$save_dir.$root_core.DS."util".DS."ucenter";
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

         // - 修改Action_Auth.php文件
        $action_auth_content=<<<AUTHCONTENT
<?php
/**
 +---------------------------------<br/>
 * 控制器:用户身份验证<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.front
 * @subpackage auth
 * @author skygreen <skygreen2001@gmail.com>
 */
class Action_Auth extends Action
{
    /**
     * 退出
     */
    public function logout()
    {
        HttpSession::remove("user_id");
        \$this->redirect("auth","login");
    }

    /**
     * 登录
     */
    public function login()
    {
        \$this->view->set("message","");
        if(HttpSession::isHave('user_id')) {
            \$this->redirect("blog","display");
        }else if (!empty(\$_POST)) {
            \$user = \$this->model->User;
            \$userdata = User::get_one(array("username"=>\$user->username,
                    "password"=>md5(\$user->getPassword())));
            if (empty(\$userdata)) {
                \$this->view->set("message","用户名或者密码错误");
            }else {
                HttpSession::set('user_id',\$userdata->user_id);
                \$this->redirect("blog","display");
            }
        }
    }

    /**
     * 注册
     */
    public function register()
    {
        if(!empty(\$_POST)) {
            \$user = \$this->model->User;
            \$userdata=User::get(array("username"=>\$user->username));
            if (empty(\$userdata)) {
                \$pass=\$user->getPassword();
                \$user->setPassword(md5(\$user->getPassword()));
                \$user->loginTimes=0;
                \$user->save();
                HttpSession::set('user_id',\$user->id);
                \$this->redirect("blog","display");
            }else{
                \$this->view->color="red";
                \$this->view->set("message","该用户名已有用户注册！");
            }
        }
    }
}
?>
AUTHCONTENT;

        $action_auth_file=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS."action".DS."Action_Auth.php";
        if(file_exists($action_auth_file))file_put_contents($action_auth_file, $action_auth_content);

        $util_view_dir=self::$save_dir.$root_core.DS."util".DS."view".DS;
        //2.去除在线编辑器工具集
        $ignore_files=array(
            "UtilKindEditor.php",
            "UtilXheditor.php",
        );
        foreach ($ignore_files as $ignore_file) {
            $toDeleteFile=$util_view_dir.DS."onlineditor".DS.$ignore_file;
            if(file_exists($toDeleteFile))unlink($toDeleteFile);
        }
        $toDeleteDir=$util_view_dir.DS."onlineditor".DS."ckEditor";
        if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

        //3.去除js框架工具集
        $ignore_files=array(
            "UtilAjaxDojo.php",
            "UtilAjaxMootools.php",
            "UtilAjaxProtaculous.php",
            "UtilAjaxPrototype.php",
            "UtilAjaxScriptaculous.php",
            "UtilAjaxYui.php"
        );
        foreach ($ignore_files as $ignore_file) {
            $toDeleteFile=$util_view_dir.DS."ajax".DS.$ignore_file;
            if(file_exists($toDeleteFile))unlink($toDeleteFile);
        }
    }

    /**
     * 运行生成Web项目代码重用
     */
    public static function Run()
    {
        if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))self::$save_dir=$_REQUEST["save_dir"];
        if(isset($_REQUEST["pj_name_cn"])&&!empty($_REQUEST["pj_name_cn"]))
        {
            self::$pj_name_cn=$_REQUEST["pj_name_cn"];
        }else{
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称【中文】</font></div>");
        }
        if(isset($_REQUEST["pj_name_en"])&&!empty($_REQUEST["pj_name_en"]))
        {
            self::$pj_name_en=$_REQUEST["pj_name_en"];
        }else{
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称【英文】</font></div>");
        }
        if(isset($_REQUEST["pj_name_alias"])&&!empty($_REQUEST["pj_name_alias"]))
        {
            self::$pj_name_alias=$_REQUEST["pj_name_alias"];
        }else{
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:新Web项目名称别名</font></div>");
        }
        if(isset($_REQUEST["dbname"])&&!empty($_REQUEST["dbname"]))
        {
            self::$db_name=$_REQUEST["dbname"];
        }else{
            self::UserInput();
            die("<div align='center'><font color='red'>不能为空:数据库名称</font></div>");
        }

        if(isset($_REQUEST["table_prefix"])&&!empty($_REQUEST["table_prefix"]))
        {
            self::$table_prefix=$_REQUEST["table_prefix"];
        }

        if(isset($_REQUEST["reuse_type"])&&!empty($_REQUEST["reuse_type"]))
            self::$reuse_type=$_REQUEST["reuse_type"];

        if(isset($_REQUEST["git_name"])&&!empty($_REQUEST["git_name"]))
            self::$git_name=$_REQUEST["git_name"];

        $default_dir=Gc::$nav_root_path;
        $domain_root=str_replace(Gc::$appName . DS, "", $default_dir);
        $domain_root=str_replace(Gc::$appName_alias . DS, "", $domain_root);
        $save_dir=self::$save_dir;
        self::$save_dir=$domain_root.self::$save_dir.DS;

        if(is_dir(self::$save_dir."core".DS)){
            self::$save_dir=$save_dir;
            self::UserInput();
            die("<div align='center'><font color='red'>该目录已存在!为防止覆盖您现有的代码,请更名!</font></div>");
        }

        if(self::$reuse_type==EnumReusePjType::MINI)
        {
            $include_dirs=array(
                "install",
                "core",
                "include",
                "tools"
            );

            UtilFileSystem::createDir(self::$save_dir);
            foreach ($include_dirs as $include_dir) {
                if(is_dir(Gc::$nav_root_path.$include_dir.DS)){
                    smartCopy(Gc::$nav_root_path.$include_dir.DS,self::$save_dir.$include_dir.DS);
                }
            }

            $homeAppDir=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en;
            UtilFileSystem::createDir($homeAppDir.DS."src".DS."domain".DS);

            //修改Initializer.php初始化文件
            $init_file=self::$save_dir."core".DS."main".DS."Initializer.php";
            $content=file_get_contents($init_file);
            $content=str_replace("Library_Loader::load_phpexcel_autoload(\$class_name);","",$content);
            $content=str_replace("self::loadLibrary();", "//self::loadLibrary();", $content);
            $content=str_replace("self::loadModule();", "//self::loadModule();", $content);
            //$content=str_replace("self::recordModuleClasses();", "//self::recordModuleClasses();", $content);
            file_put_contents($init_file, $content);

            $include_files=array(
                ".htaccess",
                "favicon.ico",
                "Gc.php",
                "index.php",
                "init.php",
                "test.php",
                "welcome.php"
            );
            foreach ($include_files as $include_file) {
                copy(Gc::$nav_root_path.$include_file, self::$save_dir.$include_file);
            }

            //修改Gc.php配置文件
            $gc_file=self::$save_dir."Gc.php";
            $content=file_get_contents($gc_file);
            $content=str_replace("\"model\",", "", $content);
            $content=str_replace("\"admin\",\r\n", "", $content);
            $content=str_replace(Gc::$site_name, self::$pj_name_cn, $content);
            $content=str_replace(Gc::$appName, self::$pj_name_en, $content);
            $content=str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
            file_put_contents($gc_file, $content);

            //修改Config_Db.php配置文件
            $conf_db_file=self::$save_dir."config".DS."config".DS."Config_Db.php";
            $content=file_get_contents($conf_db_file);
            $content=str_replace("\$dbname=\"".Config_Db::$dbname."\"", "\$dbname=\"".self::$db_name."\"", $content);
            $content=str_replace("\$table_prefix=\"".Config_Db::$table_prefix."\"", "\$table_prefix=\"".self::$table_prefix."\"", $content);
            file_put_contents($conf_db_file, $content);

            //修改Welcome.php文件
            $welcome_file=self::$save_dir."welcome.php";
            $content=file_get_contents($welcome_file);
            if(!empty(self::$git_name)){
                $ctrl=substr($content,0,strpos($content,"<?php \$help_url=\"")+17);
                $ctrr=substr($content,strpos($content,"<?php \$help_url=\"")+18);
                $ctrr=substr($ctrr,strpos($ctrr,"\""));
                $content=$ctrl.self::$git_name.$ctrr;
            }
            $content=str_replace("网站后台", "", $content);
            $content=str_replace("通用模板", "", $content);
            $content=str_replace("工程重用</a>|", "", $content);
            file_put_contents($welcome_file, $content);

            self::IgnoreTools();

            self::IgnoreAllDbEngineExceptMysql();
            self::IgnoreUtils();

            //修改Config_AutoCode.php配置文件
            $config_autocode_file=self::$save_dir."config".DS."config".DS."Config_AutoCode.php";
            $content=file_get_contents($config_autocode_file);
            $content=str_replace("const ONLY_DOMAIN=false;", "const ONLY_DOMAIN=true;", $content);
            file_put_contents($config_autocode_file, $content);

            //修改数据库脚本表前缀
            $db_bak_file=self::$save_dir."db".DS."mysql".DS."db_".Gc::$appName.".sql";
            if(file_exists($db_bak_file)){
                $content=file_get_contents($db_bak_file);
                $content=str_replace(Config_Db::$table_prefix ,self::$table_prefix , $content);
                file_put_contents($db_bak_file, $content);
            }

        }else{
            //生成新项目目录
            UtilFileSystem::createDir(self::$save_dir);
            if (!is_dir(self::$save_dir)) {
                $isMac = (contain(strtolower(php_uname()),"darwin")) ? true : false;
                $os = $isMac ? "MacOS" : "Linux";
                $info = "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>因为安全原因，需要手动在操作系统中创建目录:" . self::$save_dir . "<br/>" .
                        "$os 系统需要执行指令:<br/>" . str_repeat("&nbsp;",8) .
                        "sudo mkdir -p " . self::$save_dir . "<br/>" . str_repeat("&nbsp;",8);
                if (isMac){
                    $info .=
                        "sudo chmod -R 0777 " . self::$save_dir . "</p>";
                }else{
                    $info .=
                        "sudo chown -R www-data:www-data " . self::$save_dir . "<br/>" . str_repeat("&nbsp;",8) .
                        "sudo chmod -R 0755 " . self::$save_dir . "</p>";
                }
                die($info);
            }
            smartCopy(Gc::$nav_root_path,self::$save_dir);

            //修改Gc.php配置文件
            $gc_file=self::$save_dir."Gc.php";
            $content=file_get_contents($gc_file);
            $content=str_replace(Gc::$site_name, self::$pj_name_cn, $content);
            $content=str_replace(Gc::$appName, self::$pj_name_en, $content);
            $content=str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
            if((self::$reuse_type!=EnumReusePjType::FULL)){
                $content=str_replace("\"model\",", "", $content);
            }
            file_put_contents($gc_file, $content);

            //修改Config_Db.php配置文件
            $conf_db_file=self::$save_dir."config".DS."config".DS."Config_Db.php";
            $content=file_get_contents($conf_db_file);
            $content=str_replace("\$dbname=\"".Config_Db::$dbname."\"", "\$dbname=\"".self::$db_name."\"", $content);
            $content=str_replace("\$table_prefix=\"".Config_Db::$table_prefix."\"", "\$table_prefix=\"".self::$table_prefix."\"", $content);
            file_put_contents($conf_db_file, $content);

            //修改Welcome.php文件
            if(!empty(self::$git_name)){
                $welcome_file=self::$save_dir."welcome.php";
                $content=file_get_contents($welcome_file);

                $ctrl=substr($content,0,strpos($content,"<?php \$help_url=\"")+17);
                $ctrr=substr($content,strpos($content,"<?php \$help_url=\"")+18);
                $ctrr=substr($ctrr,strpos($ctrr,"\""));
                $content=$ctrl.self::$git_name.$ctrr;
                if(self::$reuse_type!=EnumReusePjType::FULL){
                    $content=str_replace("通用模板", "", $content);
                }

                file_put_contents($welcome_file, $content);
            }

            //修改应用文件夹名称
            $old_name=self::$save_dir.Gc::$module_root.DS.Gc::$appName.DS;
            $new_name=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS;
            if(is_dir($old_name)){
                $toDeleteDir=$old_name."view".DS."default".DS."tmp".DS."templates_c".DS;
                UtilFileSystem::deleteDir($toDeleteDir);
                UtilFileSystem::createDir($toDeleteDir);
                rename($old_name,$new_name);
            }

            //修改前台的注释:* @category 应用名称
            $frontActionDir=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS."action".DS;
            $actionFiles=UtilFileSystem::getAllFilesInDirectory($frontActionDir,array("php"));

            foreach ($actionFiles as $actionFile) {
                $content=file_get_contents($actionFile);
                $content=str_replace("* @category ".Gc::$appName, "* @category ".self::$pj_name_en, $content);
                file_put_contents($actionFile, $content);
            }

            $frontSrcDir=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS."src".DS;
            $srcFiles=UtilFileSystem::getAllFilesInDirectory($frontSrcDir,array("php"));

            foreach ($srcFiles as $srcFile) {
                $content=file_get_contents($srcFile);
                $content=str_replace("* @category ".Gc::$appName, "* @category ".self::$pj_name_en, $content);
                file_put_contents($srcFile, $content);
            }

            switch (self::$reuse_type) {
                case EnumReusePjType::SIMPLE:
                    self::IgnoreInCommon();
                    $toDeleteDir=self::$save_dir.Gc::$module_root.DS."model";
                    if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

                    self::IgnoreAllDbEngineExceptMysql();
                    self::IgnoreCommons();
                    self::IgnoreUtils();
                    break;
                case EnumReusePjType::LIKE:
                    self::IgnoreInCommon();
                    self::IgnoreCommons();
                    self::IgnoreUtils();

                    //修改model文件夹名称为后台文件夹admin
                    $old_model_name=self::$save_dir.Gc::$module_root.DS."model".DS;
                    $new_model_name=self::$save_dir.Gc::$module_root.DS."admin".DS;
                    if(is_dir($old_model_name)){
                        rename($old_model_name,$new_model_name);
                        //替换model的tpl文件里的链接地址
                        $modelTplDir=self::$save_dir.Gc::$module_root.DS."admin".DS."view".DS."default".DS."core".DS;
                        $tplFiles=UtilFileSystem::getAllFilesInDirectory($modelTplDir,array("tpl"));

                        foreach ($tplFiles as $tplFile) {
                            $content=file_get_contents($tplFile);
                            $content=str_replace("go=model.", "go=admin.", $content);
                            file_put_contents($tplFile, $content);
                        }

                        //修改Action控制器类的注释:* @category 应用名称
                        $modelActionDir=self::$save_dir.Gc::$module_root.DS."admin".DS."action".DS;
                        $actionFiles=UtilFileSystem::getAllFilesInDirectory($modelActionDir,array("php"));

                        foreach ($actionFiles as $actionFile) {
                            $content=file_get_contents($actionFile);
                            $content=str_replace("* @category ".Gc::$appName, "* @category ".self::$pj_name_en, $content);
                            file_put_contents($actionFile, $content);
                        }
                    }

                    //修改Config_AutoCode.php配置文件
                    $config_autocode_file=self::$save_dir."config".DS."config".DS."Config_AutoCode.php";
                    $content=file_get_contents($config_autocode_file);
                    $content=str_replace("const AFTER_MODEL_CONVERT_ADMIN=false;", "const AFTER_MODEL_CONVERT_ADMIN=true;", $content);
                    file_put_contents($config_autocode_file, $content);
                    break;
                case EnumReusePjType::HIGH:
                    self::IgnoreInCommon();
                    $old_model_name=self::$save_dir.Gc::$module_root.DS."model".DS;
                    if(is_dir($old_model_name))UtilFileSystem::deleteDir($old_model_name);
                    break;
                default:
                    //修改Action控制器类的注释:* @category 应用名称
                    $modelActionDir=self::$save_dir.Gc::$module_root.DS."model".DS."action".DS;
                    $actionFiles=UtilFileSystem::getAllFilesInDirectory($modelActionDir,array("php"));

                    foreach ($actionFiles as $actionFile) {
                        $content=file_get_contents($actionFile);
                        $content=str_replace("* @category ".Gc::$appName, "* @category ".self::$pj_name_en, $content);
                        file_put_contents($actionFile, $content);
                    }
                    break;
            }
        }
        self::$save_dir=$save_dir;
        self::UserInput();
        $default_dir=Gc::$url_base;
        $domain_url=str_replace(Gc::$appName."/", "", $default_dir);

        if (contain(strtolower(php_uname()),"darwin")){
            $domain_url=UtilNet::urlbase();
            $file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
            if (contain($file_sub_dir,"tools".DS))
                $file_sub_dir=substr($file_sub_dir,0,strpos($file_sub_dir,"tools".DS));
            $domainSubDir=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir);
            if(!endwith($domain_url,$domainSubDir))$domain_url.=$domainSubDir;
            $domain_url=str_replace(Gc::$appName."/", "", $domain_url);
        }
        die("<div align='center'><font color='green'><a href='".$domain_url.self::$pj_name_en."/' target='_blank'>生成新Web项目成功！</a></font><br/><a href='".$domain_url.self::$pj_name_en."/' target='_blank'>新地址</a></div>");
    }

    /**
     * 多数情况下都会清除的内容
     */
    private static function IgnoreInCommon()
    {
        self::IgnoreDir();
        self::IgnoreFiles();
        self::IgnoreTools();
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {
        $title="一键重用Web项目代码";
        if(empty($_REQUEST["save_dir"])){
            $pj_name_cn=Gc::$site_name;
            $pj_name_en=Gc::$appName;
            $pj_name_alias=Gc::$appName_alias;
            $default_dir=Gc::$nav_root_path;
            $domain_root=str_replace($pj_name_en . DS, "", $default_dir);
            $domain_root=str_replace(Gc::$appName_alias . DS, "", $domain_root);
            $default_dir=$pj_name_en;
            $dbname=Config_Db::$dbname;
            $table_prefix=Config_Db::$table_prefix;
            $git_name="http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html";
        }else{
            $reuse_type=self::$reuse_type;
            $pj_name_cn=self::$pj_name_cn;
            $pj_name_en=self::$pj_name_en;
            $pj_name_alias=self::$pj_name_alias;
            $default_dir=Gc::$nav_root_path;
            $domain_root=str_replace(Gc::$appName . DS, "", $default_dir);
            $domain_root=str_replace(Gc::$appName_alias . DS, "", $domain_root);
            $default_dir=self::$save_dir;
            $dbname=self::$db_name;
            $table_prefix=self::$table_prefix;
            $git_name=self::$git_name;
        }
        $inputArr=array(
            "4"=>"精简版",
            "5"=>"MINI版",
            "2"=>"通用版",
            "3"=>"高级版",
            "1"=>"完整版"
        );

        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\r\n
                <html lang='zh-CN' xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>\r\n";
        echo "<head>\r\n";
        echo UtilCss::form_css()."\r\n";
        $url_base=UtilNet::urlbase();
        echo "</head>";
        echo "<body>";
        echo "<br/><br/><br/><h1 align='center'>$title</h1>\r\n";
        echo "<div align='center' height='450'>\r\n";
        echo "<form>\r\n";
        echo "    <div style='line-height:1.5em;'>\r\n";
        echo "        <label>Web项目名称【中文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' placeholder='Web项目名称【中文】' name='pj_name_cn' value='$pj_name_cn' id='pj_name_cn' /><br/>\r\n";
        echo "        <label>Web项目名称【英文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' placeholder='Web项目名称【英文】' name='pj_name_en' value='$pj_name_en' id='pj_name_en' oninput=\"document.getElementById('dbname').value=this.value;document.getElementById('save_dir').value=this.value;\" /><br/>\r\n";
        echo "        <label title='最好两个字母,头字母大写'>&nbsp;&nbsp;&nbsp;&nbsp;Web项目别名:</label><input title='最好两个字母,头字母大写' style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_alias' value='$pj_name_alias' id='pj_name_alias' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;输出Web项目路径:</label><label>$domain_root</label><input style='width:190px;text-align:left;padding-left:10px;' type='text' name='save_dir' value='$default_dir' id='save_dir' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;数据库名称:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='dbname' value='$dbname' id='dbname' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;数据库表名前缀:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='table_prefix' value='$table_prefix' id='table_prefix' /><br/>\r\n";
        echo "        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帮助地址:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='git_name' value='$git_name' id='git_name' /><br/>\r\n";
        $selectd_str="";
        if (!empty($inputArr)){
            echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;重用类型:</label><select name='reuse_type'>\r\n";
            foreach ($inputArr as $key=>$value) {
                if(isset($reuse_type)){
                    if($key==$reuse_type)$selectd_str=" selected";else $selectd_str="";
                }
                echo "        <option value='$key'$selectd_str>$value</option>\r\n";
            }
            echo "        </select>\r\n";
        }
        echo "    </div>\r\n";
        echo "    <input type='submit' value='生成' /><br/>\r\n";
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
function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
{
    $result=false;

    if (is_file($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
            }
            $__dest=$dest."/".basename($source);
        } else {
            $__dest=$dest;
        }
        $dest_dir=dirname($__dest);
        UtilFileSystem::createDir($dest_dir);
        $result=copy($source, $__dest);
        chmod($__dest,$options['filePermission']);

    } elseif(is_dir($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if ($source[strlen($source)-1]=='/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest=$dest.basename($source);
                @mkdir($dest);
                chmod($dest,$options['filePermission']);
            }
        } else {
            if ($source[strlen($source)-1]=='/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            }
        }

        $dirHandle=opendir($source);
        while($file=readdir($dirHandle))
        {
            if($file!="." && $file!=".."&& $file!=".git"&& $file!=".svn"&& $file!=".DS_Store")
            {
                 if(!is_dir($source."/".$file)) {
                    $__dest=$dest."/".$file;
                } else {
                    $__dest=$dest."/".$file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $result=smartCopy($source."/".$file, $__dest, $options);
            }
        }
        closedir($dirHandle);

    } else {
        $result=false;
    }
    return $result;
}


//控制器:运行Web项目代码重用
if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"])){
    Project_Refactor::Run();
}else{
    Project_Refactor::UserInput();
}
