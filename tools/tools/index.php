<?php
require_once("../../init.php");

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
      .vertical-center-modal{
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .vertical-center-modal .ivu-modal{
          top: 0;
      }
      input{
        height: 30px;
        padding-left: 5px;
      }
      li {
        list-style: none;
        text-align: left;
        line-height: 25px;
      }
      #tools-box {
        display:none;
      }
      #toolkitCard {
        width: 600px;
        margin: 80px auto 10px auto;
      }
      #dbCommonCard, #dbCodeCard, #otherCard {
        width: 600px;
        margin: 10px auto 10px auto;
      }
      #dbCodeCard {
        margin-bottom: 30px;
      }
    </style>
</head>
<body>
  <div id="tools-box" class="container" align="center">
    <card id="toolkitCard" name='toolkitCard'>
      <p slot="title" style="text-align:left;">
          <i class="ivu-icon ivu-icon-ios-construct"></i> 原创高级工具集
      </p>
      <ul>
        <li><a href="<?php echo $url_base ?>app/redis/index.html" target="_blank">Redis Online Manager</a></li>
      </ul>
    </card>
    <card id="dbCommonCard" name='dbCommonCard'>
      <p slot="title" style="text-align:left;">
          <i class="ivu-icon ivu-icon-ios-construct"></i> 数据库常用脚本生成工具
      </p>
      <ul>
        <li><a @click="at=1;inputModel=true">修改数据库表前缀</a></li>
        <li><a @click="at=2;inputModel=true">替换所有表里的关键词</a></li>
        <li><a @click="at=3;ok()">删除所有的表数据</a></li>
        <li><a @click="at=4;ok()">删除所有的表</a></li>
        <li><a @click="at=5;ok()">移植数据库表从Mysql到Sqlserver</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a @click="at=6;ok()">[包括注释]</a></li>
      </ul>
    </card>
    <card id="dbCodeCard" name='dbCodeCard'>
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

    <card id="otherCard" name='otherCard'>
      <p slot="title" style="text-align:left;">
          <i class="ivu-icon ivu-icon-ios-construct"></i> 其它工具
      </p>
      <ul>
        <li><a href="problem/CheckBom.php" target="_blank">检查清除文件头的Bom</a></li>
        <!-- <li><a href="web/clipboard.php" target="_blank">剪贴板</a></li> -->
      </ul>
    </card>

    <i-button type="primary" @click="goback">返回首页</i-button><br><br>
    <Modal title="修改数据库表前缀" v-model="inputModel" @on-ok="ok" class-name="vertical-center-modal">
      <div style='line-height:1.5em;'>
          <i-input v-model="originTxt">
            <span slot="prepend">原关键字</span>
          </i-input><br/>
          <i-input v-model="newTxt">
            <span slot="prepend">新关键字</span>
          </i-input>
      </div>
    </Modal>
    <Modal title="结果" v-model="resultModel" cancel-text="" fullscreen>
        <div v-html="result"></div>
    </Modal>
  </div>
  <script src="../../misc/js/common/bower.min.js"></script>
  <script type="text/javascript">
      Vue.config.debug = true;
      Vue.config.devtools = true;

      var reportForm = new Vue({
        el: '#tools-box',
        data () {
            return {
                /**
                 * 1: 修改数据库表前缀
                 * 2: 替换所有表里的关键词
                 * 3: 删除所有的表数据
                 * 4: 删除所有的表
                 * 5: 移植数据库表从Mysql到Sqlserver
                 * 6: 移植数据库表从Mysql到Sqlserver(包括注释)
                 */
                at: 0,
                inputModel: false,
                originTxt: '',
                newTxt: '',
                resultModel: false,
                result: ''
            }
        },
        created: function () {
            this.$Spin.show();
            setTimeout(() => {
                this.$Spin.hide();
                $(function() {
                  $("#tools-box").css("display", "block");
                });
            }, 1000);
        },
        methods: {
          ok: function() {
            let ctrl = this;
            let url = '';
            let params = {};
            switch (this.at) {
              case 1:
                url = "db/rename_db_prefix.php";
                params = {
                  old_prefix: this.originTxt,
                  new_prefix: this.newTxt
                }
                break;
              case 2:
                url = "db/db_replace_keywords.php";
                params = {
                  oldwords: this.originTxt,
                  newwords: this.newTxt
                }
                break;
              case 3:
                url = "db/db_delete_data.php";
                break;
              case 4:
                url = "db/db_delete_tables.php";
                break;
              case 5:
                url = "db/sqlserver/db_sqlserver_convert_prepare.php";
                break;
              case 6:
                url = "db/sqlserver/db_sqlserver_convert_prepare.php";
                params = {
                  isComment: 1
                }
                break;

              default:
            }
            if ( url ) {
              axios.get(url,{
                      params: params
                   })
                   .then(function(response) {
                      // document.write(res.body);
                      ctrl.resultModel = true;
                      ctrl.result = response.data;
                   })
                   .catch(function (error) { // 请求失败处理
                       console.log(error);
                   });
            }
          },
          goback: function() {
             location.href = "<?php echo $url_base ?>";
          }
        }
      });
  </script>
</body>
</html>
