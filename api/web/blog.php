<?php
// error_reporting(0);
require_once ("../../init.php");

$draw         = $_GET["draw"];
$page         = $_GET["page"];
$page_size    = $_GET["pageSize"];
$search       = $_GET["query"];
$columns      = $_GET["columns"];
$where_clause = "";
$orderDes     = "id desc";

if (!empty($query)){
  $where_clause  = "(";
  $search_atom = explode(" ", trim($query));
  array_walk($search_atom,function(&$value, $key){
    $value = " ( blog_name LIKE '%" . $value . "%' ) ";
  });
  $where_clause .= implode(" and ", $search_atom);
  $where_clause .= ")";
}

blog_content
foreach ($columns as $key => $column) {
  $column_search_value = $column["search"]["value"];
  if ($column_search_value!=""){
    if(!empty($where_clause)){
      $where_clause .= " and ";
    }
    $where_clause .= " ".$column["data"]."='".$column_search_value."' ";
  }
}

$pageBlogs = Blog::queryPageByPageNo($page,$where_clause,$page_size,$orderDes);
$data      = $pageBlogs["data"];
$recordsFiltered = $pageBlogs["count"];
$recordsTotal    = $recordsFiltered;
$result = array(
  'data' => $data,
  'draw' => $draw,
  'recordsFiltered' => $recordsFiltered,
  'recordsTotal' => $recordsTotal
);

//调试使用的信息
$result["debug"] = array(
  'param' => array(
    'columns' => $columns
  ),
  'where' => $where_clause
);
echo json_encode($result);
?>