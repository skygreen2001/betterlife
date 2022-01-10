<?php

/**
 * -----------| 系统日志 |-----------
 * @category Betterlife
 * @package log
 * @author skygreen skygreen2001@gmail.com
 */
class Logsystem extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $logsystem_id;
    /**
     * 日志记录时间
     * @var date
     * @access public
     */
    public $logtime;
    /**
     * 分类 
     *
     * 标志或者分类
     * @var string
     * @access public
     */
    public $ident;
    /**
     * 优先级 
     *
     * - 0: 严重错误-EMERG 
     * - 1: 警戒性错误-ALERT 
     * - 2: 临界值错误-CRIT 
     * - 3: 一般错误-ERR 
     * - 4: 警告性错误-WARN 
     * - 5: 通知-NOTICE 
     * - 6: 信息-INFO 
     * - 7: 调试-DEBUG 
     * - 8: SQL-SQL
     *
     * @var enum
     * @access public
     */
    public $priority;
    /**
     * 日志内容
     * @var string
     * @access public
     */
    public $message;
    //</editor-fold>
    /**
     * 规格说明
     * 表中不存在的默认列定义:commitTime,updateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE => array(
            'commitTime',
            'updateTime'
        ),
    );

    /**
     * 显示优先级 
     * - 0: 严重错误-EMERG 
     * - 1: 警戒性错误-ALERT 
     * - 2: 临界值错误-CRIT 
     * - 3: 一般错误-ERR 
     * - 4: 警告性错误-WARN 
     * - 5: 通知-NOTICE 
     * - 6: 信息-INFO 
     * - 7: 调试-DEBUG 
     * - 8: SQL-SQL
     */
    public function getPriorityShow()
    {
        return self::priorityShow( $this->priority );
    }

    /**
     * 显示优先级 
     * 0:严重错误-EMERG 
     * 1:警戒性错误-ALERT 
     * 2:临界值错误-CRIT 
     * 3:一般错误-ERR 
     * 4:警告性错误-WARN 
     * 5:通知-NOTICE 
     * 6:信息-INFO 
     * 7:调试-DEBUG 
     * 8:SQL-SQL
     */
    public static function priorityShow($priority)
    {
        return EnumPriority::priorityShow( $priority );
    }

}

