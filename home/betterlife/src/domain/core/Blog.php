<?php
/**
 +---------------------------------------<br/>
 * 博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Blog extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $blog_id;
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 博客标题
     * @var string
     * @access public
     */
    public $blog_name;
    /**
     * 博客内容
     * @var string
     * @access public
     */
    public $blog_content;
    //</editor-fold>

    /**
     * 从属一对一关系
     */
    static $belong_has_one=array(
        "user"=>"User"
    );

    /**
     * 一对多关系
     */
    static $has_many=array(
        "comments"=>"Comment"
    );

}

