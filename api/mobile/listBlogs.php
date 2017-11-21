<?php
// 线路详情列表
require_once ("../../init.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: x-requested-with,content-type");

$params = json_decode(file_get_contents('php://input'), true);
// 当前页数
$page  = $params['page'];
if ( empty($page) ) $page = 1;

$data                = array();
$data["code"]        = 1;
$data["description"] = "";

$where_clause = " ";
$orderDes     = " blog_id desc ";
$total        = 1;
$page_size    = Config_Mobile::$api_page_size;

$pageBlogs = Blog::queryPageByPageNo( $page, $where_clause, $page_size, $orderDes );
if ($pageTrips) {
  $total = $pageBlogs["pageCount"];//总页数
  $count = Blog::count();//$pageTrips["count"];//总记录数
  if ($page == $total || $total==0) $data["code"] = 999;
  $blogs = $pageBlogs["data"];
  foreach($blogs as $key => $blog){
    unset($blog->commitTime, $blog->updateTime);
  }

  $data["result"] = array(
    "pageCount"   => $total,
    "recordCount" => $count,
    "data"        => $blogs
  );
} else {
  $data["code"] = 999;
}

$data["debug"] = array(
  'param' => array(
    'page' => $page
  ),
  'where' => $where_clause
);
echo json_encode($data);

?>
