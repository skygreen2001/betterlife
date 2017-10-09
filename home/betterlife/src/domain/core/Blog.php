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
     * 是否公开<br/>
     * 0: 不公开<br/>
     * 1: 公开<br/>
     * 默认为1: 公开<br/>
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
     * 状态<br/>
     * 0:待审核-pend<br/>
     * 1:进行中-run<br/>
     * 100:已结束-end<br/>
     * 400:已删除-del<br/>
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
    static $belong_has_one=array(
        "user"=>"User",
        "category"=>"Category"
    );

    /**
     * 一对多关系
     */
    static $has_many=array(
        "comments"=>"Comment"
    );

    /**
     * 多对多关系
     */
    static $many_many=array(
        "tags"=>"Tag"
    );

    /**
     * 显示状态<br/>
     * 0:待审核-pend<br/>
     * 1:进行中-run<br/>
     * 100:已结束-end<br/>
     * 400:已删除-del<br/>
     * 
     */
    public function getStatusShow()
    {
        return self::statusShow($this->status);
    }

    /**
     * 显示状态<br/>
     * 0:待审核-pend<br/>
     * 1:进行中-run<br/>
     * 100:已结束-end<br/>
     * 400:已删除-del<br/>
     * 
     */
    public static function statusShow($status)
    {
        return EnumBlogStatus::statusShow($status);
    }


    /**
     * 是否公开<br/>
     * 0: 不公开<br/>
     * 1: 公开<br/>
     * 默认为1: 公开<br/>
     * 
     */
    public function isPublicShow()
    {
        if ( $this->isPublic ) {
            return "是";
        }
        return "否";
    }
}

