<?php

/**
 * -----------| 工具类: 数据库 |-----------
 * @category Betterlife
 * @package util.common
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class UtilDb extends Util
{
    /**
     * 获取含有指定关键词的表和列
     * @param string $keyword 关键词
     */
    public static function keywords_table_columns($keyword)
    {
        $result     = array();
        $tableList  = ManagerDb::newInstance()->dbinfo()->tableList();
        $fieldInfos = array();
        foreach ($tableList as $tablename) {
            $fieldInfoList = ManagerDb::newInstance()->dbinfo()->fieldInfoList($tablename);
            foreach ($fieldInfoList as $fieldname => $field) {
                $fieldInfos[$tablename][$fieldname]["Field"]   = $field["Field"];
                $fieldInfos[$tablename][$fieldname]["Type"]    = $field["Type"];
                $fieldInfos[$tablename][$fieldname]["Comment"] = $field["Comment"];
            }
        }
        $tableInfoList      = ManagerDb::newInstance()->dbinfo()->tableInfoList();
        $filterTableColumns = array();
        if (count($fieldInfos) > 0) {
            foreach ($fieldInfos as $tablename => $fieldInfo) {
                foreach ($fieldInfo as $fieldname => $field) {
                    $data = ManagerDb::newInstance()->dao()->sqlExecute("select count($fieldname) from $tablename where $fieldname like '%$keyword%'");
                    if ($data) {
                        $result[$tablename][] = $fieldname;
                    }
                }
            }
        }
        return $result;
    }
}
