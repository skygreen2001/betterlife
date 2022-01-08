<?php

/**
 * -----------| 枚举类型: 数据库方式类别|数据源定义 |-----------
 * @category betterlife
 * @package core.config
 * @author skygreen
 */
class EnumDbSource extends Enum
{
    /**
     * 默认: 数据库: Mysql
     */
    const DB_MYSQL = 0;
    /**
     * 数据库: PostgresSql;需要 PostgreSQL 8.2 and later
     */
    const DB_PGSQL = 1;
    /**
     * 数据库: PHP自带的SQLite,数据存储在内存中
     */
    const DB_SQLITE_MEMORY = 3;
    /**
     * 数据库: Oracle
     */
    const DB_ORACLE = 4;
    /**
     * 数据库: Informix
     */
    const DB_INFOMIX = 5;
    /**
     * 数据库: IBM Db2
     */
    const DB_DB2 = 6;
    /**
     * 数据库: Microsoft Excel;ODBC支持[Config_Db数据库ODBC设置$dbname = Excel文件名]
     */
    const DB_MICROSOFT_EXCEL = 7;
    /**
     * Config_Db数据库ODBC设置$dbname = 数据库文件名
     *
     * 如: Config_Db::$dbname = D:\betterlife.mdb
     *
     * 数据库: Microsoft Access:
     *
     * 说明: Access支持GBK字符集，尚未找到对UTF-8的支持设置；
     *
     * 因此开发此类应用；需默认采用GBK编码，否则在输出到页面前需要进行转码
     */
    const DB_MICROSOFT_ACCESS = 8;
    /**
     * 数据库: Microsoft Sql Server
     *
     * 需要设置服务器名Config_Db::$host;和数据库名Config_Db::$dbname;
     *
     * 说明: Microsoft Sql Server支持GBK字符集，对UTF-8的支持设置比较复杂；
     *
     * 因此开发此类应用；需默认采用GBK编码，否则在输出到页面前需要进行转码
     */
    const DB_SQLSERVER = 9;
    /**
     * 数据库: Microsoft Sybase
     */
    const DB_SYBASE = 10;
    /**
     * 数据库: FreeTDS
     */
    const DB_FREETDS = 11;
    /**
     * 数据库: FireBird
     */
    const DB_FIREBIRD = 12;
    /**
     * 数据库: Interbase
     */
    const DB_INTERBASE = 13;
    /**
     * 数据库: LDAP
     *
     * 可通过Config_Db::$engine = ENGINE_DAL_ADODB配合使用
     *
     * 需要配置如下:
     *
     *        Config_Db::$host   = 'ldap.baylor.edu';
     *
     *        Config_Db::$dbname = 'ou = People,o = Baylor University,c = US';
     */
    const DB_LDAP = 14;
    /**
     * 数据库: Sqlite 2
     */
    const DB_SQLITE2 = 15;
    /**
     * 数据库: PHP自带的SQLite3 推荐版本
     */
    const DB_SQLITE3 = 16;
}

/**
 * -----------| 枚举类型: 数据源操作方式引擎定义 |-----------
 * @category betterlife
 * @package core.config
 * @author skygreen
 */
class EnumDbEngine extends Enum
{
    /**
     * PHP自带的MYSQL数据库访问方法函数
     */
    const ENGINE_OBJECT_MYSQL_PHP = 0;
    /**
     * 默认: 经典的MYSQLI访问数据库方法函数
     */
    const ENGINE_OBJECT_MYSQL_MYSQLI = 1;
    /**
     * PHP自带的ODBC数据库访问方法函数
     */
    const ENGINE_OBJECT_ODBC = 2;
    /**
     * PHP自带的MS SQL SERVER数据库访问方法函数
     *
     * 当 $engine = self::ENGINE_OBJECT_MSSQLSERVER时；
     *
     * 1. 它能和FreeTDS配合支持UTF-8字符集，FreeTDS本身支持Unix和Linux，但也有支持Windows的组件；详情请看Config_Mssql
     * 2. 时间在表里设置为datetime;在页面上显示的时间格式不太适合中文阅读
     *
     * 解决办法如下:
     *
     *  a. 修改php.ini文件，找到php.ini文件，将mssql.datetimeconvert 设为OFF，并去掉行首的‘；’
     *
     *  b. 如果没办法修改php.ini文件，可以在你的php配置（比喻数据库连接文件）文件里加上一句:
     *
     *    ini_set ("mssql.datetimeconvert","0"); //设置数据库格式.
     */
    const ENGINE_OBJECT_MSSQLSERVER = 3;
    /**
     * PHP自带的Sqlite访问数据库方法函数
     */
    const ENGINE_OBJECT_SQLITE = 4;
    /**
     * PHP自带的PostgresSql访问数据库方法函数
     */
    const ENGINE_OBJECT_POSTGRES = 5;
    /**
     * PDO:PHP Data Objects;作为PECL extension；数据库调用统一的DAO；PHP5.0以上均可使用；
     */
    const ENGINE_DAL_PDO = 111;
    /**
     * Adode:支持MySQL, Oracle, Microsoft SQL Server, Sybase, Sybase SQL Anywhere,
     * Informix, PostgreSQL, FrontBase, SQLite, Interbase (Firebird and Borland variants),
     * Foxpro, Access, ADO, DB2, SAP DB and ODBC
     */
    const ENGINE_DAL_ADODB = 112;
    /**
     * 配合Adodb使用PDO；仅支持PHP5
     */
    const ENGINE_DAL_ADODB_PDO = 113;
    /**
     * MDB2
     */
    const ENGINE_DAL_MDB2   = 114;
}

/**
 * -----------|  所有数据库配置的父类 |-----------
 *
 * @todo Sql Server 和 Pdo的测试-尚不知道如何找到Php5.3的php_pdo_mssql驱动；暂无需求
 *
 *        Sql Server 第三方方案: http://www.easysoft.com/developer/languages/php/sql_server_unix_tutorial.html#driver
 *
 * 说明:  目前可使用PHP自带的ODBC方案使用Sql Server；通过配置Config_Db:$db = DB_SQLSERVER和Config_Db:$engine = ENGINE_OBJECT_ODBC即可
 * @category betterlife
 * @package core.config
 * @author skygreen
 */
class Config_Db extends ConfigBB
{
    /**
     * @var int 当前应用使用Mysql数据库
     * @static
     */
    public static $db = EnumDbSource::DB_MYSQL;//self::DB_SQLSERVER;
    /**
     * @var int 数据库使用调用引擎
     * @static
     */
    public static $engine = EnumDbEngine::ENGINE_OBJECT_MYSQL_MYSQLI;//self::ENGINE_DAL_ADODB;
    /**
     * @var string Host 默认本地 localhost
     * @static
     */
    public static $host = "127.0.0.1";//UF-T4300-2003-9
    /**
     * 默认端口如下:
     *
     * Mysql 3306
     *
     * Postgres 5432
     *
     * DB2 50000
     *
     * Microsoft Sql Server 10060
     *
     * @var string 默认端口
     *
     * @static
     */
    public static $port = "";
    /**
     * @var string 数据库用户名
     * @static
     */
    public static $username = "root";
    /**
     * @var string 数据库密码
     * @static
     */
    public static $password = "";
    /**
     * 如果数据库指定是文件，如Microsoft Access,Sqlite
     * 则为数据库文件名
     * 如果是Oracle数据库则是SID名称
     * @var string 数据库名称
     * @static
     */
    public static $dbname = "betterlife";
    /**
     * @var string 数据库表字段字符集
     * @static
     */
    public static $character = Config_C::CHARACTER_UTF8_MB4;
    /**
    * 协助调试: 打印SQL语句
    * @var bool
    * @static
    */
    public static $debug_show_sql = true;
    /**
     * 目前调试通过  该参数对Config_Db::$engine
     *
     *     * ENGINE_DAL_ADODB
     *     * ENGINE_OBJECT_ODBC 有效
     *
     * 是否使用了Dsn的设置
     *
     * true : 在Windows里进行了系统DSN的设置，只需在Config_Db::$dbname里输入DSN设置的名称即可
     *
     * false: 未进行设置，则需要在Config_Db::$dbname设置数据库文件所在路径或者数据库名称
     *
     * 特殊情况: 当数据库为Sql server时； *ENGINE_DAL_ADODB 只支持  $is_dsn_set = false；这是由Adodb自身所决定的
     * @var boolean
     */
    public static $is_dsn_set = false;
    /**
     * @var boolean 是否持久化数据库
     * @static
     */
    public static $is_persistent = false;
    /**
     * @var string 数据库表名前缀
     * @static
     */
    public static $table_prefix = "bb_";

    /**
     * 数据库表和数据对象类的ORM映射
     *
     * 命名规范:[类第一个字母大写，表名都是小写]
     *
     * 数据库实体POJO对象需放置在domain目录下
     *
     * 表名为三段: 数据库表名前缀+“_”+[文件夹目录+“_”]...+(类名)
     *
     * @example 示例如下:
     *
     *     数据库表名前缀:apts_
     *
     *     类  : Task
     *
     *     包  : domain.business.work
     *
     *     文件夹目录: domain/business/work
     *
     *     表名: apts_business_work_task
     *
     * 无需进行$orm配置；通过规则生成表与类的映射；即规则优于配置
     *
     * 如果在 $orm 配置里定义了类和表名的关系, 则从 $orm 配置中根据类名获取表名
     *
     * 这是全局定义，也就是说如果不是通过sqlExecute直接使用SQL, 框架所有的数据库表的增删改查都会从这里根据类名获取表名
     *
     * @var array key: 类名, value: 表名
     * @static
     */
    public static $orm = array(
    );

    /**
     * 对象定义和表定名映射
     *
     * 相当于ORM
     *
     * 无需进行$orm配置；通过规则生成表与类的映射；即规则优于配置
     *
     * 如果在 $orm 配置里定义了类和表名的关系, 则从 $orm 配置中根据类名获取表名
     * @param classname 类名称
     * @return 根据对象定义返回表名
     * @final
     */
    final public static function orm($classname)
    {
        if (array_key_exists($classname, self::$orm)) {
            return self::$orm[$classname];//在Config_Db::$orm里手动配置类与表的对应关系
        } else {
            return self::ormByRule($classname);
        }
    }

    /**
     * 根据表命名获取对应的对象定义
     *
     * 表命名到对象定义映射
     *
     * 相当于ORM
     *
     * 无需进行$orm配置；通过规则生成表与类的映射；即规则优于配置
     *
     * 如果在 $orm 配置里定义了类和表名的关系, 则从 $orm 配置中根据表名获取类名
     * @param $tablename 表名称
     * @return 根据表名称返回对象名称定义
     * @final
     */
    final public static function tom($tablename)
    {
        if (in_array($tablename, self::$orm)) {
            return array_search($tablename, self::$orm);//在Config_Db::$orm里手动配置类与表的对应关系
        } else {
            return self::tomByRule($tablename);
        }
    }

    /**
     * 表名段之间的连接符
     */
    const TABLENAME_CONCAT = "_";
    /**
     * 关系表的类所在的文件夹目录
     */
    const TABLENAME_DIR_RELATION = "_relation";
    /**
     * 关系表的段名
     */
    const TABLENAME_RELATION = "_re";

    /**
     * 按照类和表的对应关系规则规范自动生成；
     * 要求是表的命名一定按照Config_Db::$orm的说明进行定义
     * @param string $classname 数据库实体对象POJO类名
     * @return string 遵循命名规则和规范的表名
     */
    private static function ormByRule($classname)
    {
        $class_root_dir = Config_F::DOMAIN_ROOT;
        $class          = new ReflectionClass($classname);
        $filename       = strtolower(dirname($class->getFileName()));
        $subDirname     = substr($filename, strpos($filename, $class_root_dir) + strlen($class_root_dir) + 1);
        if ($subDirname) {
            $subDirname = str_replace(DIRECTORY_SEPARATOR, self::TABLENAME_CONCAT, $subDirname);
            $subDirname = str_replace(self::TABLENAME_DIR_RELATION, self::TABLENAME_RELATION, $subDirname);
            $subDirname = $subDirname . self::TABLENAME_CONCAT;
        } else {
            $subDirname = "";
        }
        $tablename = self::$table_prefix . $subDirname . strtolower($classname);
        return $tablename;
    }

     /**
      * 按照类和表的对应关系规则规范自动生成；
      *
      * 要求是表的命名一定按照Config_Db::$orm的说明进行定义
      * @param string $tablename 遵循命名规则和规范的表名
      * @return string 数据库实体对象POJO类名
      */
    private static function tomByRule($tablename)
    {
        $classname      = str_replace(self::$table_prefix, "", $tablename);
        $classnamepart  = explode(self::TABLENAME_CONCAT, $classname);
        $classnamepart  = array_reverse($classnamepart);
        $maybeClassname = "";
        foreach ($classnamepart as $name) {
            $maybeClassname = ucfirst($name) . $maybeClassname;
            if (class_exists($maybeClassname)) {
                $classname = $maybeClassname;
                return $classname;
            }
            $maybeClassname = self::TABLENAME_CONCAT . $maybeClassname;
        }
        return null;
    }

    /**
     * 初始化全局变量: Gc::$database_config
     */
    public static function initGc()
    {
        if (isset(Gc::$database_config)) {
            $db_config = Gc::$database_config;
            if (isset($db_config['db_type'])) {
                Config_Db::$db     = $db_config['db_type'];
            }
            if (isset($db_config['driver'])) {
                Config_Db::$engine = $db_config['driver'];
            }

            if (isset($db_config['host'])) {
                Config_Db::$host           = $db_config['host'];
            }
            if (isset($db_config['port'])) {
                Config_Db::$port           = $db_config['port'];
            }
            if (isset($db_config['database'])) {
                Config_Db::$dbname         = $db_config['database'];
            }
            if (isset($db_config['username'])) {
                Config_Db::$username       = $db_config['username'];
            }
            if (isset($db_config['password'])) {
                Config_Db::$password       = $db_config['password'];
            }

            if (isset($db_config['prefix'])) {
                Config_Db::$table_prefix   = $db_config['prefix'];
            }
            if (isset($db_config['debug'])) {
                Config_Db::$debug_show_sql = $db_config['debug'];
            }
        }
    }
}

Config_Db::initGc();
