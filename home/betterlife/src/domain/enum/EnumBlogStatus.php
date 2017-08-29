<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:状态  <br/>
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumBlogStatus extends Enum
{
    /**
     * 状态:待审核-new
     */
    const 0  ='0  ';
    /**
     * 状态:进行中-run
     */
    const 1   ='1   ';
    /**
     * 状态:已结束
     */
    const END='100';
    /**
     * 状态:已删除
     */
    const DEL='400';

    /**
     * 显示状态<br/>
     * 0  :待审核-new<br/>
     * 1   :进行中-run<br/>
     * 100:已结束-end<br/>
     * 400:已删除-del<br/>
     * <br/>
     */
    public static function statusShow($status)
    {
        switch($status){
            case self::0  :
                return "待审核-new";
            case self::1   :
                return "进行中-run";
            case self::END:
                return "已结束";
            case self::DEL:
                return "已删除";
        }
        return "未知";
    }

    /**
     * 根据状态显示文字获取状态<br/>
     * @param mixed $statusShow 状态显示文字
     */
    public static function statusByShow($statusShow)
    {
        switch($statusShow){
            case "待审核-new":
                return self::0  ;
            case "进行中-run":
                return self::1   ;
            case "已结束":
                return self::END;
            case "已删除":
                return self::DEL;
        }
        return self::0  ;
    }

    /**
     * 通过枚举值获取枚举键定义<br/>
     */
    public static function statusEnumKey($status)
    {
        switch($status){
            case '0  ':
                return "0  ";
            case '1   ':
                return "1   ";
            case '100':
                return "END";
            case '400':
                return "DEL";
        }
        return "0  ";
    }

}

