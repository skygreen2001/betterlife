<?php

/**
 * -----------| 生成报表[模板文件] |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen <skygreen2001@gmail.com>
 */

$reportCname = isset($reportCname) ? $reportCname : "";
$reportEname = isset($reportEname) ? $reportEname : "";
$reportDesc  = isset($reportDesc) ? $reportDesc : "";
$reportSql   = isset($reportSql) ? $reportSql : "";
$tplColumns  = isset($tplColumns) ? $tplColumns : "";
$jsColumns   = isset($jsColumns) ? $jsColumns : "";
$configCols  = isset($configCols) ? $configCols : "";
$reptTimeCol = isset($reptTimeCol) ? $reptTimeCol : "";
$reptFiltCol = isset($reptFiltCol) ? $reptFiltCol : "";
$reptOrderBy = isset($reptOrderBy) ? $reptOrderBy : "";

$sql_config_template = <<<SQLCONFIG

\$configReport["$reportEname"] = array(
    "title" => "$reportCname",
    "intro" => "$reportDesc",
    "columns" => array(
$configCols
    )
);
\$sqlReport["$reportEname"] = "$reportSql";

SQLCONFIG;

$api_template = <<<API
<?php
require_once("../../../init.php");

\$draw         = \$_GET["draw"];
\$currentPage  = \$_GET["page"];
\$pageSize     = \$_GET["pageSize"];
\$startDate    = \$_GET["startDate"];
\$endDate      = \$_GET["endDate"];
\$query        = \$_GET["query"];
\$columns      = \$_GET["columns"];

\$sql_report   = " $reportSql ";
\$where_clause = ServiceReport::getWhereClause( \$sql_report, \$query, \$startDate, \$endDate, \$columns );
\$orderDes     = "$reptOrderBy";

\$countSql   = "select count(*) from (" . \$sql_report . ") report_tmp " . \$where_clause;
\$totalCount = sqlExecute(\$countSql);
\$pageData   = array();
\$pageCount  = 0;
\$reportSql  = "";
if (\$totalCount > 0) {
    // 总页数
    \$pageCount = ceil(\$totalCount / \$pageSize);
    if (\$currentPage <= \$pageCount) {
        \$startPoint = (\$currentPage - 1) * \$pageSize;
        if (\$startPoint > \$totalCount) {
            \$startPoint = 0;
        }
        \$endPoint = \$currentPage * \$pageSize;
        if (\$endPoint > \$totalCount) {
            \$endPoint = \$totalCount;
        }

        \$reportSql   = "select * from (";
        \$reportSql  .= \$sql_report;
        \$reportSql  .= ") report_tmp " . \$where_clause . \$orderDes . " limit " . \$startPoint . "," . \$pageSize;
        \$pageData = sqlExecute(\$reportSql);
    }
}

\$result = array(
    'data' => \$pageData,
    'draw' => \$draw,
    'recordsFiltered' => \$totalCount,
    'recordsTotal'    => \$totalCount
);

if (contains( \$_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.", ".test") ) || Gc::\$dev_debug_on) {
    //调试使用的信息
    \$result["debug"] = array(
        'param' => array(
            'columns'   => \$columns,
            'query'     => \$query,
            'page'      => \$currentPage,
            'pageSize'  => \$pageSize,
            'startDate' => \$startDate,
            'endDate'   => \$endDate
        ),
        'sql'   => \$reportSql,
        'where' => \$where_clause
    );
}
echo json_encode(\$result);

?>
API;

$action_template = <<<ACTION
<?php

/**
 * -----------| 控制器:报表 |-----------
 * @category report
 * @package web.admin.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Report$reportEname extends ActionReport
{

    /**
     * $reportCname
     * 说明   : $reportDesc
     */
    public function lists()
    {

    }

    /**
     * 导出报表: $reportCname
     * 说明   : $reportDesc
     */
    public function export$reportEname()
    {
        \$startDate = \$_GET["startDate"];
        \$endDate   = \$_GET["endDate"];
        \$query     = \$_GET["query"];
        return Manager_ReportService::service$reportEname()->export$reportEname( \$startDate, \$endDate, \$query );
    }
}
?>
ACTION;


$tpl_template = <<<TPL
{extends file="\$template_dir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="\$template_dir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{\$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li class="active" title="$reportCname ( 说明: $reportDesc )">$reportCname ( 说明: $reportDesc )</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <div class="form-group filter-report">
                          <label for="startDateStr" class="col-sm-2 control-label">开始时间</label>
                          <div class="input-group col-sm-3 datetimeStyle" id="startDate">
                              <input id="startDateStr" name="startDate" class="form-control date-picker" type="text" value=""/>
                              <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                          </div>
                          <label for="endDateStr" class="col-sm-2 control-label">结束时间</label>
                          <div class="input-group col-sm-3 datetimeStyle" id="endDate">
                              <input id="endDateStr" name="endDate" class="form-control date-picker" type="text" value=""/>
                              <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                          </div>
                        </div>

                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-report-export">导出</a>
                            <input id="upload_file" name="upload_file" type="file" style="display:none;" accept=".xlsx, .xls" />
                        </div>
                    </div><br/>
                    <div class="row up-container">
                        <div class="filter-up">
                            <div class="filter-up-right col-sm-12">
                                <div>
                                    <i aria-label="search-menu" class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                    <input id="input-search" type="search" placeholder="搜索名称" aria-controls="infoTable" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive col-xs-12">
                        <table id="infoTable" class="display nowrap dataTable table table-striped table-bordered">
                            <thead>
                                <tr>
$tplColumns
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    {include file="\$template_dir/layout/normal/footer.tpl"}
    <script src="{\$template_url}js/normal/list.js"></script>
    <script src="{\$template_url}js/normal/edit.js"></script>
    <script src="{\$template_url}js/core/report$reportEname.js"></script>
{/block}
TPL;


$js_template = <<<JS
\$(function() {
    //Datatables中文网[帮助]: http://datatables.club/
    if (\$.dataTable) {
        var infoTable = \$('#infoTable').DataTable({
            "language"  : \$.dataTable.chinese,
            "processing": true,
            "serverSide": true,
            "retrieve"  : true,
            "ajax": {
                "url" : "api/web/report/report{$reportEname}.php",
                "data": function ( d) {
                    d.query    = \$("#input-search").val();
                    d.pageSize = d.length;
                    d.page     = d.start / d.length + 1;
                    d.limit    = d.start + d.length;
                    d.startDate = '';
                    d.endDate   = '';

                    // [Add parameter to datatable ajax call before draw](https://stackoverflow.com/questions/28906515/add-parameter-to-datatable-ajax-call-before-draw)
                    // Retrieve dynamic parameters
                    var dt_params = $('#infoTable').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) { $.extend(d, dt_params); }
                    return d;
                },
                //可以对返回的结果进行改写
                "dataFilter": function(data) {
                    return data;
                }
            },
            "responsive"   : true,
            "searching"    : false,
            "ordering"     : false,
            "dom"          : '<"top">rt<"bottom"ilp><"clear">',
            "deferRender"  : true,
            "bStateSave"   : true,
            "bLengthChange": true,
            "aLengthMenu"  : [[10, 25, 50, 100,-1],[10, 25, 50, 100,'全部']],
            "columns": [
$jsColumns
            ],
            "initComplete":function() {
                \$.dataTable.filterDisplay();
            },
            "drawCallback": function( settings) {
                \$.dataTable.pageNumDisplay(this);
                \$.dataTable.filterDisplay();
            }
        });
        \$.dataTable.doFilter(infoTable);

        \$("#btn-report-export").click(function() {
            var params = infoTable.ajax.params();
            params = "&query=" + params.query + "&startDate=" + params.startDate + "&endDate=" + params.endDate;
            \$.getJSON("index.php?go=report.report{$reportEname}.export{$reportEname}" + params, function(response) {
                window.open(response.data);
            });
        });

        $.edit.datetimePicker('#startDate');
        $.edit.datetimePicker('#endDate');

        $(".datetimeStyle").on("dp.change", function(e) {
            $(this).children("input").val(e.date.format("YYYY-MM-DD") + " 00:00");
            var startDate = $("#startDateStr").val();
            var endDate   = $("#endDateStr").val();
            if (startDate && endDate) {
                $('#infoTable').data('dt_params', { startDate: startDate, endDate: endDate });
                // console.log(infoTable.ajax.params());
                infoTable.draw();
            }
        });

    }

});
JS;


$service_template = <<<SERVICE
<?php

/**
 * -----------| 服务类:报表服务 |-----------
 * @category admin
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class Service$reportEname extends ServiceReport
{
    /**
     * 导出报表: $reportCname
     * 说明   : $reportDesc
     */
    public function export$reportEname(\$startDate, \$endDate, \$query) {
        \$sql_report = " $reportSql ";
        \$out_header = \$this->getSqlSelCols(\$sql_report);
        \$whereState = ServiceReport::getWhereClause( \$sql_report, \$query, \$startDate, \$endDate );
        \$orderDes   = ServiceReport::getOrderBy(\$sql_report);

        \$reportSql  = "select * from (";
        \$reportSql .= \$sql_report;
        \$reportSql .= ") report_tmp " . \$whereState . \$orderDes;

        // 对sql进行格式化
        \$reportSql  = trim(\$reportSql);//去除首尾空格
        // \$reportSql  = strtolower(\$reportSql);//转换成小写
        \$data       = sqlExecute(\$reportSql);// 获取数据
        \$diffpart   = date("YmdHis");
        \$fileName   = "$reportCname".\$diffpart;
        \$outFname   = Gc::\$attachment_path . "export" . DS . "report" . DS . "\$fileName.xls";
        UtilExcel::arraytoExcel( \$out_header, \$data, \$outFname );
        \$downPath   = Gc::\$attachment_url . "export/report/\$fileName.xls";
        return array(
            'success' => true,
            'data'    => \$downPath
        );
    }
}
?>
SERVICE;
