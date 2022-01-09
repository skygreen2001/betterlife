<?php

/**
 * -----------| 枚举类型:状态 |-----------
 * @category betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumBlogStatus extends Enum
{
    /**
     * 状态:待审核
     */
    const PEND = '0';
    /**
     * 状态:进行中
     */
    const RUN = '1';
    /**
     * 状态:已结束
     */
    const END = '100';
    /**
     * 状态:已删除
     */
    const DEL = '400';

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
        switch ($status) {
            case self::PEND:
                return "待审核";
            case self::RUN:
                return "进行中";
            case self::END:
                return "已结束";
            case self::DEL:
                return "已删除";
        }
        return "未知";
    }

    /**
     * 根据状态显示文字获取状态
     * @param mixed $statusShow 状态显示文字
     */
    public static function statusByShow($statusShow)
    {
        switch ($statusShow) {
            case "待审核":
                return self::PEND;
            case "进行中":
                return self::RUN;
            case "已结束":
                return self::END;
            case "已删除":
                return self::DEL;
        }
        return self::PEND;
    }

    /**
     * 通过枚举值获取枚举键定义
     */
    public static function statusEnumKey($status)
    {
        switch ($status) {
            case '0':
                return "PEND";
            case '1':
                return "RUN";
            case '100':
                return "END";
            case '400':
                return "DEL";
        }
        return "PEND";
    }
}
