
<?php
require_once ("../../../init.php");

$title = "一键生成指定SQL查询的报表";
$url_base = UtilNet::urlbase();
$reportCname = !empty($_REQUEST['report_cname']) ? $_REQUEST['report_cname'] : "";
$reportEname = !empty($_REQUEST['report_ename']) ? $_REQUEST['report_ename'] : "";
$reportDesc = !empty($_REQUEST['report_desc']) ? $_REQUEST['report_desc'] : "";
$reportSql = !empty($_REQUEST['report_sql']) ? $_REQUEST['report_sql'] : "";

AutoCodeCreateReport::$save_dir = Gc::$nav_root_path . "model" . DS;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>一键生成指定SQL查询的报表</title>
        <meta name="description" content="Hello">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="icon" href="<?php echo $url_base ?>favicon.ico" mce_href="favicon.ico" type="image/x-icon">

        <link rel="stylesheet" href="../../../misc/css/common.min.css">
        <link rel="stylesheet" href="../../../misc/css/normalize.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- <h1 align="center"><?php echo $title ?></h1> -->
        <div id="report-form" class="container" align="center">

          <card name='reportCard' style="width:600px;margin: 150px auto 50px auto;">
            <p slot="title" style="text-align:left;">
                <i class="ivu-icon ivu-icon-ios-construct"></i>
                <?php echo $title ?>
            </p>
            <a href="#" slot="extra" @click.prevent="refresh">
                <i class="ivu-icon ivu-icon-ios-refresh-circle"></i>
                Change
            </a>
            <i-form ref="reportForm" :model="reportForm" :rules="ruleValidate" label-position="right" :label-width="100">
                <div class="input-contianer">
                    <!-- <label><i style="color: red;">*</i> 报表中文昵称</label> -->
                    <form-item label="报表中文昵称" prop="report_cname">
                      <i-input v-model="reportForm.report_cname" name="report_cname" placeholder="" clearable  />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <!-- <label>&nbsp;&nbsp;报表英文昵称</label> -->
                    <form-item label="报表英文昵称" prop="report_ename">
                      <i-input v-model="reportForm.report_ename" name="report_ename" placeholder="" clearable />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <!-- <label><i style="color: red;">*</i> 报表详细描述</label> -->
                    <form-item label="报表详细描述" prop="report_desc">
                      <i-input type="textarea" v-model="reportForm.report_desc" name="report_desc" :rows="6" :cols="58" :autosize="{minRows: 2,maxRows: 5}"  />
                    </form-item>
                </div>
                <div class="input-contianer">
                    <!-- <label><i style="color: red;">*</i> 报表所需SQL</label -->
                    <form-item label="报表所需SQL" prop="report_sql">
                      <i-input type="textarea" v-model="reportForm.report_sql" name="report_sql" :rows="10" :cols="58" :autosize="{minRows: 2,maxRows: 20}"  />
                    </form-item>
                </div>

                <div>
                    <input type="hidden" name="report_dev" value="true" />
                </div>
                <i-button type="primary" html-type="submit" @click="createReport">生成</i-button><br><br>
            </i-form>

          <?php
          $reportDev = !empty($_REQUEST['report_dev']) ? $_REQUEST['report_dev'] : "";
          if ($reportDev && !empty($reportCname) && !empty($reportDesc) && !empty($reportSql)){
              AutoCodeCreateReport::AutoCode(false,$reportCname,$reportEname,$reportDesc,$reportSql);
              LogMe::log("-----生成至model目录-----");
              echo "<h4 style=\"text-align: center\">请去model目录下查看新创建的报表文件，确认无误后点击覆盖生成，生成正式文件</h4>";
              echo "
                <form>
                  <input class=\"input_save_dir\" type=\"hidden\" name=\"report_cname\" value=\"$reportCname\" />
                  <input class=\"input_save_dir\" type=\"hidden\" name=\"report_ename\" value=\"$reportEname\" />
                  <input class=\"input_save_dir\" type=\"hidden\" name=\"report_desc\" value=\"$reportDesc\" />
                  <input class=\"input_save_dir\" type=\"hidden\" name=\"report_sql\" value=\"$reportSql\" />
                  <input type='hidden' name='report_prod' value='true'>
                  <i-button type='primary'>覆盖生成</i-button>
                </form>";
          }

          $reportProd = !empty($_REQUEST['report_prod']) ? $_REQUEST['report_prod'] : "";
          if ($reportProd && !empty($reportCname) && !empty($reportDesc) && !empty($reportSql)){
              AutoCodeCreateReport::AutoCode(true,$reportCname,$reportEname,$reportDesc,$reportSql);
              LogMe::log("-----生成至正式目录-----");
              echo "<h4 style='text-align: center'>报表文件已生成至正式目录，请自行查看</h4>";
          }

          ?>


          </card>
          </div>
        <script src="../../../misc/js/common/bower.min.js"></script>
        <script type="text/javascript">
          Vue.config.debug = true;
          Vue.config.devtools = true;

          var reportForm = new Vue({
            el: '#report-form',
            data: {
              reportForm: {
                report_cname: '<?php echo $reportCname ?>',
                report_ename: '<?php echo $reportEname ?>',
                report_desc: '<?php echo $reportDesc ?>',
                report_sql: '<?php echo $reportSql ?>'
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
              // `this` points to the vm instance
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
                        this.$Message.success('Success!');
                        var params = "";
                        for (var key in this.$data.reportForm) {
                            if (this.$data.reportForm[key]) {
                                if (params != "") {
                                    params += "&";
                                }
                              params += key + "=" + encodeURIComponent(this.$data.reportForm[key]);
                            }
                        }
                        console.log("<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params);
                        location.href = "<?php echo $url_base;?>tools/tools/autocode/report_onekey.php?" + params;
                    } else {
                        // this.$Message.error('Fail!');
                    }
                })
              },
              refresh: function() {
                console.log("refresh");
              }
            }
          });
        </script>
    </body>
</html>
