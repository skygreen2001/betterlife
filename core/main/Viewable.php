<?php
/**
 * -----------| 所有显示工具类的父类 |-----------
 * @category betterlife
 * @package web.back
 * @author skygreen
 */
class Viewable extends BBObject implements ArrayAccess
{
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
    public function offsetExists($key) {
        $method = "get" . ucfirst($key);
        return method_exists($this, $method);
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
        $method = "get" . ucfirst($key);
        return $this->$method();
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
        $method = "set".ucfirst($key);
        $this->$method( $value );
//        $this->$key = $value;
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
