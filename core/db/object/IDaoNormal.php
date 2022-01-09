<?php

/**
 * -----------| 统一的数据库操作接口定义 |-----------
 *
 * 定义如下:
 *    - 通用SQL的执行
 *    - 单个对象【单张表】的增删改查
 *    - 列表对象的查询
 *    - 统计函数的执行
 * @category betterlife
 * @package core.db.object
 * @author skygreen
 */
interface IDaoNormal
{
    //<editor-fold defaultstate="collapsed" desc="需要实现的方法">
    /**
     * 保存新建对象
     * @param Object $object
     * @return int 保存对象记录的ID标识号
     */
    public function save($object);

    /**
     * 删除对象
     * @param string $classname
     * @param int $id
     * @return Object
     */
    public function delete($object);

    /**
     * 更新对象
     * @param int $id
     * @param Object $object
     * @return Object
     */
    public function update($object);

    /**
     * 保存或更新当前对象
     * @param Object $dataobject
     * @return boolen|int 更新:是否更新成功；true为操作正常|保存:保存对象记录的ID标识号
     */
    public function saveOrUpdate($object);

    /**
     * 根据对象实体查询对象列表
     *
     * @param string $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如:(id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *     1. id asc;
     *     2. name desc;
     *
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下:
     *
     *    0,10
     *
     * @return array 对象列表数组
     */
    public function get($object, $filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID, $limit = null);

    /**
     * 查询得到单个对象实体
     *
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如:(id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *     1. id asc;
     *     2. name desc;
     * @return object 单个对象实体
     */
    public function getOne($object, $filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID);

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string|class $object 需要查询的对象实体|类名称
     * @param string $id
     * @return object 对象
     */
    public function getById($object, $id);

    /**
     * 直接执行SQL语句
     *
     * @param mixed $sql SQL查询|更新|删除语句
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @return array
     * 返回
     *     1. 执行查询语句返回对象数组
     *     2. 执行更新和删除SQL语句返回执行成功与否的true|null
     * @return mixed 返回数据
     */
    public function sqlExecute($sql, $object = null);

    /**
     * 对象总计数
     *
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如:(id=1 and name='sky') or (name like 'sky')
     *
     * @return 对象总计数
     */
    public function count($object, $filter = null);

    /**
     * 对象分页
     *
     * @param string|class $object 需要查询的对象实体|类名称
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *     0. "id=1,name='sky'"
     *     1. array("id=1","name='sky'")
     *     2. array("id"=>"1","name"=>"sky")
     *     3. 允许对象如new User(id="1",name="green");
     *
     * 默认:SQL Where条件子语句。如:(id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     *
     * 示例如下:
     *
     *      1.id asc;
     *      2.name desc;
     *
     * @return mixed 对象分页
     */
    public function queryPage($object, $startPoint, $endPoint, $filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID);
    //</editor-fold>
}
