<?php
require_once ("../../init.php");

$title    = "一键生成指定SQL查询的报表";
$url_base = UtilNet::urlbase();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title?>工具箱</title>
    <meta name="description" content="Web开发便捷使用的工具" />
    <link rel="icon" href="<?php echo $url_base ?>favicon.ico" mce_href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../misc/css/common.min.css">
    <link rel="stylesheet" href="../../misc/css/normalize.css">
    <style type="text/css">
      li {
        list-style: none;
        text-align: left;
        line-height: 25px;
      }
    </style>
</head>
<body>
  <div id="report-form" class="container" align="center">
    <card id="dbCommonCard" name='dbCommonCard' style="width:600px;margin: 150px auto 10px auto;">
      <p slot="title" style="text-align:left;">
          <i class="ivu-icon ivu-icon-ios-construct"></i> 数据库常用脚本生成工具
      </p>
      <ul>
        <li><a href="db/rename_db_prefix.php" target="_blank">修改数据库表前缀</a></li>
        <li><a href="db/db_replace_keywords.php" target="_blank">替换所有表里的关键词</a></li>
        <li><a href="db/db_delete_data.php" target="_blank">删除所有的表数据</a></li>
        <li><a href="db/db_delete_tables.php" target="_blank">删除所有的表</a></li>
        <li><a href="db/sqlserver/db_sqlserver_convert_prepare.php" target="_blank">移植数据库表从Mysql到Sqlserver</a></li>
      </ul>
    </card>
    <card id="dbCodeCard" name='dbCodeCard' style="width:600px;margin: 10px auto 50px auto;">
      <p slot="title" style="text-align:left;">
          <i class="ivu-icon ivu-icon-ios-construct"></i> 数据库代码生成工具
      </p>
      <ul>
        <li><a href="autocode/db_onekey.php" target="_blank">一键生成用于Web应用开发的初始模型</a></li>
        <li><a href="autocode/layer/domain/db_domain.php" target="_blank">数据库生成实体类</a></li>
        <li><a href="autocode/layer/domain/db_domain_java.php" target="_blank">数据库生成Java实体类</a></li>
        <li><a href="autocode/layer/db_service.php" target="_blank">数据库生成服务类</a></li>
        <li><a href="autocode/layer/db_action.php" target="_blank">数据库生成控制器类</a></li>
        <li><a href="autocode/layer/view/db_view_default.php" target="_blank">数据库生成默认通用的表示层</a></li>
      </ul>
    </card>
    <i-button type="primary" @click="goback">返回首页</i-button><br><br>
  </div>
  <script src="../../misc/js/common/bower.min.js"></script>
  <script type="text/javascript">
      Vue.config.debug = true;
      Vue.config.devtools = true;

      var reportForm = new Vue({
        el: '#report-form',

        methods: {
          goback: function() {
             location.href = "<?php echo $url_base ?>";
          }
        }
      });
  </script>
</body>
</html>
