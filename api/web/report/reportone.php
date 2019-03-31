<?php
require_once ("../../../init.php");
require_once ("../../../misc/sql/report.php");
$draw        = $_GET["draw"];
$currentPage = $_GET["page"];
$pageSize    = $_GET["pageSize"];
$rtype       = $_GET["rtype"];
$reportSql   = $sqlReport[$rtype];
$data        = sqlExecute($reportSql);
$totalCount  = count($data);
$pageData    = array();
$pageCount   = 0;

if ( $totalCount > 0 ) {
    // 总页数
    $pageCount = ceil($totalCount / $pageSize);
    if ( $currentPage <= $pageCount ) {
        $startPoint = ($currentPage - 1) * $pageSize;
        if ( $startPoint > $totalCount ) {
            $startPoint = 0;
        }
        $endPoint = $currentPage * $pageSize;
        if ( $endPoint > $totalCount ) {
            $endPoint = $totalCount;
        }
        $pageData = array_slice($data, $startPoint, $pageSize, false);
    }
}

$result = array(
  'data' => $pageData,
  'draw' => $draw,
  'recordsFiltered' => $totalCount,
  'recordsTotal' => $totalCount
);
echo json_encode($result);
