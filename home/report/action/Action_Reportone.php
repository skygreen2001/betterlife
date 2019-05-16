<?php
/**
 +---------------------------------------<br/>
 * 控制器:所有报表可以通过一个控制器处理<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.admin.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Reportone extends ActionReport
{
    /**
     * 统一的报表列表页面
     */
    public function index()
    {

    }

    /**
     * 导出报表: 统一的报表
     * 说明   : 统一的报表
     */
    public function export()
    {
        $rtype     = $this->data["rtype"];
        $startDate = $_GET["startDate"];
        $endDate   = $_GET["endDate"];
        $query     = $_GET["query"];
        return Manager_ReportService::serviceReportone()->export($rtype, $startDate, $endDate, $query);
    }
}
