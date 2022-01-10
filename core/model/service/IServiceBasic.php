<?php

/**
 * 提供业务方法服务的接口
 * @category Betterlife
 * @package core.model
 * @subpackage service
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
interface IServiceBasic
{
    /**
     * 保存数据对象
     * @param array $dataobject
     * @return int 保存对象记录的ID标识号
     */
    public function save($dataobject);

    /**
     * 更新当前对象
     * @param array $dataobject
     * @return boolen 是否更新成功；true为操作正常
     */
    public function update($dataobject);

    /**
     * 由标识删除指定ID数据对象
     *
     * @param mixed $id
     */
    public function deleteByID($id);

    /**
     * 根据主键删除多条记录
     * @param string classname 数据对象类名
     * @param array|string $ids 数据对象编号
     * 形式如下:
     *
     *     1. array:array(1, 2, 3, 4, 5)
     *     2. 字符串:1,2,3,4
     */
    public function deleteByIds($ids);

    /**
     * 对属性进行递增
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1", "name"=>"sky")
     *      3. 允许对象如new User(id = "1", name = "green");
     * @param string property_name 属性名称
     * @param int incre_value 递增数
     */
    public function increment($property_name, $incre_value, $filter = null);

    /**
     * 对属性进行递减
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1", "name" => "sky")
     *      3. 允许对象如new User(id = "1", name = "green");
     * @param string property_name 属性名称
     * @param int decre_value 递减数
     */
    public function decrement($property_name, $decre_value, $filter = null);

    /**
     * 查询当前对象需显示属性的列表
     * @param string 指定的显示属性，同SQL语句中的Select部分。
     * 示例如下:
     *
     *     id, name, commitTime
     *
     * @param mixed $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1", "name" => "sky")
     *      3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "(id=1 and name='sky') or (name like 'sky')"
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *      1. id asc;
     *      2. name desc;
     *
     * @param string $limit 分页数量:limit起始数被改写，默认从1开始，如果是0，同Mysql limit语法；
     * 示例如下:
     *
     *      - 6,10  从第6条开始取10条(如果是mysql的limit，意味着从第五条开始，框架里不是这个意义。)
     *      - 1,10 (相当于第1-第10条)
     *      - 10 (相当于第1-第10条)
     * @return 对象列表数组
     */
    public function select($columns, $filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID, $limit = null);

    /**
     * 查询当前对象列表
     * @param string $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1", "name" => "sky")
     *      3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: "(id=1 and name='sky') or (name like 'sky')"
     *
     * @param string $sort 排序条件
     * 示例如下:
     *
     *      1. id asc;
     *      2. name desc;
     *
     * @param string $limit 分页数量:limit起始数被改写，默认从1开始，如果是0，同Mysql limit语法；
     * 示例如下:
     *
     *    - 6,10  从第6条开始取10条(如果是mysql的limit，意味着从第五条开始，框架里不是这个意义。)
     *    - 1,10 (相当于第1-第10条)
     *    - 10 (相当于第1-第10条)
     *
     * @return 对象列表数组
     */
    public function get($filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID, $limit = null);

    /**
     * 查询得到单个对象实体
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1", "name" => "sky")
     *      3. 允许对象如new User(id = "1", name = "green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @return 单个对象实体
     */
    public function getOne($filter = null);

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string $id
     * @return 对象
     */
    public function getById($id);

    /**
     * 对象总计数
     * @param object|string|array $filter
     * $filter 格式示例如下:
     *
     *      0. 允许对象如new User(id = "1", name = "green");
     *      1. "id=1", "name='sky'"
     *      2. array("id=1", "name='sky'")
     *      3. array("id" => "1", "name" => "sky")
     * @return int 对象总计数
     */
    public function count($filter = null);

    /**
     * 数据对象分页
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下:
     *
     *      0. "id=1, name='sky'"
     *      1. array("id=1", "name='sky'")
     *      2. array("id" => "1","name" => "sky")
     *      3. 允许对象如new User(id = "1",name = "green");
     *
     * 默认:SQL Where条件子语句。如: (id=1 and name='sky') or (name like 'sky')
     *
     * @param string $sort 排序条件
     * 默认为 id desc
     * 示例如下:
     *
     *      1. id asc;
     *      2. name desc;
     * @return mixed 对象分页
     */
    public function queryPage($startPoint, $endPoint, $filter = null, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID);
}
