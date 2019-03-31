<?php

$sqlReport    = array();
$configReport = array();

$configReport["userReport"] = array(
    "title" => "日用户增长数",
    "intro" => "日用户增长数，导出计算增长率",
    "columns" => array(
        "日期",
        "用户数"
    )
);
$sqlReport["userReport"] = "select DATE_FORMAT(updateTime,'%Y-%m-%d') 日期,count(user_id) 用户数 from bb_user_user group by 日期;";

$configReport["blogReport"] = array(
    "title" => "日增博客数",
    "intro" => "每日新增博客总数，导出计算当日博客增长率",
    "columns" => array(
        "日期",
        "博客数"
    )
);
$sqlReport["blogReport"] = "select DATE_FORMAT(updateTime,'%Y-%m-%d') 日期,count(blog_id) 博客数 from bb_core_blog group by 日期;";
