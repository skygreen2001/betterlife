<?php
/**
 +---------------------------------------<br/>
 * 博客标签<br/>
 * 博客标签<br/>
 +---------------------------------------
 * @category betterlife
 * @package core.relation
 * @author skygreen skygreen2001@gmail.com
 */
class Blogtags extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $blogtags_id;
    /**
     * 博客编号
     * @var int
     * @access public
     */
    public $blog_id;
    /**
     * 标签编号
     * @var int
     * @access public
     */
    public $tags_id;
    //</editor-fold>

    /**
     * 从属一对一关系
     */
    static $belong_has_one=array(
        "blog"=>"Blog",
        "tags"=>"Tags"
    );
    /**
     * 规格说明
     * 表中不存在的默认列定义:commitTime,updateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE=>array(
            'commitTime',
            'updateTime'
        ),
    );

}

