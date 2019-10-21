<?php
require_once ("../../../init.php");

$title    = "一键生成指定SQL查询的报表";
$url_base = UtilNet::urlbase();
$reportType  = !empty($_REQUEST['report_type']) ? $_REQUEST['report_type'] : "1";
$reportCname = !empty($_REQUEST['report_cname']) ? $_REQUEST['report_cname'] : "";
$reportEname = !empty($_REQUEST['report_ename']) ? $_REQUEST['report_ename'] : "";
$reportDesc  = !empty($_REQUEST['report_desc']) ? $_REQUEST['report_desc'] : "";
$reportSql   = !empty($_REQUEST['report_sql']) ? $_REQUEST['report_sql'] : "";
$reportSql   = preg_replace('/\"/', "'", $reportSql);

AutoCodeCreateReport::$save_dir = Gc::$nav_root_path . "model" . DS;

$reportDev = !empty($_REQUEST['report_dev']) ? $_REQUEST['report_dev'] : "";
$template_build_dev = "";
if ($reportDev && !empty($reportCname) && !empty($reportSql)){
    $config = array(
        "isProd" => false,
        "reportType" => $reportType,
        "reportCname" => $reportCname,
        "reportEname" => $reportEname,
        "reportDesc" => $reportDesc,
        "reportSql" => $reportSql,

    );
    AutoCodeCreateReport::AutoCode( $config );
    $template_build_dev = <<<TPL_BUILDDEV
        <card id="reportProdCard" name='reportProdCard' style="width:600px;margin: -30px auto 50px auto;">
            <h4 style="text-align: center">请去model目录下查看新创建的报表文件，确认无误后点击覆盖生成，生成正式文件</h4><br />
            <i-form ref="reportProdForm" :model="reportProdForm">
                <input type="hidden" v-model="reportProdForm.report_cname" name="report_cname" value="$reportCname" />
                <input type="hidden" v-model="reportProdForm.report_ename" name="report_ename" value="$reportEname" />
                <input type="hidden" v-model="reportProdForm.report_desc" name="report_desc" value="$reportDesc" />
                <input type="hidden" v-model="reportProdForm.report_sql" name="report_sql" value="$reportSql" />
                <i-button type='primary' @click="createReportProd">覆盖生成</i-button>
            </i-form>
        </card>
TPL_BUILDDEV;
};

$reportProd = !empty($_REQUEST['report_prod']) ? $_REQUEST['report_prod'] : "";
$template_build_prod = "";
if ($reportProd && !empty($reportCname) && !empty($reportSql)){
    $config = array(
        "isProd" => true,
        "reportType" => $reportType,
        "reportCname" => $reportCname,
        "reportEname" => $reportEname,
        "reportDesc" => $reportDesc,
        "reportSql" => $reportSql,
    );
    AutoCodeCreateReport::AutoCode( $config );
    $template_build_prod = <<<TPL_BUILDPROD
        <card id="reportInfoCard" name='reportInfoCard' style="width:600px;margin: -30px auto 50px auto;">
            <h4 style='text-align: center'>报表文件已生成至正式目录，请自行查看</h4>
        </card>
TPL_BUILDPROD;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo $title?></title>
        <meta name="description" content="报表代码生成器">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $url_base ?>favicon.ico" mce_href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="../../../misc/css/common.min.css">
        <link rel="stylesheet" href="../../../misc/css/normalize.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div id="report-form" class="container" align="center">
          <card id="reportCard" name='reportCard' style="width:600px;margin: 150px auto 50px auto;">
            <p slot="title" style="text-align:left;">
                <i class="ivu-icon ivu-icon-ios-construct"></i> <?php echo $title ?>
            </p>
            <a href="#" slot="extra" @click.prevent="refresh">
                <i class="ivu-icon ivu-icon-ios-refresh-circle"></i> 刷新
            </a>
            <i-form ref="reportForm" :model="reportForm" :rules="ruleValidate" label-position="right" :label-width="100">
                <div class="input-contianer">
                    <form-item label="报表生成方式" prop="report_type">
                        <i-select v-model="reportForm.report_type" name="report_type">
                            <i-option v-for="item in report_types" :value="item.value" :key="item.value">{{ item.label }}</Option>
                        </i-select>
                    </form-item>
                </div>
                <div class="input-contianer">
                    <form-item label="报表中文昵称" prop="report_cname">
                      <i-input v-model="reportForm.report_cname" name="report_cname" placeholder="" clearable  />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <form-item label="报表英文昵称" prop="report_ename">
                      <i-input v-model="reportForm.report_ename" name="report_ename" placeholder="" clearable />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <form-item label="报表详细描述" prop="report_desc">
                      <i-input type="textarea" v-model="reportForm.report_desc" name="report_desc" :rows="6" :cols="58" :autosize="{minRows: 2,maxRows: 5}"  />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <form-item label="报表所需SQL" prop="report_sql">
                      <i-input type="textarea" v-model="reportForm.report_sql" name="report_sql" :rows="10" :cols="58" :autosize="{minRows: 2,maxRows: 20}"  />
                    </form-item>
                </div>
                <i-button type="primary" @click="createReport">生成</i-button><br><br>
            </i-form>
            <Divider dashed> 说明 </Divider>
            <p>报表默认按统一的规则生成，自定义则可以在单独的各个文件中生成，灵活度高</p>
          </card>

          <?php
          if ( $template_build_dev ) echo $template_build_dev;
          if ( $template_build_prod ) echo $template_build_prod;
          ?>
        </div>
        <script src="../../../misc/js/common/bower.min.js"></script>
        <script type="text/javascript">
          Vue.config.debug = true;
          Vue.config.devtools = true;

          // javascript的几种使用多行字符串的方式: http://jser.me/2013/08/20/javascript%E7%9A%84%E5%87%A0%E7%A7%8D%E4%BD%BF%E7%94%A8%E5%A4%9A%E8%A1%8C%E5%AD%97%E7%AC%A6%E4%B8%B2%E7%9A%84%E6%96%B9%E5%BC%8F.html
          function heredoc(fn) {
              return fn.toString().split('\n').slice(1,-1).join('\n') + '\n'
          }

          var reportForm = new Vue({
            el: '#report-form',
            data: {
              report_types: [
                {
                    value: '1',
                    label: '默认'
                },
                {
                    value: '2',
                    label: '自定义'
                }
              ],
              reportForm: {
                report_type: '<?php echo $reportType ?>',
                report_cname: '<?php echo $reportCname ?>',
                report_ename: '<?php echo $reportEname ?>',
                report_desc: '<?php echo $reportDesc ?>',
                report_sql: heredoc(function(){/*<?php echo "\r\n" . $reportSql . "\r\n" ?>*/}),
                report_dev: true
              },
              reportProdForm: {
                report_type: '<?php echo $reportType ?>',
                report_cname: '<?php echo $reportCname ?>',
                report_ename: '<?php echo $reportEname ?>',
                report_desc: '<?php echo $reportDesc ?>',
                report_sql: heredoc(function(){/*<?php echo "\r\n" . $reportSql . "\r\n" ?>*/}),
                report_prod: true
              },
              ruleValidate: {
                  report_cname: [
                      { required: true, message: '报表中文昵称输入不能为空', trigger: 'blur' }
                  ],
                  report_sql: [
                      { required: true, message: '报表所需SQL输入不能为空', trigger: 'blur' }
                  ]
              }
            },
            created: function () {
              // console.log('message is: ' + this.report_cname);
            },
            computed: {
            },
            watch: {
            },
            methods: {
              createReport: function () {
                this.$refs["reportForm"].validate((valid) => {
                    if (valid) {
                        this.$Message.success('生成成功!');
                        var params = "";
                        for (var key in this.$data.reportForm) {
                            if (this.$data.reportForm[key]) {
                                if (params != "") {
                                    params += "&";
                                }
                              params += key + "=" + encodeURIComponent(this.$data.reportForm[key]);
                            }
                        }
                        // console.log("<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params);
                        location.href = "<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params;
                    } else {
                        this.$Message.error('请按格式要求填写表格!');
                    }
                });
              },
              createReportProd: function () {
                if (this.$refs["reportProdForm"]) {
                  var params = "";
                  for (var key in this.$data.reportProdForm) {
                      if (this.$data.reportProdForm[key]) {
                          if (params != "") {
                              params += "&";
                          }
                        params += key + "=" + encodeURIComponent(this.$data.reportProdForm[key]);
                      }
                  }
                  // console.log("<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params);
                  location.href = "<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params;

                }
              },
              refresh: function() {
                console.log("refresh");
                 this.$refs["reportForm"].resetFields();
              }
            }
          });
        </script>
    </body>
</html>
