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
     * 导出报表: 月充值数
     * 说明   : 本月充值数，增长率(需要程序)
     */
    public function export()
    {
        $rtype = $this->data["rtype"];
        return Manager_ReportService::serviceReportone()->export($rtype);
    }
}
