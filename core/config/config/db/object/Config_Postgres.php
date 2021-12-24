<?php
/**
 * -----------| Postgres的配置类 |-----------
 * @link http://blogs.techrepublic.com.com/howdoi/?p=110
 * @link http://neilconway.org/docs/sequences/
 * @category betterlife
 * @package core.config.db
 * @subpackage object
 * @author skygreen
 */
class Config_Postgres extends Config_Db {   
    /**
     * @var type 获取数据的模式
     * PGSQL_ASSOC, PGSQL_NUM and PGSQL_BOTH
     */
    public static $fetchmode = PGSQL_ASSOC;

}
?>
