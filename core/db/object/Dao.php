<?php

/**
 * -----------| 所有数据库访问对象的父类 |-----------
 * @category Betterlife
 * @package core.db.object
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
abstract class Dao
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * @var string 对象类名
     */
    protected $classname;
    /**
     * @var object 数据库连接
     */
    public $connection;
    /**
     * @var string SQL语句
     */
    protected $sQuery;
    /**
     * @var array 预编译准备SQL参数
     */
    protected $saParams;
    /**
     * @var mixed 预编译准备SQL表达式容器
     */
    protected $stmt;
    /**
     * @var mixed 执行SQL的结果
     */
    protected $result;
    /**
     * Sql 连接符
     */
    const EQUAL = "=";
    //</editor-fold>

    /**
     * 构造器
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param enum $dbtype 指定数据库类型。{使用DaoODBC引擎，需要定义该字段,该字段的值参考: EnumDbSource}
     *                      需要在实现里重载 setdbType方法以传入数据库类型参数
     */
    public function __construct($host = null, $port = null, $username = null, $password = null, $dbname = null, $dbtype = null)
    {
        if (isset($dbtype)) {
            $this->setdbType($dbtype);
        }
        $this->connect($host, $port, $username, $password, $dbname);
    }

    /**
     * 是否能连接上数据库
     */
    public function isCanConnect()
    {
        if ($this->connection && ($this->connection->connect_errno == 0)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 指定数据库类型
     * @param enum $dbtype 指定数据库类型。{使用DaoODBC引擎，需要定义该字段,该字段的值参考: EnumDbSource}
     */
    protected function setdbType($dbtype)
    {
    }

    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @return mixed 数据库连接
     */
    abstract protected function connect($host = null, $port = null, $username = null, $password = null, $dbname = null);

    /**
     * 获取数据库连接
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @return mixed 数据库连接
     */
    public function getConnection($host = null, $port = null, $username = null, $password = null, $dbname = null)
    {
        if ($this->connection == null) {
            $this->connect($host, $port, $username, $password, $dbname);
        }
        return $this->connection;
    }

    /**
     * 返回基于主键查询的sql语句
     * @param mixed $object 对象实体|对象名称
     * @return string 基于主键的sql语句,如主键列名为user_id,则返回"user_id"
     */
    protected function sql_id($object)
    {
        if (is_string($object)) {
            if (class_exists($object)) {
                $object = new $object();
            }
        }
        if ($object instanceof DataObject) {
            return DataObjectSpec::getRealIDColumnName($object);
        }
        x(Wl::ERROR_INFO_EXTENDS_CLASS);
    }

    /**
     * 察看传入参数是否合法
     * @param string|class $object 需要更新的对象实体|对象名称【Id是已经存在的】
     * @param boolean
     */
    protected function validParameter($object)
    {
        if (is_string($object)) {
            if (class_exists($object)) {
                if ((new $object()) instanceof DataObject) {
                    $this->classname = $object;
                    return true;
                } else {
                    x(Wl::ERROR_INFO_EXTENDS_CLASS, $this);
                    return false;
                }
            }
        } else {
            return $this->validObjectParameter($object);
        }
    }

    /**
     * 将数据对象里的显示属性进行清除
     *
     * 规范: 数据对象里的显示属性以v_开始
     * @param array $saParams 预编译准备SQL参数
     * @return array 数据对象里的显示属性
     */
    protected function filterViewProperties($saParams)
    {
        if (isset($saParams) && is_array($saParams)) {
            $keys = array_keys($saParams);
            foreach ($keys as $key) {
                if (strpos((substr($key, 0, 2)), "v_") !== false) {
                    unset($saParams[$key]);
                }
            }
        }
        return $saParams;
    }

    /**
     * 察看传入参数是否合法
     * @param class $object 需要更新的对象实体|对象名称【Id是已经存在的】
     * @param boolean
     */
    protected function validObjectParameter($object)
    {
        if (is_object($object)) {
            if ($object instanceof DataObject) {
                $this->classname = $object->classname();
            } else {
                x(Wl::ERROR_INFO_EXTENDS_CLASS, $this);
                return false;
            }
        } else {
            x(Wl::ERROR_INFO_NEED_OBJECT_CLASSNAME, $this);
            return false;
        }
        return true;
    }

    /**
     * 设置Mysql数据库字符集
     * @param string $character_code 字符集
     */
    public function change_character_set($character_code = "utf8mb4")
    {
        $sql = "SET NAMES " . $character_code;
        $this->connection->query($sql);
    }

    /**
     * 获取插入或者更新的数据的类型。
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @param array $saParams 对象field名称值键值对
     * @param array $typeOf
     *
     *     - 0: 通用的协议定义的类型标识，暂未实现。
     *     - 1: PHP定义的数据类型标识，暂未实现。
     *
     * @return array 获取插入或者更新的数据的field和field值类型键值对
     */
    public function getColumnTypes($object, $saParams, $typeOf = 1)
    {
        $type = array();
        foreach ($saParams as $key => $value) {
            $type[$key] = "s";
        }
        return $type;
    }

    /**
     * 当查询结果集只有一个值的时候，直接返回该值
     *
     * 如果是数据对象，返回数组对象
     *
     * 如果是基础类型如int等一般是统计函数count,sum,max,min，直接返回值
     *
     * @param array $result 结果集
     * @return 值
     */
    protected function getValueIfOneValue($result)
    {
        if (($result != null) && (count($result) == 1)) {
            if ($result[0] instanceof stdClass) {
                // $tmp = UtilObject::object_to_array( $result[0] );
                // if (count($tmp) == 1) {
                //     $tmp_values = array_values($tmp);
                //     $result     = $tmp_values[0];
                // }
            } else {
                if (!( $result[0] instanceof DataObject)) {
                    $result = $result[0];
                }
            }
        }
        return $result;
    }
}
