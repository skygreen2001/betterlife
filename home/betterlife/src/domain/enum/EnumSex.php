<?php

/**
 * -----------| 枚举类型:会员性别 |-----------
 * @category Betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumSex extends Enum
{
    /**
     * 会员性别:女
     */
    const FEMALE = '0';
    /**
     * 会员性别:男
     */
    const MALE = '1';
    /**
     * 会员性别:待确认
     */
    const UNKNOWN = '-1';

    /**
     * 显示会员性别
     * - 0：女-female
     * - 1：男-male
     * - -1：待确认-unknown
     * - 默认男
     * @return string
     */
    public static function sexShow($sex)
    {
        switch ($sex) {
            case self::FEMALE:
                return "女";
            case self::MALE:
                return "男";
            case self::UNKNOWN:
                return "待确认";
        }
        return "未知";
    }

    /**
     * 根据会员性别显示文字获取会员性别
     * @param mixed $sexShow 会员性别显示文字
     * @return string
     */
    public static function sexByShow($sexShow)
    {
        switch ($sexShow) {
            case "女":
                return self::FEMALE;
            case "男":
                return self::MALE;
            case "待确认":
                return self::UNKNOWN;
        }
        return self::FEMALE;
    }

    /**
     * 通过枚举值获取枚举键定义
     * @return string
     */
    public static function sexEnumKey($sex)
    {
        switch ($sex) {
            case '0':
                return "FEMALE";
            case '1':
                return "MALE";
            case '-1':
                return "UNKNOWN";
        }
        return "FEMALE";
    }
}
