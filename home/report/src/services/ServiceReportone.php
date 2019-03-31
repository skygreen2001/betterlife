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
    public function export($rtype) {
        require_once (Gc::$nav_root_path . "misc" . DS . "sql" . DS . "report.php");
        $reportSql = $sqlReport[$rtype];
        // 对sql进行格式化
        $reportSql = trim($reportSql);//去除首尾空格
        $reportSql = strtolower($reportSql);//转换成小写
        $data      = sqlExecute($reportSql);// 获取数据
        $arr_output_header = $this->getSqlSelCols($reportSql);
        $diffpart = date("YmdHis");
        $fileName = $rtype . $diffpart;
        $outputFileName = Gc::$attachment_path . "export" . DS . "report" . DS . "$fileName.xls";
        UtilExcel::arraytoExcel($arr_output_header, $data, $outputFileName, false);
        $downloadPath = Gc::$attachment_url . "export/report/$fileName.xls";
        return array(
            'success' => true,
            'data'  => $downloadPath
        );
    }
}
?>
