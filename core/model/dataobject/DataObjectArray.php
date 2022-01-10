<?php

/**
 * -----------| 数组对象 |-----------
 *
 * 可以以数组的方式访问数据对象
 * @category Betterlife
 * @package core.model
 * @subpackage dataobject
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class DataObjectArray extends BBObject implements ArrayAccess
{
    //<editor-fold defaultstate="collapsed" desc="魔术方法">
    /**
    * 从数组创建对象。
    * @param mixed $array
    * @return DataObject
    */
    public function __construct($array = null)
    {
        if (!empty($array) && is_array($array)) {
            foreach ($array as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * 可设定对象未定义的成员变量[但不建议这样做]
     *
     * 类定义变量访问权限设定需要是pulbic
     * @param mixed $property 属性名
     * @return mixed 属性值
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * 可设定对象未定义的成员变量[但不建议这样做]
     *
     * 类定义变量访问权限设定需要是pulbic
     * @param mixed $property 属性名
     * @param mixed $value 属性值
     */
    public function __set($property, $value)
    {
        return $this->$property = $value;
    }

     /**
     * 打印当前对象的数据结构
     * @return string 描述当前对象。
     */
    public function __toString()
    {
        return DataObjectFunc::toString($this);
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="定义数组进入对象方式">
    /**
     * Whether a offset exists
     *
     * @param mixed $key — An offset to check for.
     * @access public
     * @return bool
     * @abstracting ArrayAccess
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($key)
    {
        if ($this->$key) {
            return true;
        }
        return method_exists($this, $key);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $key — The offset to retrieve.
     * @access public
     * @return mixed — Can return all value types.
     * @abstracting ArrayAccess
     */
    public function offsetGet($key)
    {
        return $this->$key;
    }

    /**
     * Offset to set
     * @param mixed $key — The offset to assign the value to.
     * @param mixed $value — The value to set.
     * @access public
     * @return void
     * @abstracting ArrayAccess
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Offset to unset
     * @param mixed $key — The offset to unset.
     * @access public
     * @return void
     * @abstracting ArrayAccess
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->$key);
    }
    //</editor-fold>
}
