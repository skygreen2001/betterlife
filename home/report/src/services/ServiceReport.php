<?php
/**
+---------------------------------------<br/>
 * 服务类:所有报表服务的父类<br/>
+---------------------------------------
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
        $reportSql = substr($reportSql, 0, $fromPos);//截取from前的字符串
        $reportSql = substr($reportSql, 7);//截取select后from前的字符串

        // [PHP正则表达式来拆分SQL字段列表](http://cn.voidcc.com/question/p-fmqfqqts-ye.html)
        // [PHP SQL Parser](https://code.google.com/archive/p/php-sql-parser/)

        // 测试用例: $reportSql = 'field1,field2,field3,CONCAT(field1,field2) AS concatted,IF(field1 = field2,field3,field4) AS logic';
        $selCols   = preg_split ("/,(?![^()]*+\\))/", $reportSql); //以,分隔字符串
        $arr_output_header = array();
        foreach ( $selCols as $selCol) {
            $selCol    = trim($selCol);
            $newSelCol = preg_split('/( |as)/', $selCol, 0, PREG_SPLIT_NO_EMPTY);//正则匹配分隔字符串
            //以下用来判断select column列名是否被重命名 ( 根据newSelCol的size来判断 )
            if ( count($newSelCol) > 1) {
                $newSelCol[1] = preg_replace("/('|`|\")/", "", $newSelCol[1]);
                $arr_output_header[$newSelCol[1]] = $newSelCol[1];
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
      return " order by " . $result . " desc ";
   }
}

?>
