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
     * 是否系统设置
     * @var int
     * @access public
     */
    public $is_system;
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
    /**
     * 创建时间
     * @var date
     * @access public
     */
    public $creation_time;
    /**
     * 更新时间
     * @var date
     * @access public
     */
    public $last_modified;
    //</editor-fold>
    /**
     * 规格说明
     * 表中不存在的默认列定义:commitTime,updateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE=>array(
            'commitTime',
            'updateTime'
        )
    );

}

