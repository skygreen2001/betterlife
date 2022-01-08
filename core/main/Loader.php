<?php

/**
 * -----------| 加载网站内的类【在系统里只需动态加载一次的对象】 |-----------
 *
 * 采用Singleton模式
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Loader {
    const CLASS_CACHE = "Cache";
    const CLASS_MODEL = "Model";
    const CLASS_VIEW  = "View";
    private static $loaded = array();

    /**
     * @param Object $object
     * @param $param1 构造对象时传入的参数
     * @param $param2 构造对象时传入的参数
     * @return Object
     */
    public static function load($object, $param1 = null, $param2 = null) {
        $valid = array(
                self::CLASS_CACHE,
                self::CLASS_MODEL,
                self::CLASS_VIEW
        );
        if (!in_array($object, $valid)) {
            if (Gc::$dev_debug_on) {
                ExceptionMe::backtrace();
            }
            if (Gc::$language == Config_C::LANGUAGE_EN_US) {
                $error_info = "Not a valid object '{$object}' to load!";
            } else {
                $error_info = "不是有效的可以加载的 '{$object}'!";
            }
            x( $error_info, new Loader() );
        }
        if (empty(self::$loaded[$object])) {
            if (empty($param1)) {
                self::$loaded[$object] = new $object();
            } else {
                self::$loaded[$object] = new $object( $param1, $param2 );
            }
        }
        return self::$loaded[$object];
    }

    /**
     * 获取网站物理路径
     * @return string 获取路径
     */
    public static function basePath() {
        return getcwd();
    }
}