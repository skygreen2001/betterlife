<?php
/**
 +---------------------------------------<br/>
 * 博客分类<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Category extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $category_id;
    /**
     * 序号
     * @var int
     * @access public
     */
    public $sequence_no;
    /**
     * 名称
     * @var string
     * @access public
     */
    public $name;
    /**
     * 图标
     * @var string
     * @access public
     */
    public $icon_url;
    /**
     * 说明
     * @var string
     * @access public
     */
    public $intro;
    /**
     * 状态
     * @var string
     * @access public
     */
    public $status;
    //</editor-fold>

    /**
     * 一对多关系
     */
    static $has_many=array(
        "blogs"=>"Blog"
    );

}

