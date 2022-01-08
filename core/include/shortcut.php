<?php

/**
 * -----------| 定义一些缩写函数快速调用的方法 |-----------
 * @category betterlife
 * @package include
 * @author skygreen2001
 */

/**
 * 自定义异常处理缩写表示
 * @param string $errorInfo 错误信息
 * @param object $object    发生错误信息的自定义类
 * @param int    $code      异常编码
 * @param string $extra     补充存在多余调试信息
 * @return void
 */
function x($errorInfo, $object = null, $code = 0, $extra = null)
{
    ExceptionMe::recordException($errorInfo, $object, $code, $extra);
}


/**
 * 记录日志
 * @param string $message 日志记录的内容
 * @param enum $level 日志记录级别
 * @param string $category 日志内容业务分类
 * @return void
 */
function logme($message, $level = EnumLogLevel::INFO, $category = '')
{
    LogMe::log($message, $level, $category);
}
