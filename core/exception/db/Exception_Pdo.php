<?php

/**
 * -----------| Pdo异常处理类 |-----------
 * @category betterlife
 * @package core.exception.db
 * @author zhouyuepu
 */
class Exception_Pdo extends ExceptionDb {
    /**
     * PDO 异常记录: 记录PDO的异常信息
     * @param string $category 异常分类
     */
    public static function log($errorInfo, $object = null, $code = 0, $extra = null) {
        parent::recordException( $errorInfo, $object, $code, $extra );
    }
}
?>
