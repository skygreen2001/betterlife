<?php

/**
 * -----------| 用户收到通知用户收到通知 |-----------
 * @category Betterlife
 * @package domain.msg.relation
 * @author skygreen skygreen2001@gmail.com
 */
class Usernotice extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $usernotice_id;
    /**
     * 用户编号
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 通知编号
     * @var int
     * @access public
     */
    public $notice_id;
    //</editor-fold>

    /**
     * 从属一对一关系
     * @var array
     */
    public static $belong_has_one = array(
        "user" => "User",
        "notice" => "Notice"
    );
}
