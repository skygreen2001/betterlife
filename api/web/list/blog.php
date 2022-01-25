<?php

// error_reporting(0);
require_once("../../../init.php");

$draw         = $_GET["draw"];
$page         = $_GET["page"];
$page_size    = $_GET["pageSize"];
$query        = $_GET["query"];
$columns      = $_GET["columns"];
$where_clause = "";
$orderDes     = "blog_id desc";

if (!empty($query)) {
    $where_clause = "(";
    $search_atom  = explode(" ", trim($query));
    array_walk($search_atom, function (&$value, $key) {
        $value = " ( blog_name LIKE '%" . $value . "%' ) ";
    });
    $where_clause .= implode(" and ", $search_atom);
    $where_clause .= ")";
}

foreach ($columns as $key => $column) {
    $column_search_value = $column["search"]["value"];
    if ($column_search_value != "") {
        if (!empty($where_clause)) {
            $where_clause .= " and ";
        }
        $where_clause .= " " . $column["data"] . "='" . $column_search_value . "' ";
    }
}

$pageBlogs = Blog::queryPageByPageNo($page, $where_clause, $page_size, $orderDes);
$data = $pageBlogs["data"];
if ($data) {
    foreach ($data as $key => $blog) {
        if (!empty($blog->user_id)) {
            $user_i = User::getById($blog->user_id);
            if ($user_i) {
                $blog->username = $user_i->username;
            }
        }
        if (!empty($blog->category_id)) {
            $category_i = Category::getById($blog->category_id);
            if ($category_i) {
                $blog->category_name = $category_i->name;
            }
        }

        if (!empty($blog->icon_url)) {
            $blog->icon_url = Gc::$upload_url . "images/" . $blog->icon_url;
        }

    }
}
$recordsFiltered = $pageBlogs["count"];
$recordsTotal    = Blog::count();
$result = array(
    'data' => $data,
    'draw' => $draw,
    'recordsFiltered' => $recordsFiltered,
    'recordsTotal'    => $recordsTotal
);

//调试使用的信息
$result["debug"] = array(
    'param' => array(
        'columns' => $columns
    ),
    'where' => $where_clause
);
echo json_encode($result);
