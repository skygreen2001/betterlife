<?php
/**
 * -----------| 用户日志 |-----------
 * @category betterlife
 * @package log
 * @author skygreen skygreen2001@gmail.com
 */
class Loguser extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $loguser_id;
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 类型
     * 1:登录-LOGIN
     * 2:写日志-BLOG
     * 3:写评论-COMMENT
     * @var enum
     * @access public
     */
    public $userType;
    /**
     * 日志详情
     * 一般日志类型决定了内容；这一栏一般没有内容
     * @var string
     * @access public
     */
    public $log_content;
    //</editor-fold>

    /**
     * 从属一对一关系
     */
    static $belong_has_one = array(
        "user" => "User"
    );
    /**
     * 规格说明
     * 表中不存在的默认列定义:updateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE => array(
            'updateTime'
        ),
    );

    /**
     * 显示类型
     * 1:登录-LOGIN
     * 2:写日志-BLOG
     * 3:写评论-COMMENT
     */
    public function getUserTypeShow()
    {
        return self::userTypeShow( $this->userType );
    }

    /**
     * 显示类型
     * 1:登录-LOGIN
     * 2:写日志-BLOG
     * 3:写评论-COMMENT
     */
    public static function userTypeShow($userType)
    {
        return EnumUserType::userTypeShow( $userType );
    }

}

