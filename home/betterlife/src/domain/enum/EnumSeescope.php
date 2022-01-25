<?php

/**
 * -----------| 枚举类型:视野 |-----------
 * @category Betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumSeescope extends Enum
{
    /**
     * 视野:只能查看自己的信息
     */
    const SELF = '0';
    /**
     * 视野:查看所有的信息
     */
    const ALL = '1';

    /**
     * 显示视野
     * - 0:只能查看自己的信息-self
     * - 1:查看所有的信息-all
     * @return string
     */
    public static function seescopeShow($seescope)
    {
        switch ($seescope) {
            case self::SELF:
                return "只能查看自己的信息";
            case self::ALL:
                return "查看所有的信息";
        }
        return "未知";
    }

    /**
     * 根据视野显示文字获取视野
     * @param mixed $seescopeShow 视野显示文字
     * @return string
     */
    public static function seescopeByShow($seescopeShow)
    {
        switch ($seescopeShow) {
            case "只能查看自己的信息":
                return self::SELF;
            case "查看所有的信息":
                return self::ALL;
        }
        return self::SELF;
    }

    /**
     * 通过枚举值获取枚举键定义
     * @return string
     */
    public static function seescopeEnumKey($seescope)
    {
        switch ($seescope) {
            case '0':
                return "SELF";
            case '1':
                return "ALL";
        }
        return "SELF";
    }
}
