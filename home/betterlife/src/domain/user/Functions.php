<?php

/**
 * -----------| 功能信息 |-----------
 * @category Betterlife
 * @package domain.user
 * @author skygreen skygreen2001@gmail.com
 */
class Functions extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     *
     * 权限编号
     * @var int
     * @access public
     */
    public $functions_id;
    /**
     * 允许访问的URL权限
     * @var string
     * @access public
     */
    public $url;
    //</editor-fold>

    /**
     * 一对多关系
     * @var array
     */
    public static $has_many = array(
        "rolefunctionss" => "Rolefunctions"
    );

    /**
     * 从属于多对多关系
     * @var array
     */
    public static $belongs_many_many = array(
        "roles" => "Role"
    );
}
