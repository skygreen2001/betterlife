<?php
/**
 +---------------------------------------<br/>
 * 标签<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Tags extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $tags_id;
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
    public $title;
    /**
     * 状态
     * @var string
     * @access public
     */
    public $status;
    //</editor-fold>

}

