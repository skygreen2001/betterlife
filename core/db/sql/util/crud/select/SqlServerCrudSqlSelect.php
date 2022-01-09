<?php

/**
 * -----------| 比较直观可看的专用于SqlServer的SQL查询构造器 |-----------
 * @category betterlife
 * @package core.db.sql.util.crud.select
 * @subpackage sqlserver
 * @author skygreen
 */
class SqlServerCrudSqlSelect extends CrudSqlSelect
{
    public static function pageSql($startPoint, $endPoint, &$_SQL, $tablename, $saParams, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID)
    {
        $pageSize     = $endPoint - $startPoint + 1;//每页的记录个数
        $currentNo    = ( $startPoint - 1 ) / $pageSize + 1;//当前页数
        $selectclause = "top $pageSize *";
        $whereclause  = self::pageWhereSql($_SQL, $tablename, $saParams, $sort, $pageSize, $currentNo);
        $_SQL->select($selectclause);
        return $whereclause;
    }

    /**
     * 生成分页SQL语句
     */
    public static function getSql(&$_SQL, $tablename, $saParams, $sort = CrudSQL::SQL_ORDER_DEFAULT_ID, $limit = null)
    {
        $selectclause = "";
        $whereclause  = $saParams;
        if (!empty($limit)) {
            $pageOffset   = explode(",", $limit);
            $startPoint   = $pageOffset[0];
            $endPoint     = $pageOffset[1];
            $pageSize     = $endPoint - $startPoint + 1;//每页的记录个数
            $currentNo    = ( $startPoint - 1 ) / $pageSize + 1;//当前页数
            $selectclause = "top $pageSize *";
            $whereclause  = self::pageWhereSql($_SQL, $tablename, $saParams, $sort, $pageSize, $currentNo);
        }
        $_SQL->select($selectclause);
        return $whereclause;
    }

    private static function pageWhereSql(&$_SQL, $tablename, $saParams, $sort, $pageSize, $currentNo)
    {
        if (empty($sort) || $sort == 'null') {
            $sortclause = "";
        } else {
            $sortclause = CrudSQL::SQL_ORDERBY . $sort;
        }
        $_SQL->isPreparedStatement = false;
        $whereclause = $_SQL->where($saParams)->getWhereClause();
        $_SQL->initWhereClause();
        if ($currentNo > 1) {
            if ($whereclause) {
                $whereclause = $whereclause . CrudSQL::SQL_AND . " (id not in (select top " . ( $pageSize * ( $currentNo - 1)) . " id from $tablename " . CrudSQL::SQL_WHERE . $whereclause . $sortclause . "))";
            } else {
                $whereclause = " (id not in (select top " . ( $pageSize * ( $currentNo - 1)) . " id from $tablename " . $sortclause . "))";
            }
        }
        return $whereclause;
    }
}
