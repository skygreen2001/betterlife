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
     * 博客头像
     * @var string
     * @access public
     */
    public $icon_url;
    /**
     * 博客内容
     * @var string
     * @access public
     */
    public $blog_content;
    /**
     * 状态<br/>
     * 0  :待审核-new<br/>
     * 1   :进行中-run<br/>
     * 100:已结束-end<br/>
     * 400:已删除-del<br/>
     *
     * @var enum
     * @access public
     */
    public $status;
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
