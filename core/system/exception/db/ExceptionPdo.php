<?php

/**
 * -----------| Pdo异常处理类 |-----------
 * @category Betterlife
 * @package core.system.exception.db
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class ExceptionPdo extends ExceptionDb
{
    /**
     * PDO 异常记录: 记录PDO的异常信息
     * @param string $category 异常分类
     */
    public static function log($errorInfo, $object = null, $code = 0, $extra = null)
    {
        parent::recordException($errorInfo, $object, $code, $extra);
    }
}
