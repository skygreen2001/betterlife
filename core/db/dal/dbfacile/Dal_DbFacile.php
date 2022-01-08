<?php

/**
 * -----------| @todo 实现dbFacile通用的DAL访问方式 |-----------
 * @link https://github.com/alanszlosek/dbFacile
 * @category betterlife
 * @package core.db.dal
 * @subpackage dbfacile
 * @author skygreen
 * @todo
 */
class Dal_DbFacile extends Dal implements IDal
{
    /**
     * 连接数据库
     * @global string $ADODB_FETCH_MODE
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param mixed $dbtype 指定数据库类型。{该字段的值参考: EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考: EnumDbEngine}
     * @return mixed 数据库连接
     */
    public function connect($host = null, $port = null, $username = null, $password = null, $dbname = null, $dbtype = null, $engine = null)
    {
    }

    /**
     * 新建对象
     * @param Object $object
     * @return Object
     */
    public function save($object)
    {

    }


    /**
     * 删除对象
     * @param string $classname
     * @param int $id
     * @return Object
     */
    public function delete($object)
    {

    }

    /**
     * 更新对象
     * @param int $id
     * @param Object $object
     * @return Object
     */
    public function update($object)
    {

    }

    /**
     * 保存或更新当前对象
     * @param Object $dataobject
     * @return boolen|int 更新:是否更新成功；true为操作正常|保存:保存对象记录的ID标识号
     */
    public function saveOrUpdate($dataobject)
    {
    }

    /**
     * 根据对象实体查询对象列表
     *
     * @param string $object 需要查询的对象实体|类名称
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *     1.id asc;
     *     2.name desc;
     *
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下:
     *
     *    0,10
     * @return 对象列表数组
     */
    public function get($object, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit = null)
    {

    }

    /**
     * 查询得到单个对象实体
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *      1.id asc;
     *      2.name desc;
     *
     * @return 单个对象实体
     */
    public function get_one($object, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {

    }

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string $classname
     * @param string $id
     * @return 对象
     */
    public function get_by_id($object, $id)
    {

    }

    /**
     * 直接执行SQL语句
     * @param mixed $sql SQL查询|更新|删除语句
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @return array
     * 返回
     *     - 执行查询语句返回对象数组
     *     - 执行更新和删除SQL语句返回执行成功与否的true|null
     */
    public function sqlExecute($sqlstring, $object = null)
    {
    }

    /**
     * 对象总计数
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @return int 对象总计数
     */
    public function count($object, $filter = null)
    {

    }

    /**
     * 对象分页
     * @param string|class $object 需要查询的对象实体|类名称
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0."id=1,name='sky'"
     *     1.array("id=1","name='sky'")
     *     2.array("id"=>"1","name"=>"sky")
     *     3.允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     *
     * 示例如下:
     *
     *     1.id asc;
     *     2.name desc;
     *
     * @return mixed 对象分页
     */
    public function queryPage($object, $startPoint, $endPoint, $filter = null, $sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {

    }
}
?>
