<?php
/**
 +---------------------------------------<br/>
 * 博客所属分类<br/>
 * 博客所属分类<br/>
 +---------------------------------------
 * @category betterlife
 * @package core.relation
 * @author skygreen skygreen2001@gmail.com
 */
class Blogcategory extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $blogcategory_id;
    /**
     * 博客编号
     * @var int
     * @access public
     */
    public $blog_id;
    /**
     * 分类编号
     * @var int
     * @access public
     */
    public $category_id;
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

