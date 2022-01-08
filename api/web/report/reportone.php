<?php
require_once("../../../init.php");
require_once("../../../misc/sql/report.php");
$draw         = $_GET["draw"];
$currentPage  = $_GET["page"];
$pageSize     = $_GET["pageSize"];
$startDate    = $_GET["startDate"];
$endDate      = $_GET["endDate"];
$query        = @$_GET["query"];
$columns      = $_GET["columns"];

$rtype        = $_GET["rtype"];
$sql_report   = $sqlReport[$rtype];
$sql_report   = str_replace(";", "", $sql_report);
$where_clause = ServiceReport::getWhereClause( $sql_report, $query, $startDate, $endDate, $columns );
$orderDes     = ServiceReport::getOrderBy( $sql_report );

$countSql   = "select count(*) from (" . $sql_report . ") report_tmp " . $where_clause;
$totalCount = sqlExecute($countSql);
$pageData   = array();
$pageCount  = 0;
if ($totalCount > 0) {
    // 总页数
    $pageCount = ceil($totalCount / $pageSize);
    if ($currentPage <= $pageCount) {
        $startPoint = ($currentPage - 1) * $pageSize;
        if ($startPoint > $totalCount) {
            $startPoint = 0;
        }
        $endPoint = $currentPage * $pageSize;
        if ($endPoint > $totalCount) {
            $endPoint = $totalCount;
        }

        $reportSql   = "select * from (";
        $reportSql  .= $sql_report;
        $reportSql  .= ") report_tmp " . $where_clause . $orderDes . " limit " . $startPoint . "," . $pageSize;
        $pageData = sqlExecute($reportSql);
    }
}

$result = array(
    'data' => $pageData,
    'draw' => $draw,
    'recordsFiltered' => $totalCount,
    'recordsTotal'    => $totalCount
);

if (contains( $_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.", ".test") ) || Gc::$dev_debug_on) {
    //调试使用的信息
    $result["debug"] = array(
        'param' => array(
            'columns'   => $columns,
            'query'     => $query,
            'page'      => $currentPage,
            'pageSize'  => $pageSize,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ),
        'sql'   => $reportSql,
        'where' => $where_clause
    );
}
echo json_encode($result);
