<?php

/**
 * -----------| 使用PHP5自带的MySQL Extension |-----------
 * @category betterlife
 * @package core.db.sql
 * @subpackage mysql
 * @author skygreen
 */
class SqlMysql extends Sql implements ISqlNormal
{
    /**
     * @var mixed 数据库连接
     */
    private $connection;

    /**
     * 连接数据库
     * @param type $host
     * @param type $port
     * @param type $username
     * @param type $password
     * @param type $dbname
     * @return mixed 数据库连接
     */
    public function connect($host = null, $port = null, $username = null, $password = null, $dbname = null)
    {
        $connecturl = ConfigMysql::connctionurl($host, $port);
        if (!isset($username)) {
            $username = ConfigMysql::$username;
        }
        if (!isset($password)) {
            $password = ConfigMysql::$password;
        }
        if (!isset($dbname)) {
            $dbname = ConfigMysql::$dbname;
        }
        $this->connection = mysql_connect($connecturl, $username, $password);
        mysql_select_db($dbname, $this->connection);
    }

    /**
     * 新增一条数据记录
     * @param array $data 数据数组
     * @return int 返回插入数据的ID编号
     * @example 示例:
     * ```
     *     $db     = new SqlMysql();
     *     $data   = array("name"=>"skygreen","pass"=>md5("hello world"));
     *     $result = $this->Db->insertdata($data);
     *     其中 name, pass 是表列名，"skygreen", md5("hello world"))是列值，与列名一一对应。
     * ```
     */
    public function insertdata($tablename, $data)
    {
        $fields = join(",", array_keys($data));
        $values = "'" . join(",", array_values($data)) . "'";
        $query  = CrudSQL::SQL_INSERT . $tablename . " ({$fields})" . CrudSQL::SQL_INSERT_VALUE . " ({$values})";
        return mysql_query($query, $this->connection);
    }

    /**
     * 删除一条数据记录
     * @param int $sql_id 需删除数据的ID编号Sql语句
     * @example 示例如下:
     *
     *     $sql_id:
     *
     *         user_id=1
     *
     * @param string $tablename 表名称
     * @example 示例如下:
     * ```
     *     $tablename:
     *         $db=new SqlMysql();
     *         $result =$db ->deleteData(1);
     * ```
     * @return boolean:是否删除成功
     */
    public function deleteData($tablename, $sql_id)
    {
        $query = CrudSQL::SQL_DELETE . CrudSQL::SQL_FROM . $tablename . CrudSQL::SQL_WHERE . $sql_id;
        return mysql_query($query, $this->connection);
    }

    /**
     * 修改一条数据记录
     * @param int $sql_id 需删除数据的ID编号Sql语句
     * @example 示例如下:
     *
     *     $sql_id:
     *
     *         user_id=1
     *
     * @param array $data 数据数组
     * @return boolean:是否修改成功
     * @example 示例:
     * ```
     *      $db=new SqlMysql();
     *      $data = array("name"=>"afif2","pass"=>md5("hello world"));
     *      $result = $db->updateData(1, $data);
     * ```
     */
    public function updateData($tablename, $sql_id, $data)
    {
        $queryparts = array();
        foreach ($data as $key => $value) {
            $queryparts[] = "{$key} = '{$value}'";
        }
        $query = CrudSQL::SQL_UPDATE . $tablename . CrudSQL::SQL_SET . join(",", $queryparts) .
                 CrudSQL::SQL_WHERE . $sql_id;
        return mysql_query($query, $this->connection);
    }

    /**
     * 直接执行SQL语句
     * @param string $sqlstring SQL语句
     */
    public function sqlExectue($sqlstring)
    {
        return mysql_query($sqlstring, $this->connection);
    }
}
