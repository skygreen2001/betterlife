<?php

/**
 * -----------| MysqlI异常处理类 |-----------
 * @category betterlife
 * @package core.exception.db
 * @author zhouyuepu
 */
class Exception_Mysqli extends ExceptionDb
{
    /**
     * MysqlI 异常记录: 记录Myql的异常信息
     * @param string $extra  补充存在多余调试信息
     * @param string $category 异常分类
     * @return void
     */
    public static function record($extra = null, $category = null, $link = null)
    {
        if ($link == null) {
            if (mysqli_connect_errno()) {
                $error_info = "连接数据库失败:" . mysqli_connect_error();
                $category   = ExceptionDb::CATEGORY_MYSQL;
                LogMe::log($error_info, EnumLogLevel::ERR);
                self::recordException($error_info, $category, mysqli_connect_errno(), $extra);
            } else {
                $link = ManagerDb::newInstance()->currentdao()->getConnection();
            }
        }
        if ($link && is_object($link) && $link->error) {
            if (!isset($category)) {
                $category = ExceptionDb::CATEGORY_MYSQL;
            }
            $errorinfo = $link->error;
            LogMe::log("错误信息:" . $errorinfo, EnumLogLevel::ERR);
            self::recordException($errorinfo, $category, $link->errno, $extra);
        }
    }
}
