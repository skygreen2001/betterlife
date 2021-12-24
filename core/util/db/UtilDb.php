<?php
/**
 * -----------| 工具类: 数据库 |-----------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilDb extends Util
{
    /**
     * 获取含有指定关键词的表和列
     * @param string $keyword 关键词
     */
    public static function keywords_table_columns($keyword) {
        $result     = array(); 
        $tableList  = Manager_Db::newInstance()->dbinfo()->tableList();
        $fieldInfos = array();
        foreach ($tableList as $tablename) {
            $fieldInfoList = Manager_Db::newInstance()->dbinfo()->fieldInfoList( $tablename );
            foreach ($fieldInfoList as $fieldname => $field) {
                $fieldInfos[$tablename][$fieldname]["Field"]   = $field["Field"];
                $fieldInfos[$tablename][$fieldname]["Type"]    = $field["Type"];
                $fieldInfos[$tablename][$fieldname]["Comment"] = $field["Comment"];
            }
        }
        $tableInfoList      = Manager_Db::newInstance()->dbinfo()->tableInfoList();
        $filterTableColumns = array();
        if ( count($fieldInfos) > 0 ) {
            foreach ($fieldInfos as $tablename => $fieldInfo) 
            {
                foreach ($fieldInfo as $fieldname => $field)
                {
                    $data = Manager_Db::newInstance()->dao()->sqlExecute( "select count($fieldname) from $tablename where $fieldname like '%$keyword%'" );
                    if ( $data ) {
                        $result[$tablename][] = $fieldname;
                    }
                }
            }
        }
        return $result;
    }
}