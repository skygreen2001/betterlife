<?php
/**
 * -----------| 服务类:所有报表服务的父类 |-----------
 * @category report
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class ServiceReport extends Service
{
    /**
     * 解析SQL查询取出查的列名
     * @param string $reportSql
     * @return array
     */
    public static function getSqlSelCols($reportSql = "")
    {
        $reportSql = trim($reportSql);//去除首尾空格
        $reportSql = strtolower($reportSql);//转换成小写
        //解析sql取出查的字段名，以array输出
        $fromPos   = strpos($reportSql, " from");//获取from的位置
        if ( empty($fromPos) ) {
            $fromPos = stripos($reportSql, "	from");
        }
        if ( empty($fromPos) ) {
            $fromPos = stripos($reportSql, "from");
        }
        $reportSql = substr($reportSql, 0, $fromPos);//截取from前的字符串
        $reportSql = substr($reportSql, 7);//截取select后from前的字符串
        if ( contain( $reportSql, "from" ) ) {
            if ( !preg_match('/from(\s+)(\S*),/i', $reportSql) ) {
                $fromPos   = strpos($reportSql, "from");//获取from的位置
                $reportSql = substr($reportSql, 0, $fromPos);//截取from前的字符串
            }
        }

        // [PHP正则表达式来拆分SQL字段列表](http://cn.voidcc.com/question/p-fmqfqqts-ye.html)
        // [PHP SQL Parser](https://code.google.com/archive/p/php-sql-parser/)

        // 测试用例: $reportSql = 'field1,field2,field3,CONCAT(field1,field2) AS concatted,IF (field1 = field2,field3,field4) AS logic';
        $selCols   = preg_split ("/,(?![^()]*+\\))/", $reportSql); //以,分隔字符串
        $arr_output_header = array();
        foreach ( $selCols as $selCol) {
            $selCol    = trim($selCol);
            $newSelCol = preg_split('/( |as)/', $selCol, 0, PREG_SPLIT_NO_EMPTY);//正则匹配分隔字符串
            //以下用来判断select column列名是否被重命名 ( 根据newSelCol的size来判断 )
            if ( count($newSelCol) > 1) {
                // $newSelCol[1] = preg_replace("/('|`|\")/", "", $newSelCol[1]);
                // $arr_output_header[$newSelCol[1]] = $newSelCol[1];
                $lst_index = count($newSelCol) - 1;
                $newSelCol[$lst_index] = preg_replace("/('|`|\")/", "", $newSelCol[$lst_index]);
                $arr_output_header[$newSelCol[$lst_index]] = $newSelCol[$lst_index];
            } else {
                $newSelColStr   = $newSelCol[0];
                $newSelColArray = explode(".", $newSelColStr);
                //以下用来判断当前字段是否是关联表查询a.id格式 ( 根据$newSelColArray的size来判断 )
                if ( count($newSelColArray) > 1 ) {
                    $arr_output_header[$newSelColArray[1]] = $newSelColArray[1];
                } else {
                    $arr_output_header[$newSelColArray[0]] = $newSelColArray[0];
                }
            }
        }
        // unset($arr_output_header['updateTime'], $arr_output_header['commitTime']);
        return $arr_output_header;
    }

    /**
     * 解析SQL查询取出用户可筛选的字段
     * @param string $reportSql
     * @return array
     */
    public static function getFilterCols($reportSql = "")
    {
        $columns = self::getSqlSelCols($reportSql);
        $result  = array();
        foreach ($columns as $column)
        {
            if ( contains( strtolower($column), array("id", "name", "title") ) ) {
                $result[] = $column;
            }

            if ( contains( $column, array("名称", "标题", "标识", "编号") ) ) {
                $result[] = $column;
            }
        }
        return $result;
    }

    /**
     * 解析SQL查询取出用户可筛选的时间字段
     * @param string $reportSql
     * @return array
     */
    public static function getFilterTime($reportSql = "")
    {
        $columns = self::getSqlSelCols($reportSql);
        $result  = "";
        foreach ($columns as $column)
        {
            if ( contains( $column, array("date", "time") ) ) {
                $result = $column;
                break;
            }

            if ( contains( $column, array("时间", "年", "月", "日") ) ) {
                $result = $column;
                break;
            }
        }
        return $result;
    }

    /**
     * 解析SQL查询取出用户可排序的字段
     * @param string $reportSql
     * @return array
     */
    public static function getOrderBy($reportSql = "")
    {
        $result  = self::getFilterTime($reportSql);
        if ( empty($result) ) {
            $columns = self::getSqlSelCols($reportSql);
            foreach ($columns as $column)
            {
                if ( contains( strtolower($column), array("id") ) ) {
                    $result = $column;
                    break;
                }

                if ( contains( $column, array("标识", "编号") ) ) {
                    $result = $column;
                    break;
                }
            }
        }
        if ( !empty($result) ) {
            return " order by " . $result . " desc ";
        } else {
            return "";
        }
    }

    /**
     * 根据用户输入筛选条件生成sql where语句
     * @param string $sql_report 报表sql语句
     * @param string $query 用户输入语句
     * @param string $startDate 开始时间
     * @param string $endDate 结束时间
     * @param array $columns 列筛选条件
     * @return array
     */
    public static function getWhereClause($sql_report, $query, $startDate, $endDate, $columns = null)
    {
        $where_clause = "";
        if ( $sql_report ) $sql_report = str_replace(";", "", $sql_report);
        if ( !empty($query) && $query != "undefined" ) {
            $search_atom  = explode(" ", trim($query));
            $filterCols   = ServiceReport::getFilterCols( $sql_report );
            $where_sub    = array();
            for ($i=0; $i < count($filterCols); $i++) {
                $clause    = " ( ";
                $filterCol = $filterCols[$i];
                $satom_tmp = $search_atom;
                array_walk($satom_tmp, function(&$value, $key, $filterCol) {
                    $value = " $filterCol LIKE '%" . $value . "%' ";
                }, $filterCol);
                $clause .= implode(" and ", $satom_tmp);
                $clause .= " ) ";
                $where_sub[$i] = $clause;
            }
            if ( $where_sub && count($where_sub) > 0 ) {
                if ( count($where_sub) > 1 ) $where_clause = " ( ";
                $where_clause .= implode(" or ", $where_sub);
                if ( count($where_sub) > 1 ) $where_clause .= " ) ";
            }
        }

        if ( !empty($startDate) && !empty($endDate) ) {
            $filterTime  = ServiceReport::getFilterTime( $sql_report );
            if ( $filterTime ) {
                if ( !empty($where_clause) ) $where_clause .= ' and ';
                $where_clause .= " ( $filterTime between '$startDate' and '$endDate' ) ";
            }
        }

        if ( $columns && count($columns) > 0 ) {
            foreach ($columns as $key => $column) {
                $column_search_value = $column["search"]["value"];
                if ( $column_search_value != "" ) {
                    if ( !empty($where_clause) ) {
                        $where_clause .= " and ";
                    }
                    $where_clause .= " " . $column["data"] . "='" . $column_search_value . "' ";
                }
            }
        }

        if ( !empty($where_clause) ) $where_clause = " where " . $where_clause;
        return $where_clause;
    }

}

?>
