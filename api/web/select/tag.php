<?php
// error_reporting(0);
require_once ("../../../init.php");

$query        = $_GET["term"];
$where_clause = "";
if (!empty($query)){
  $where_clause  = "(";
  $search_atom = explode(" ", trim($query));
  array_walk($search_atom,function(&$value, $key){
    $value = " ( title LIKE '%" . $value . "%' ) ";
  });
  $where_clause .= implode(" and ", $search_atom);
  $where_clause .= ")";
}
$pageTags = Tags::get($where_clause);
$data     = array();
if ($pageTags){
  foreach ($pageTags as $key => $tag) {
    $tagv         = array();
    $tagv["id"]   = $tag->tags_id;
    $tagv["text"] = $tag->title;
    $data[]       = $tagv;
  }
}
$result   = array(
  'code' => 1,
  'description' => "",
  'data' => $data
);

//调试使用的信息
$result["debug"] = array(
  'param' => array(
    'term' => $search
  ),
  'where' => $where_clause
);
echo json_encode($result);
