<?php
require_once ("../../../init.php");
require_once ("../../../misc/sql/report.php");
$draw         = $_GET["draw"];
$currentPage  = $_GET["page"];
$pageSize     = $_GET["pageSize"];
$query        = $_GET["query"];
$columns      = $_GET["columns"];
$where_clause = "";

$rtype        = $_GET["rtype"];
$sql_report   = $sqlReport[$rtype];
$sql_report   = str_replace(";", "", $sql_report);
$orderDes     = ServiceReport::getOrderBy($sql_report);

if ( !empty($query) ) {
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

foreach ($columns as $key => $column) {
  $column_search_value = $column["search"]["value"];
  if ( $column_search_value != "" ) {
    if ( !empty($where_clause) ) {
      $where_clause .= " and ";
    }
    $where_clause .= " " . $column["data"] . "='" . $column_search_value . "' ";
  }
}

if ( !empty($where_clause) ) $where_clause = " where " . $where_clause;

$reportSql   = "select * from (";
$reportSql  .= $sql_report;
$reportSql  .= ") report_tmp " . $where_clause . $orderDes;
$data        = sqlExecute($reportSql);
$totalCount  = @count($data);
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

if( contains( $_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.") ) || Gc::$dev_debug_on ) {
  //调试使用的信息
  $result["debug"] = array(
    'param' => array(
      'columns' => $columns
    ),
    'sql'   => $reportSql,
    'where' => $where_clause
  );
}
echo json_encode($result);
