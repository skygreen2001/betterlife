<?php

/**
 * -----------| 评论 |-----------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Comment extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $comment_id;
    /**
     * 评论者标识
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 评论
     * @var string
     * @access public
     */
    public $comment;
    /**
     * 博客标识
     * @var int
     * @access public
     */
    public $blog_id;
    //</editor-fold>

    /**
     * 从属一对一关系
     */
    static $belong_has_one = array(
        "user" => "User",
        "blog" => "Blog"
    );

}

