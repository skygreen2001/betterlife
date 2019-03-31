<?php
require_once ("../../../init.php");

//AutoCodeModel::UserInput();

$title = "一键生成指定SQL查询的报表";
$url_base = UtilNet::urlbase();
$reportCname = !empty($_REQUEST['report_cname']) ? $_REQUEST['report_cname'] : "";
$reportEname = !empty($_REQUEST['report_ename']) ? $_REQUEST['report_ename'] : "";
$reportDesc = !empty($_REQUEST['report_desc']) ? $_REQUEST['report_desc'] : "";
$reportSql = !empty($_REQUEST['report_sql']) ? $_REQUEST['report_sql'] : "";

AutoCodeCreateReport::$save_dir = Gc::$nav_root_path . "model" . DS;


$userinput_model = <<<USERINPUT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="icon" href="{$url_base}favicon.ico" mce_href="favicon.ico" type="image/x-icon">
        <style type="text/css">
            html,body {
                  font:normal 15px SimSun,sans-serif;
                  border:0 none;
                  overflow:auto;
                  line-height:1.5em;
                  margin:5px 0 0;
                  padding:0;
                }
                .container {
                  width:800px;
                  margin:0 auto;
                }
                h1,h2,h3 {
                  font:bold 150% Arial,sans-serif,Microsoft YaHei UI,Microsoft YaHei, SimSun,sans-serif,STXihei;
                }
                h1{
                  margin-top: 150px;
                }
                input,button,select,textarea{
                  outline: none;
                }
                input[type="checkbox"]{
                  cursor: pointer;
                }
                form{
                  line-height:1.5em;
                }
                label {
                  vertical-align:middle;
                  width:200px;
                  height:35px;
                  text-align:right;
                  display:inline-block;
                  margin:32px 16px 6px;
                }
                input[type=text],input[type=password]{
                  border:1px solid #fff;
                  text-align:center;
                  width:200px;
                  height:28px;
                  line-height:28px;
                  color:white;
                  background:gray;

                  margin:10px 0px 0px 0px;
                  font-size: 14px;
                  color: #555;
                  vertical-align: middle;
                  background-color: #fff;
                  border: 1px solid #ccc;
                  border-radius: 4px;
                  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                }
                input.input_save_dir{
                  width:412px;
                  text-align:left;
                  padding-left:10px;
                }
                input[type=button]{
                  border:1px solid;
                  text-align:center;
                  width:80px;
                  height:28px;
                }
                input:hover {
                  border:1px solid #77cc6d;
                  color:#000;
                  background:#FFF;
                }
                textarea{
                  margin:15px 0px 15px 0px;
                  font-size: 14px;
                  color: #555;
                  vertical-align: middle;
                  background-color: #fff;
                  border: 1px solid #ccc;
                  border-radius: 4px;
                  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                }
                textarea.text_style{
                  border:1px solid gray;
                  text-align:left;
                }
                .btnSubmit{
                  margin-top:15px;
                  width:126px;
                  height:38px;
                  color:#FFF;
                  cursor:pointer;
                  font-size: 15px;
                  font-weight: 600;
                  background-color: #77cc6d;
                  border:1px solid;
                  border-color:#fff;
                  border-radius: 6px;
                }
                .container .btnSubmit{
                  margin-left:120px;
                  font-weight:600;
                }
                .btnSubmit:hover{
                  color:#000;
                  background-color:#fff;
                }
                @media screen and (-webkit-min-device-pixel-ratio:0) {
                  .btnSubmit{
                    -webkit-border-radius: 6px;
                    -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
                  }
                }
        </style>
    </head>
    <body>
        <h1 align="center">$title</h1>
        <div class="container" align="center">
        <form>
            <div>
                <label><i style="color: red;">*</i> 报表中文昵称</label>
                <input class="input_save_dir" type="text" name="report_cname" value="$reportCname" />
            </div>
            <div>
                <label>报表英文昵称</label>
                <input class="input_save_dir" type="text" name="report_ename" value="$reportEname" />
            </div>
            <div>
                <label><i style="color: red;">*</i> 报表详细描述</label>
                <textarea class="text_style" name="report_desc" rows="6" cols="58">$reportDesc</textarea>
            </div>
            <div>
                <label><i style="color: red;">*</i> 报表所需SQL</label>
                <textarea class="text_style" name="report_sql" rows="10" cols="58">$reportSql</textarea>
            </div>
            <div>
                <input type="hidden" name="report_dev" value="true" />
            </div>

            <input class="btnSubmit" type="submit" value="生成" /><br/><br/><br/>
        </form>
        </div>
    </body>
</html>
USERINPUT;
echo $userinput_model;

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
        <input class=\"btnSubmit\" type=\"submit\" value=\"覆盖生成\"
            style=\"text-align: center;margin: 0 auto;display: block\" />
      </form>";
}

$reportProd = !empty($_REQUEST['report_prod']) ? $_REQUEST['report_prod'] : "";
if ($reportProd && !empty($reportCname) && !empty($reportDesc) && !empty($reportSql)){
    AutoCodeCreateReport::AutoCode(true,$reportCname,$reportEname,$reportDesc,$reportSql);
    LogMe::log("-----生成至正式目录-----");
    echo "<h4 style='text-align: center'>报表文件已生成至正式目录，请自行查看</h4>";
}

?>
