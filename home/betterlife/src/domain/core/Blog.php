<?php

/**
 * -----------| 博客 |-----------
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
     * 排序
     * @var int
     * @access public
     */
    public $sequenceNo;
    /**
     * 分类编号
     * @var int
     * @access public
     */
    public $category_id;
    /**
     * 封面
     * @var string
     * @access public
     */
    public $icon_url;
    /**
     * 是否公开
     * 0: 不公开
     * 1: 公开
     * 默认为1: 公开
     *
     * @var bit
     * @access public
     */
    public $isPublic;
    /**
     * 博客内容
     * @var string
     * @access public
     */
    public $blog_content;
    /**
     * 状态
     * 0:待审核-pend
     * 1:进行中-run
     * 100:已结束-end
     * 400:已删除-del
     *
     * @var enum
     * @access public
     */
    public $status;
    /**
     * 发布日期
     * @var date
     * @access public
     */
    public $publish_date;
    //</editor-fold>

    /**
     * 从属一对一关系
     */
    static $belong_has_one = array(
        "user" => "User",
        "category" => "Category"
    );

    /**
     * 一对多关系
     */
    static $has_many = array(
        "comments" => "Comment",
        "blogtagss" => "Blogtags"
    );

    /**
     * 多对多关系
     */
    static $many_many = array(
        "tagss" => "Tags"
    );

    /**
     * 显示状态
     * 0:待审核-pend
     * 1:进行中-run
     * 100:已结束-end
     * 400:已删除-del
     *
     */
    public function getStatusShow()
    {
        return self::statusShow($this->status);
    }

    /**
     * 显示状态
     * 0:待审核-pend
     * 1:进行中-run
     * 100:已结束-end
     * 400:已删除-del
     *
     */
    public static function statusShow($status)
    {
        return EnumBlogStatus::statusShow($status);
    }


    /**
     * 是否公开
     * 0: 不公开
     * 1: 公开
     * 默认为1: 公开
     *
     */
    public function isPublicShow()
    {
        if ($this->isPublic) {
            return "是";
        }
        return "否";
    }
}
