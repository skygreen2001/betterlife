<?php
/**
 +---------------------------------------<br/>
 * 服务类:统一的报表服务<br/>
 +---------------------------------------
 * @category admin
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class ServiceReportone extends ServiceReport
{
    /**
     * 导出报表
     * @param string $rtype 报表类型
     */
    public function export($rtype, $startDate, $endDate, $query) {
        require_once(Gc::$nav_root_path . "misc" . DS . "sql" . DS . "report.php");
        $sql_report = $sqlReport[$rtype];
        $sql_report = str_replace(";", "", $sql_report);
        $out_header = $this->getSqlSelCols($sql_report);
        $whereState = ServiceReport::getWhereClause($sql_report, $query, $startDate, $endDate);
        $orderDes   = ServiceReport::getOrderBy($sql_report);

        $reportSql  = "select * from (";
        $reportSql .= $sql_report;
        $reportSql .= ") report_tmp " . $whereState . $orderDes;
        // 对sql进行格式化,保证和$out_header里抬头一致
        $reportSql  = trim($reportSql);//去除首尾空格
        // $reportSql  = strtolower($reportSql);//转换成小写
        $data       = sqlExecute($reportSql);// 获取数据
        $diffpart   = date("YmdHis");
        $fileName   = $rtype . $diffpart;
        $outputFileName = Gc::$attachment_path . "export" . DS . "report" . DS . "$fileName.xls";
        UtilExcel::arraytoExcel( $out_header, $data, $outputFileName );
        $downloadPath   = Gc::$attachment_url . "export/report/$fileName.xls";
        return array(
            'success' => true,
            'data'    => $downloadPath
        );
    }
}
?>
