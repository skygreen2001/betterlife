<?php
/**
 * 生成报表[模板文件]
 * Created by IntelliJ IDEA.
 * User: harlan
 * Date: 2019/2/28
 * Time: 16:38
 */
$reportCname = isset($reportCname) ? $reportCname : "";
$reportEname = isset($reportEname) ? $reportEname : "";
$reportDesc  = isset($reportDesc) ? $reportDesc : "";
$reportSql   = isset($reportSql) ? $reportSql : "";
$tplColumns  = isset($tplColumns) ? $tplColumns : "";
$jsColumns   = isset($jsColumns) ? $jsColumns : "";

$api_template = <<<API
<?php
require_once ("../../../init.php");

\$draw        = \$_GET["draw"];
\$currentPage = \$_GET["page"];
\$pageSize    = \$_GET["pageSize"];
\$reportSql  = "$reportSql";
\$data       = sqlExecute(\$reportSql);
\$totalCount = count(\$data);
\$pageData   = array();
\$pageCount  = 0;

if ( \$totalCount > 0 ) {
    // 总页数
    \$pageCount = ceil(\$totalCount / \$pageSize);
    if ( \$currentPage <= \$pageCount ) {
        \$startPoint = (\$currentPage - 1) * \$pageSize;
        if ( \$startPoint > \$totalCount ) {
            \$startPoint = 0;
        }
        \$endPoint = \$currentPage * \$pageSize;
        if ( \$endPoint > \$totalCount ) {
            \$endPoint = \$totalCount;
        }
        \$pageData = array_slice(\$data, \$startPoint, \$pageSize, false);
    }
}

\$result = array(
  'data' => \$pageData,
  'draw' => \$draw,
  'recordsFiltered' => \$totalCount,
  'recordsTotal' => \$totalCount
);
echo json_encode(\$result);

?>
API;


$action_template = <<<ACTION
<?php
/**
 +---------------------------------------<br/>
 * 控制器:报表<br/>
 +---------------------------------------
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
        return Manager_ReportService::service$reportEname()->export$reportEname();
    }
}
?>
ACTION;


$tpl_template = <<<TPL
{extends file="\$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="\$templateDir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{\$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li class="active">$reportCname ( 说明: $reportDesc )</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-report-export">导出</a>
                            <input id="upload_file" name="upload_file" type="file" style="display:none;" accept=".xlsx, .xls" />
                        </div>
                    </div><br/>
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

    {include file="\$templateDir/layout/normal/footer.tpl"}
    <script src="{\$template_url}js/normal/list.js"></script>
    <script src="{\$template_url}js/core/report$reportEname.js"></script>
{/block}
TPL;


$js_template = <<<JS
\$(function(){
    //Datatables中文网[帮助]: http://datatables.club/
    if (\$.dataTable) {
        var infoTable = \$('#infoTable').DataTable({
            "language"  : \$.dataTable.chinese,
            "processing": true,
            "serverSide": true,
            "retrieve"  : true,
            "ajax": {
                "url" : "api/web/report/report{$reportEname}.php",
                "data": function ( d ) {
                    d.query    = \$("#input-search").val();
                    d.pageSize = d.length;
                    d.page     = d.start / d.length + 1;
                    d.limit    = d.start + d.length;
                    return d;
                },
                //可以对返回的结果进行改写
                "dataFilter": function(data){
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
            "initComplete":function(){
                \$.dataTable.filterDisplay();
            },
            "drawCallback": function( settings ) {
                \$.dataTable.pageNumDisplay(this);
                \$.dataTable.filterDisplay();
            }
        });
        \$.dataTable.doFilter(infoTable);

        \$("#btn-report-export").click(function(){
            \$.getJSON("index.php?go=report.report{$reportEname}.export{$reportEname}", function(response){
                window.open(response.data);
            });
        });
    }

});
JS;


$service_template = <<<SERVICE
<?php
/**
 +---------------------------------------<br/>
 * 服务类:报表服务<br/>
 +---------------------------------------
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
    public function export$reportEname() {
        \$reportSql = "$reportSql";
        // 对sql进行格式化
        \$reportSql = trim(\$reportSql);//去除首尾空格
        \$reportSql = strtolower(\$reportSql);//转换成小写
        \$data = sqlExecute(\$reportSql);// 获取数据
        \$arr_output_header = \$this->getSqlSelCols(\$reportSql);
        \$diffpart = date("YmdHis");
        \$fileName = "$reportCname".\$diffpart;
        \$outputFileName = Gc::\$attachment_path . "export" . DS . "report" . DS . "\$fileName.xls";
        UtilExcel::arraytoExcel(\$arr_output_header, \$data, \$outputFileName, false);
        \$downloadPath = Gc::\$attachment_url . "export/report/\$fileName.xls";
        return array(
            'success' => true,
            'data'  => \$downloadPath
        );
    }
}
?>
SERVICE;

?>
