<?php
/**
 * 显示超过限制数量的表
 * 如超过10000条的表: http://127.0.0.1/tools/tools/db/db_countrows.php?l=10000
 */
require_once ("../../../init.php");

// l: 限制数量
$limit = $_GET["l"]??0;

$tableInfos = Manager_Db::newInstance()->dbinfo()->tableInfoList();
// print_pre($tableInfos, true);
foreach ($tableInfos as $table) {
    $table_name  = $table["Name"];
    $table_intro = "";
    // $table_intro = $table["Comment"];
    // if ( !empty($table_intro) ) $table_intro = "[ $table_intro ]";
    $table_rows  = $table["Rows"];
    if ( $table_rows > $limit ) {
        echo "表名: $table_name $table_intro - 共计行数: $table_rows;<br/>";
    }
}
