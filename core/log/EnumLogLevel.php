<?php
/**
 * -----------| 枚举类型: 日志级别 |-----------
 * 
 * 日志级别 从上到下，由低到高
 */
class EnumLogLevel extends Enum
{
    /**
     * 严重错误: 导致系统崩溃无法使用
     */
    const EMERG  = 0;
    /**
     * 警戒性错误: 必须被立即修改的错误
     */
    const ALERT  = 1;
    /**
     * 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
     */
    const CRIT   = 2;
    /**
     * 一般错误: 一般性错误
     */
    const ERR    = 3;
    /**
     * 警告性错误: 需要发出警告的错误
     */
    const WARN   = 4;
    /**
     * 通知: 程序可以运行但是还不够完美的错误
     */
    const NOTICE = 5;
    /**
     * 信息: 程序输出信息
     */
    const INFO   = 6;
    /**
     * 调试: 调试信息
     */
    const DEBUG  = 7;
    /**
     * SQL: SQL语句 注意只在调试模式开启时有效
     */
    const SQL    = 8;
}
