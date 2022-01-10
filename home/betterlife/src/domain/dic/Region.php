<?php

/**
 * -----------| 地区 |-----------
 * @category Betterlife
 * @package dic
 * @author skygreen skygreen2001@gmail.com
 */
class Region extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var string
     * @access public
     */
    public $region_id;
    /**
     * 父地区标识
     * @var string
     * @access public
     */
    public $parent_id;
    /**
     * 地区名称
     * @var string
     * @access public
     */
    public $region_name;
    /**
     * 地区类型
     * 0:国家-country
     * 1:省-province
     * 2:市-city
     * 3:区-region
     *
     * @var enum
     * @access public
     */
    public $region_type;
    /**
     * 目录层级
     * @var string
     * @access public
     */
    public $level;
    //</editor-fold>

    /**
     * 从属一对一关系
     *
     * @var array
     */
    public static $belong_has_one = array(
        "region_p" => "Region"
    );
    /**
     * 规格说明
     *
     * 表中不存在的默认列定义:commitTime,updateTime
     *
     * 外键特殊定义声明: FOREIGN_ID
     * @var mixed
     */
    public $field_spec = array(
        EnumDataSpec::REMOVE => array(
            'commitTime',
            'updateTime'
        ),
        EnumDataSpec::FOREIGN_ID => array(
            "region_p" => "parent_id"
        )
    );

    /**
     * 显示地区类型
     * 0:国家-country
     * 1:省-province
     * 2:市-city
     * 3:区-region
     *
     */
    public function getRegion_typeShow()
    {
        return self::region_typeShow($this->region_type);
    }

    /**
     * 显示地区类型
     * 0:国家-country
     * 1:省-province
     * 2:市-city
     * 3:区-region
     *
     */
    public static function region_typeShow($region_type)
    {
        return EnumRegionType::region_typeShow($region_type);
    }

    /**
     * 最高的层次，默认为3
     */
    public static function maxlevel()
    {
        return Region::select("max(level)");//return 3;
    }

    /**
     * 显示父地区[全]
     */
    public function getRegionShowAll()
    {
        return self::regionShowAll($this->parent_id, $this->level);
    }

    /**
     * 显示父地区[全]
     * 注:采用了递归写法
     * @param int $parent_id 父地区标识
     * @param int $level 目录层级
     */
    public static function regionShowAll($parent_id, $level)
    {
        $region_p = Region::getById($parent_id);
        if ($level == 1) {
             $regionShowAll = $region_p->region_name;
        } else {
             $parent_id     = $region_p->parent_id;
             $regionShowAll = self::regionShowAll($parent_id, $level - 1) . "->" . $region_p->region_name;
        }
        return $regionShowAll;
    }
}
