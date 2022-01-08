<?php

/**
 * -----------| 数据对象常用的一些方法 |-----------
 * @category betterlife
 * @package core.model
 * @subpackage dataobject
 * @author skygreen
 */
class DataObjectFunc
{
    //<editor-fold defaultstate="collapsed" desc="魔术方法">
    /**
     * 对应数据对象的__call方法
     *
     * @param string $dataobject 当前数据对象
     * @param string $method 方法名
     * @param array $arguments 传递的变量数组
     */
    public static function call($dataobject, $method, $arguments)
    {
        if (strpos($method, "set") !== false) {
            $property = substr($method, strlen("set"), strlen($method));
            $property = lcfirst($property);
            //$property=lcfirst(substr($method,strlen("set"),strlen($method)));
            if (is_object($dataobject)) {
                $dataobject->{$property} = $arguments[0];
            }
        } elseif (strpos($method,"get") !== false) {
            $property = substr($method, strlen("get"), strlen($method));
            $property = lcfirst($property);
            if (method_exists($dataobject,$property)) {
                $method = $property;
                if (is_object($dataobject)) {
                    return $dataobject->$method();
                }
            }
            //$property=lcfirst(substr($method,strlen("get"),strlen($method)));
            if (is_object($dataobject)) {
                return $dataobject->{$property};
            }
        } else {
            //处理表之间一对一，一对多，多对多的关系
            //$isRelation=false;//是否存在关系
            if (is_object($dataobject)) {
                $relationData = $dataobject->getMutualRelation( $method );
                if ($relationData) {
                    return $relationData;
                }
            }
        }
    }

    /**
     * 对应数据对象的__get方法
     *
     * @param string $dataobject 当前数据对象
     *
     * 可设定对象未定义的成员变量[但不建议这样做]
     *
     * 类定义变量访问权限设定需要是pulbic
     *
     * @param mixed $property 属性名
     * @return mixed 属性值
     */
    public static function get($dataobject, $property)
    {
        if (method_exists($dataobject, "get" . ucfirst($property))) {
            $methodname = "get" . ucfirst($property);
            if (is_object($dataobject)) {
                return $dataobject->{$methodname}();
            }
        } else {
            //处理表之间一对一，一对多，多对多的关系
            //$isRelation=false;//是否存在关系
            if (is_object($dataobject)) {
                $relationData = $dataobject->getMutualRelation( $property );
                if ($relationData) {
                    return $relationData;
                } else {
                    if (!property_exists($dataobject,$property)) {
                        if (method_exists($dataobject,$property)) {
                            return $dataobject->$property();
                        } else {
                            return @$dataobject->{$property};
                        }
                    }
                }
            }
        }
    }

    /**
     * 对应数据对象的__set方法
     *
     * 可设定对象未定义的成员变量[但不建议这样做]
     *
     * 类定义变量访问权限设定需要是pulbic
     *
     * @param string $dataobject 当前数据对象
     * @param mixed $property 属性名
     * @param mixed $value 属性值
     */
    public static function set($dataobject, $property, $value)
    {
        if (method_exists($dataobject, "set" . ucfirst($property))) {
            $methodname = "set" . ucfirst($property);
            if (is_object($dataobject)) {
                $dataobject->{$methodname}($value);
            }
        } else {
            //if (!property_exists($dataobject,$property)) {
            if (is_object($dataobject)) {
                $dataobject->{$property} = $value;
            }
            //}
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="数据持久化: 数据库的CRUD操作">
    /**
     * 对应数据对象的updateProperties方法
     * @param string $classname 数据对象类名
     * @param string $sql_id 需更新数据的ID编号或者ID编号的Sql语句
     * 示例如下:
     *
     *     1. 1, 2, 3
     *     2. array(1, 2, 3)
     *
     * @param string $array_properties 指定的属性
     * 示例如下:
     *
     *     1. pass = 1, name = 'sky'
     *     2. array("pass" => "1", "name" => "sky")
     *
     * @return boolen 是否更新成功；true为操作正常
     */
    public static function updateProperties($classname, $sql_ids, $array_properties)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Update();
        $_SQL->isPreparedStatement = false;
        if ($sql_ids && !contain( $sql_ids, "=" )) {
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }
            if ($classname instanceof DataObject) {
                $idColumn = DataObjectSpec::getRealIDColumnName( $classname );
            }
            $condition = " ";
            if (is_string($sql_ids)) {
                $sql_ids = explode(",", $sql_ids);
            } elseif (!is_array($sql_ids)) {
                $sql_ids = array($sql_ids);
            }
            if ($sql_ids && (count($sql_ids) > 0 )) {
                $condition = " $idColumn=" . $sql_ids[0] . " ";
                for ($i = 1; $i < count($sql_ids); $i++) {
                    if (!empty($sql_ids[$i])) {
                        $condition .= " or $idColumn=" . $sql_ids[$i] . " ";
                    }
                }
            }
            $sql_ids = $condition;
        }

        $sQuery = $_SQL->update($tablename)->set($array_properties)->where($sql_ids)->result();
        return DataObject::dao()->sqlExecute( $sQuery );
    }

    /**
     * 对应数据对象的updateBy方法
     *
     * @param string $classname 数据对象类名
     * @param mixed $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id = 1, name = 'sky'"
     *     1. array("id = 1", "name = 'sky'")
     *     2. array("id" => "1", "name" => "sky")
     *     3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @param string $array_properties 指定的属性
     * 示例如下:
     *
     *     1. pass = 1, name = 'sky'
     *     2. array("pass" => "1", "name" => "sky")
     *
     * @return boolen 是否更新成功；true为操作正常
     */
    public static function updateBy($classname, $filter, $array_properties)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Update();
        $_SQL->isPreparedStatement = false;
        $sQuery = $_SQL->update($tablename)->set($array_properties)->where($filter)->result();
        return DataObject::dao()->sqlExecute( $sQuery );
    }

    /**
     * 对属性进行递增
     *
     * @param string $classname 数据对象类名
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id = 1, name = 'sky'"
     *     1. array("id = 1", "name = 'sky'")
     *     2. array("id" => "1", "name" => "sky")
     *     3. 允许对象如new User(id = "1", name = "green");
     *
     * @param string classname 数据对象类名
     * @param string property_name 属性名称
     * @param int incre_value 递增数
     *
     * @return bool 是否操作成功
     */
    public static function increment($classname, $filter = null, $property_name, $incre_value = 1)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Update();
        $_SQL->isPreparedStatement = false;
        $sQuery = $_SQL->update($tablename)->set("$property_name=$property_name+$incre_value")->where($filter)->result();
        return DataObject::dao()->sqlExecute( $sQuery );
    }

    /**
     * 对属性进行递减
     *
     * @param string $classname 数据对象类名
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id = 1, name = 'sky'"
     *     1. array("id = 1", "name = 'sky'")
     *     2. array("id" => "1", "name" => "sky")
     *     3. 允许对象如new User(id = "1", name = "green");
     *
     * @param string classname 数据对象类名
     * @param string property_name 属性名称
     * @param int decre_value 递减数
     *
     * @return bool 是否操作成功
     */
    public static function decrement($classname, $filter = null, $property_name, $decre_value = 1)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Update();
        $_SQL->isPreparedStatement = false;
        $sQuery = $_SQL->update($tablename)->set("$property_name=$property_name-$decre_value")->where($filter)->result();
        return DataObject::dao()->sqlExecute( $sQuery );
    }

    /**
     * 查询当前对象需显示属性的列表
     *
     * @param string $classname 数据对象类名
     * @param string 指定的显示属性，同SQL语句中的Select部分。
     * 示例如下:
     *
     *     id, name, commitTime
     *
     * @param mixed $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id = 1, name = 'sky'"
     *     1. array("id = 1", "name = 'sky'")
     *     2. array("id" => "1", "name" => "sky")
     *     3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *     1. id asc;
     *     2. name desc;
     *
     * @param string $limit 分页数量:limit起始数被改写，默认从1开始，如果是0，同Mysql limit语法；
     * 示例如下:
     *
     *     - 6, 10  从第6条开始取10条(如果是mysql的limit，意味着从第五条开始，框架里不是这个意义。)
     *     - 1, 10 (相当于第1-第10条)
     *     - 10 (相当于第1-第10条)
     *
     * @return mixed 对象列表数组
     */
    public static function showColumns($classname, $columns, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit = null)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Select();

        if ($sort == Crud_SQL::SQL_ORDER_DEFAULT_ID) {
            $realIdName = DataObjectSpec::getRealIDColumnName( $classname );
            $sort       = str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
        }
        $sQuery = $_SQL->select($columns)->from($tablename)->where($filter)->order($sort)->limit($limit)->result();
        return DataObject::dao()->sqlExecute( $sQuery );//,$classname
    }

    /**
     * 由标识删除指定ID数据对象
     * @param string $classname 数据对象类名
     * @param mixed $id 数据对象编号
     *
     * @return bool 是否删除成功
     */
    public static function deleteByID($classname,$id)
    {
        if (is_numeric($id)) {
            $tablename = Config_Db::orm($classname);
            $_SQL      = new Crud_Sql_Delete();
            $_SQL->isPreparedStatement = false;
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }

            $idColumn = DataObjectSpec::getRealIDColumnName( $classname );
            if (isset($idColumn)) {
                $sQuery = $_SQL->deletefrom($tablename)->where($idColumn . "='$id'")->result();
                return DataObject::dao()->sqlExecute( $sQuery );
            }
        }
        return false;
    }

    /**
     * 根据主键删除多条记录
     * @param string $classname 数据对象类名
     * @param array|string $ids 数据对象编号
     * 形式如下:
     *
     *     1. array:array(1, 2, 3, 4, 5)
     *     2. 字符串:1, 2, 3, 4
     *
     * @return bool 是否删除成功
     */
    public static function deleteByIds($classname, $ids)
    {
        $data = false;
        if (!empty($ids)) {
            $tablename = Config_Db::orm( $classname );
            $_SQL      = new Crud_Sql_Delete();
            $_SQL->isPreparedStatement = false;
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }
            if ($classname instanceof DataObject) {
                $idColumn = DataObjectSpec::getRealIDColumnName($classname);
            }
            if (isset($idColumn)) {
                $condition = " ";
                $ids = str_replace("(", "", $ids);
                $ids = str_replace(")", "", $ids);
                $ids = str_replace("'", "", $ids);
                if (is_string($ids)) {
                    $ids = explode(",", $ids);
                }
                if ($ids && (count($ids) > 0 )) {
                    $condition = " $idColumn=" . $ids[0] . " ";
                    for ($i = 1; $i < count($ids); $i++) {
                        if (!empty($ids[$i])) {
                            $condition .= " or $idColumn=" . $ids[$i] . " ";
                        }
                    }
                }
                $sQuery = $_SQL->deletefrom($tablename)->where($condition)->result();
                return DataObject::dao()->sqlExecute( $sQuery );
            }
        }
    }

    /**
     * 根据条件删除多条记录
     *
     * @param string $classname 数据对象类名
     * @param mixed $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @return bool 是否删除成功
     */
    public static function deleteBy($classname, $filter)
    {
        if (!empty($filter)) {
            $tablename = Config_Db::orm( $classname );
            $_SQL      = new Crud_Sql_Delete();
            $_SQL->isPreparedStatement = false;
            $sQuery = $_SQL->deletefrom($tablename)->where($filter)->result();
            return DataObject::dao()->sqlExecute( $sQuery );
        } else {
            return false;
        }
    }

    /**
     * 由标识判断指定ID数据对象是否存在
     * @param string $classname 数据对象类名
     * @param mixed $id 数据对象编号
     * @return bool 是否存在
     */
    public static function existByID($classname, $id)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Select();

        if (is_string($classname)) {
            if (class_exists($classname)) {
                $classname = new $classname();
            }
        }
        if ($classname instanceof DataObject) {
            $idColumn = DataObjectSpec::getRealIDColumnName( $classname );
        }
        if (isset($idColumn)) {
            $count_string = "count(1)";
            $sQuery       = $_SQL->select($count_string)->from($tablename)->where($idColumn . "='$id'")->result();
            $isExist      = DataObject::dao()->sqlExecute( $sQuery );
            if ($isExist > 0 ) return true; else return false;
        } else {
            return false;
        }
    }

    /**
     * 由标识判断指定ID数据对象是否存在
     * @param string $classname 数据对象类名
     * @param mixed $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @return bool 是否存在
     */
    public static function existBy($classname, $filter)
    {
        if (!empty($filter)) {
            $tablename = Config_Db::orm( $classname );
            $_SQL      = new Crud_Sql_Select();
            $count_string = "count(1)";
            $sQuery       = $_SQL->select($count_string)->from($tablename)->where($filter)->result();
            $isExist      = DataObject::dao()->sqlExecute( $sQuery );
            if ($isExist > 0 ) return true; else return false;
        } else {
            return false;
        }
    }

    /**
     * 对应数据对象的max方法
     * @param string $classname 数据对象类名
     * @param string $column_name 列名，默认为数据对象标识
     * @param object|string|array $filter 查询条件，在where后的条件
     * @return int 数据对象标识最大值
     */
    public static function max($classname, $column_name = null, $filter = null)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Select();
        if (empty($column_name)) {
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }
            $column_name = DataObjectSpec::getRealIDColumnName( $classname );
        }
        if (isset($column_name)) {
            $max_string = "max($column_name)";
            $sQuery     = $_SQL->select($max_string)->from($tablename)->where($filter)->result();
            return DataObject::dao()->sqlExecute( $sQuery );
        } else {
            return -1;
        }
    }

    /**
     * 数据对象指定列名最小值，如未指定列名，为标识最小值
     * @param string $classname 数据对象类名
     * @param string $column_name 列名，默认为数据对象标识
     * @param object|string|array $filter 查询条件，在where后的条件
     * @return int 数据对象列名最小值，如未指定列名，为标识最小值
     */
    public static function min($classname, $column_name = null, $filter = null)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Select();
        if (empty($column_name)) {
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }
            $column_name = DataObjectSpec::getRealIDColumnName( $classname );
        }
        if (isset($column_name)) {
            $min_string = "min($column_name)";
            $sQuery     = $_SQL->select($min_string)->from($tablename)->where($filter)->result();
            return DataObject::dao()->sqlExecute( $sQuery );
        } else {
            return -1;
        }
    }

    /**
     * 数据对象指定列名总数
     * @param string $classname 数据对象类名
     * @param string $column_name 列名
     * @param object|string|array $filter 查询条件，在where后的条件
     * @return int 数据对象列名总数
     */
    public static function sum($classname, $column_name, $filter = null)
    {
        $tablename = Config_Db::orm( $classname );
        $_SQL      = new Crud_Sql_Select();
        if (empty($column_name)) {
            if (is_string($classname)) {
                if (class_exists($classname)) {
                    $classname = new $classname();
                }
            }
            $column_name = DataObjectSpec::getRealIDColumnName( $classname );
        }
        if (isset($column_name)) {
            $sum_string = "sum($column_name)";
            $sQuery     = $_SQL->select($sum_string)->from($tablename)->where($filter)->result();
            return DataObject::dao()->sqlExecute( $sQuery );
        } else {
            return -1;
        }
    }

    /**
     * 对象总计数
     * @param string $classname 数据对象类名
     * @param object|string|array $filter
     *        $filter 格式示例如下: 
     *            0. "id = 1, name = 'sky'"
     *            1. array("id = 1", "name = 'sky'")
     *            2. array("id" => "1", "name" => "sky")
     *            3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     * @return int 对象总计数
     */
    public static function count($classname, $filter = null)
    {
        return DataObject::dao()->count( $classname, $filter );
    }

    /**
     * 对象分页
     * @param string $classname 数据对象类名
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件, 在where后的条件
     * 示例如下: 
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     * @param string $sort 排序条件
     * 默认为 id desc
     *
     * 示例如下: 
     *
     *        1. id asc;
     *        2. name desc;
     * @return mixed 对象分页
     */
    public static function queryPage($classname, $startPoint, $endPoint, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        if (is_string($filter) ) $filter = trim($filter);
        if (( $startPoint > $endPoint ) || ( $endPoint == 0)) return null;
        return DataObject::dao()->queryPage( $classname, $startPoint, $endPoint, $filter, $sort );
    }

    /**
     * 对象分页根据当前页数和每页显示记录数
     * @param string $classname 数据对象类名
     * @param int $pageNo  当前页数
     * @param int $pageSize 每页显示记录数
     * @param object|string|array $filter 查询条件, 在where后的条件
     * 示例如下: 
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     * 示例如下: 
     *
     *        1. id asc;
     *        2. name desc;
     *
     * @return array
     *  返回:
     *        - count    : 符合条件的记录总计数
     *        - pageCount: 符合条件的总页数
     *        - data     : 对象分页
     */
    public static function queryPageByPageNo($classname, $pageNo, $filter = null, $pageSize = 10, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        if (is_string($filter) ) $filter = trim($filter);
        $count = self::count( $classname, $filter );
        $data  = array();
        $pageCount = 0;
        if ($count > 0) {
            // 总页数
            $pageCount = floor(($count + $pageSize - 1) / $pageSize);
            if ($pageNo <= $pageCount) {
                $startPoint = ($pageNo - 1) * $pageSize + 1;
                if ($startPoint > $count) {
                    $startPoint = 0;
                }
                $endPoint = $pageNo * $pageSize;
                if ($endPoint > $count) {
                    $endPoint = $count;
                }
                $data = DataObject::dao()->queryPage( $classname, $startPoint, $endPoint, $filter, $sort );
            }
        }
        return array(
            "count"     => $count,
            "pageCount" => $pageCount,
            "data"      => $data
        );
    }

    /**
     * 对象总计数[多表关联查询]
     * @param string $classname 数据对象类名
     * @param string|array $from 来自多张表或者多个类[必须是数据对象类名], 在from后的多张表名, 表名之间以逗号[,]隔开
     *
     *        示例如下: 
     *            0. "table1, table2"
     *            1. array("table1", "table2")
     *            2. "class1, class2"
     *            3. array("class1", "class2")
     *
     * @param object|string|array $filter
     *
     *        $filter 格式示例如下: 
     *            0. 允许对象如new User(id = "1", name = "green");
     *            1. "id = 1", "name = 'sky'"
     *            2. array("id = 1", "name = 'sky'")
     *            3. array("id" => "1", "name" => "sky")
     *
     * @return int 对象总计数
     */
    public static function countMultitable($classname, $from, $filter = null)
    {
        return DataObject::dao()->countMultitable( $classname, $from, $filter );
    }

    /**
     * 对象分页[多表关联查询]
     * @param string $classname 数据对象类名
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param string|array $from 来自多张表或者多个类[必须是数据对象类名], 在from后的多张表名, 表名之间以逗号[,]隔开
     * 示例如下: 
     *
     *        0. "table1, table2"
     *        1. array("table1", "table2")
     *        2. "class1, class2"
     *        3. array("class1", "class2")
     *
     * @param object|string|array $filter 查询条件, 在where后的条件
     * 示例如下: 
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     * 示例如下: 
     *
     *        1. id asc;
     *        2. name desc;
     * @return mixed 对象分页
     */
    public static function queryPageMultitable($classname, $startPoint, $endPoint, $from, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        if (is_string($filter) ) $filter = trim($filter);
        if (( $startPoint > $endPoint ) || ( $endPoint == 0)) return null;
        return DataObject::dao()->queryPageMultitable( $classname, $startPoint, $endPoint, $from, $filter, $sort );
    }

    /**
     * 查询数据对象列表[多表关联查询]
     * @param string $classname 数据对象类名
     * @param string|array $from 来自多张表或者多个类[必须是数据对象类名], 在from后的多张表名, 表名之间以逗号[,]隔开
     * 示例如下: 
     *
     *        0. "table1, table2"
     *        1. array("table1", "table2")
     *        2. "class1, class2"
     *        3. array("class1", "class2")
     *
     * @param object|string|array $filter 查询条件, 在where后的条件
     * 示例如下: 
     *
     *        0. "id = 1, name = 'sky'"
     *        1. array("id = 1", "name = 'sky'")
     *        2. array("id" => "1", "name" => "sky")
     *        3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "( id = 1 and name = 'sky' ) or ( name like '%sky%' )"
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     * 示例如下: 
     *
     *        1. id asc;
     *        2. name desc;
     * @return mixed 对象分页
     */
    public static function getMultitable($classname, $from, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        if (is_string($filter) ) $filter = trim($filter);
        $startPoint = 0;
        $endPoint   = self::countMultitable( $classname, $from, $filter );
        if ($endPoint == 0 ) return null;
        return DataObject::dao()->queryPageMultitable( $classname, $startPoint, $endPoint, $from, $filter, $sort );
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="其他">
    /**
     * 根据数据对象的属性名获取属性名的显示。
     * @param mixed $data 数据对象数组|数据对象。如:array(user,user)
     * @param mixed $property_name  属性名【可以一次指定多个属性名】
     */
    public static function propertyShow($data, $class_name, $property_name)
    {
        if (!empty($class_name)) {
            $class_property_names = array();//$class_name
            if (is_string($property_name)) {
                $class_property_names[] = $property_name;
            } elseif (is_array($property_name)) {
                $class_property_names   = $property_name;
            }
            if (is_array($data) && (count($data) > 0)) {
                foreach ($data as $record) {
                    foreach ($class_property_names as $property_name) {
                        $record->{$property_name . "Show"} = call_user_func($class_name . "::" . $property_name . "Show", $record->$property_name);
                    }
                }
            } elseif (is_object($data)) {
                foreach ($class_property_names as $property_name)
                {
                    $data->{$property_name . "Show"} = call_user_func($class_name . "::" . $property_name . "Show", $data->$property_name);
                }
            }
        }
    }

    /**
     * 输出显示DataObject对象
     *
     * 通常以 echo $dataobject。
     * @param string $dataobject 当前数据对象
     */
    public static function toString($dataobject)
    {
        if (Gc::$dev_debug_on) {
            return print_pre($dataobject) . "";
        } else {
            if (is_object($dataobject)) {
                $classname  = $dataobject->classname();
                $result     = "<pre>";
                $result    .= $classname . " DataObject" . HH . "{" . HH;
                $dataobject = clone $dataobject;
                if (is_a($dataobject, "DataObject")) {
                    $dataobjectArr        = $dataobject->toArray();
                    $dataobjectProperties = UtilReflection::getClassPropertiesInfo( $dataobject );
                    foreach ($dataobjectArr as $key => $value)
                    {
                        $access = "";
                        if (array_key_exists($key, $dataobjectProperties)) {
                            $propertyInfo = $dataobjectProperties[$key];
                            if (!empty($propertyInfo) && array_key_exists("access", $propertyInfo)) {
                                $access = ":" . $propertyInfo["access"];
                            }
                        }
                        $result .= "      [" . $key . $access . "]" . " => " . $value . HH;
                    }
                }
                $result .= "}";
                $result .= "</pre>";
                return $result;
            }
            return "";
        }
    }

    /**
     * 将数据对象转换成Json类型格式
     * @param string $dataobject 当前数据对象
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
     * @return Json格式的数据格式的字符串。
     */
    public static function toJson($dataobject, $isAll = false)
    {
        $object_arr = UtilObject::object_to_array( $dataobject, $isAll );
        if ($isAll) {
            foreach ($object_arr as $key => $value) {
                if ($object_arr[$key] == null) {
                    $object_arr[$key] = "";
                }
            }
        }
        return json_encode($object_arr);
    }
    //</editor-fold>
}
