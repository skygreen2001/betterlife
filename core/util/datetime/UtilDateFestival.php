<?php

/**
 * -----------| 功能:处理节假日的方法 |-----------
 * @category betterlife
 * @package util.common
 * @subpackage datetime
 * @author skygreen
 */
class UtilDateFestival extends Util
{
    /**
     * 元旦在指定年的日期
     * @param string $year 年。如:2012
     */
    public static function newyear($year)
    {
        //获得下一次元旦的日期
        $nowyear = $year;
        $month   = "01";
        $day     = "01";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否元旦
     * @param string $date 日期 example 2012-01-01
     * @return bool 是否是元旦
     */
    public static function isNewyear($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::newyear($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 春节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function chineseNewyear($year)
    {
        //获取下一次春节的日期
        $nowyear    = $year;
        $lunarmonth = "01";
        $lunarday   = "01";
        $date       = UtilDateLunar::convertLunarToSolar($nowyear, $lunarmonth, $lunarday);

        return $date;
    }

    /**
     * 指定日期是否春节
     * @param string $date 日期 example 2012-01-01
     * @return bool 是否是春节
     */
    public static function isChineseNewyear($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::chineseNewyear($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 妇女节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function womenday($year)
    {
        $nowyear = $year;
        $month   = "03";
        $day     = "08";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否妇女节
     * @param string $date 日期
     * @return bool 是否是妇女节
     */
    public static function isWomenday($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::womenday($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 国庆节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function nationalDay($year)
    {
        $nowyear = $year;
        $month   = "10";
        $day     = "01";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否国庆节
     * @param string $date 日期
     * @return bool 是否是国庆节
     */
    public static function isNationalDay($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival = self::nationalDay($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 教师节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function teacherDay($year)
    {
        $nowyear = $year;
        $month   = "09";
        $day     = "10";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否教师节
     * @param string $date 日期
     * @return bool 是否是教师节
     */
    public static function isTeacherDay($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::teacherDay($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 劳动节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function laborDay($year)
    {
        $nowyear = $year;
        $month   = "05";
        $day     = "01";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否劳动节
     * @param string $date 日期
     * @return bool 是否是劳动节
     */
    public static function isLaborDay($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::laborDay($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 母亲节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function motherDay($year)
    {
        $nowyear = $year;
        $month   = "05";
        $day     = "01";
        //得到今年的5月1号是星期几
        $w   = date('w', strtotime($nowyear . "-05" . "-01"));
        $day = 14 - $w + $day;

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否母亲节
     * @param string $date 日期
     * @return bool 是否是母亲节
     */
    public static function isMotherDay($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::motherDay($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 圣诞节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function christmas($year)
    {
        $nowyear = $year;
        $month   = "12";
        $day     = "24";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否圣诞节
     * @param string $date 日期
     * @return bool 是否是圣诞节
     */
    public static function isChristmas($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::christmas($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 端午节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function dragonboat($year)
    {
        $nowyear    = $year;
        $lunarmonth = "05";
        $lunarday   = "05";
        $date       = UtilDateLunar::convertLunarToSolar($nowyear, $lunarmonth, $lunarday);

        return $date;
    }

    /**
     * 指定日期是否端午节
     * @param string $date 日期
     * @return bool 是否是端午节
     */
    public static function isDragonboat($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::dragonboat($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 儿童节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function childrenday($year)
    {
        $nowyear = $year;
        $month   = "06";
        $day     = "01";

        return array($nowyear, $month, $day);
    }

    /**
     * 指定日期是否儿童节
     * @param string $date 日期
     * @return bool 是否是儿童节
     */
    public static function isChildrenday($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::childrenday($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 元宵节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function lantern($year)
    {
        $nowyear    = $year;
        $lunarmonth = "01";
        $lunarday   = "15";
        $date       = UtilDateLunar::convertLunarToSolar($nowyear, $lunarmonth, $lunarday);

        return $date;
    }

    /**
     * 指定日期是否元宵节
     * @param string $date 日期
     * @return bool 是否是元宵节
     */
    public static function isLantern($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::isLantern($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 中秋节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function midAutumn($year)
    {
        $nowyear    = $year;
        $lunarmonth = "08";
        $lunarday   = "15";
        $date       = UtilDateLunar::convertLunarToSolar($nowyear, $lunarmonth, $lunarday);

        return $date;
    }

    /**
     * 指定日期是否中秋节
     * @param string $date 日期
     * @return bool 是否是中秋节
     */
    public static function isMidAutumn($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival = self::midAutumn($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 重阳节在指定年的日期
     * @param string $year 年。如:2012
     * @return array
     */
    public static function doubleninth($year)
    {
        $nowyear    = $year;
        $lunarmonth = "09";
        $lunarday   = "09";
        $date       = UtilDateLunar::convertLunarToSolar($nowyear, $lunarmonth, $lunarday);

        return $date;
    }

    /**
     * 指定日期是否重阳节
     * @param string $date 日期
     * @return bool 是否是重阳节
     */
    public static function isDoubleninth($date)
    {
        $flag = false;
        $year = substr($date, 0, 4);
        $nowfesstival       = self::doubleninth($year);
        $$nowfesstival_date = $nowfesstival[0] . "-" . $nowfesstival[1] . "-" . $nowfesstival[2];

        if ($$nowfesstival_date == $date) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 测试验证方法的正确性
     */
    public static function main()
    {
        $year   = Date("Y");
        $result = array();

        $newyear        = UtilDateFestival::newyear($year);
        $chineseNewyear = UtilDateFestival::chineseNewyear($year);
        $womenday       = UtilDateFestival::womenday($year);
        $nationalDay    = UtilDateFestival::nationalDay($year);
        $teacherDay     = UtilDateFestival::teacherDay($year);
        $laborDay       = UtilDateFestival::laborDay($year);
        $motherDay      = UtilDateFestival::motherDay($year);
        $christmas      = UtilDateFestival::christmas($year);
        $dragonboat     = UtilDateFestival::dragonboat($year);
        $childrenday    = UtilDateFestival::childrenday($year);
        $lantern        = UtilDateFestival::lantern($year);
        $midAutumn      = UtilDateFestival::midAutumn($year);
        $doubleninth    = UtilDateFestival::doubleninth($year);
        $result['newyear']        = $newyear;
        $result['chineseNewyear'] = $chineseNewyear;
        $result['womenday']       = $womenday;
        $result['nationalDay']    = $nationalDay;
        $result['teacherDay']     = $teacherDay;
        $result['laborDay']       = $laborDay;
        $result['motherDay']      = $motherDay;
        $result['christmas']      = $christmas;
        $result['dragonboat']     = $dragonboat;
        $result['childrenday']    = $childrenday;
        $result['lantern']        = $lantern;
        $result['midAutumn']      = $midAutumn;
        $result['doubleninth']    = $doubleninth;

        print_r($result);
    }
}
