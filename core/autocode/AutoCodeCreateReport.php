<?php
/**
 * 工具类:自动生成代码-一键生成前后台报表模板<br/>
 * Created by IntelliJ IDEA.
 * User: harlan
 * Date: 2019/3/1
 * Time: 16:46
 */

class AutoCodeCreateReport extends AutoCode
{
    public static $ename_limit_count = 16;
    /**
     * 自动生成代码-一键生成前后台报表模板
     * 报表代码生成模板文件目录: /core/autocode/view/template/report.php
     * @param $isProd true: 生成至正式目录, false: 生成至model目录下
     * @param $reportCname: 中文名称
     * @param $reportEname: 英文名称
     * @param $reportDesc : 报表描述
     * @param $reportSql  : 报表SQL
     */
    public static function AutoCode($isProd = false, $reportCname = "", $reportEname = "", $reportDesc = "", $reportSql = "")
    {
        include( "template" . DS . "report.php" );
        if ( !isset($reportEname) || empty($reportEname) ) {
            //没有定义英文名时，之后写算法取出中文名首字母
            $reportEname = UtilPinyin::getPinyinName($reportCname,2);
            if (strlen($reportEname)>self::$ename_limit_count) $reportEname = substr($reportEname, 0, self::$ename_limit_count);
        }
        $prod_root_path = Gc::$nav_root_path;
        $dev_root_path  = self::$save_dir;
        // **生成API文件[为页面提供数据的接口文件]: 模板文件中$api_template
        $filename = "report" . $reportEname . ".php";
        if ( !empty($isProd) ) {
            //正式目录
            $dir = $prod_root_path . "api" . DS."web" . DS . "report" . DS;
        } else {
            //model目录
            $dir = $dev_root_path . "api" . DS . "web" . DS . "report" . DS;
        }

        self::saveDefineToDir($dir, $filename, $api_template);

        //**生成Action类[控制某一页的报表]: 模板文件中$action_template
        $filename = "Action_Report" . $reportEname . ".php";

        $prod_root_path .= "home" . DS . "report" . DS;
        $dev_root_path  .= "home" . DS . "report" . DS;
        if ( !empty($isProd) ) {
            $dir = $prod_root_path . "action" . DS;
        } else {
            $dir = $dev_root_path . "action" . DS;
        }
        self::saveDefineToDir($dir, $filename, $action_template);


        //**生成页面tpl文件: 模板文件中$tpl_template
        $selCols = ServiceReport::getSqlSelCols($reportSql);
        $tplColumns = "";
        foreach ($selCols as $selCol) {
            $tplColumns .= "<th>" . $selCol . "</th>";
        }
        include( "template" . DS . "report.php" );
        $filename = "lists.tpl";

        $prod_root_view_path = $prod_root_path . "view" . DS . "default" . DS;
        $dev_root_view_path  = $dev_root_path . "view" . DS . "default" . DS;
        if ( !empty($isProd) ) {
            $dir = $prod_root_view_path . "core" . DS . "report" . $reportEname . DS;
        } else {
            $dir = $dev_root_view_path . "core" . DS . "report" . $reportEname . DS;
        }
        self::saveDefineToDir($dir, $filename, $tpl_template);


        //**生成页面js文件: 模板文件中$js_template
        $jsColumns = "";
        foreach ($selCols as $selCol) {
            $jsColumns .= "{ data: \"" . $selCol . "\" },";
        }
        include( "template" . DS . "report.php" );
        $filename = "report" . $reportEname . ".js";
        if ( !empty($isProd) ) {
            $dir = $prod_root_view_path . "js" . DS . "core" . DS;
        } else {
            $dir = $dev_root_view_path . "js" . DS . "core" . DS;
        }
        self::saveDefineToDir($dir, $filename, $js_template);


        //**修改页面导航栏[navbar、sidebar 添加新增报表导航]:
        //  navbar 目录: /home/report/view/default/layout/normal/navbar.tpl
        $filePath = $prod_root_view_path . "layout" . DS . "normal" . DS . "navbar.tpl";
        $fileContent = file_get_contents($filePath);
        if ( !empty($fileContent) ) {
            $endPos = strpos($fileContent, "<li id=\"searchbar-li\"");
            $startContent = substr($fileContent, 0, $endPos);
            $endContent = substr($fileContent, $endPos);
            $startPos = strpos($startContent, "</ul>");
            $startContent = substr($startContent, 0, $startPos);

            $currentNav = <<<NAV
                <li><a href="{\$url_base}index.php?go=report.report$reportEname.lists">$reportCname</a></li>
              </ul>
            </li>

NAV;
            $hasContains = strpos($startContent, "report$reportEname");
            if ( empty($hasContains) ) {
                $startContent = $startContent . "  " . ltrim($currentNav);
            }
            $fileContent = $startContent . "            " . $endContent;
        }
        $filename = "navbar.tpl";
        if ( !empty($isProd) ) {
            $dir = $prod_root_view_path . "layout" . DS . "normal" . DS;
        } else {
            $dir = $dev_root_view_path . "layout" . DS . "normal" . DS;
        }
        self::saveDefineToDir($dir, $filename, $fileContent);

        //  sidebar目录: /home/report/view/default/layout/normal/sidebar.tpl
        $filePath = $prod_root_view_path . "layout" . DS . "normal" . DS . "sidebar.tpl";
        $fileContent = file_get_contents($filePath);
        if ( !empty($fileContent) ) {
            $endPos = strrpos($fileContent, "</li>");
            $startContent = substr_replace($fileContent, "", $endPos);
            $endContent = substr($fileContent, $endPos);
            $currentSide = <<<SIDE
  <a href="{\$url_base}index.php?go=report.report$reportEname.lists"><i class="fa fa-life-ring"></i> <span>$reportCname</span></a>

SIDE;
            $hasContains = strpos($startContent, "report$reportEname");
            if ( empty($hasContains) ) {
                $startContent = $startContent . $currentSide;
            }
            $fileContent = $startContent . "          " . $endContent;
        }
        $filename = "sidebar.tpl";
        if ( !empty($isProd) ) {
            $dir = $prod_root_view_path . "layout" . DS . "normal" . DS;
        } else {
            $dir = $dev_root_view_path . "layout" . DS . "normal" . DS;
        }
        self::saveDefineToDir($dir, $filename, $fileContent);

        $prod_service_path = $prod_root_path . "src" . DS . "services" . DS;
        $dev_service_path  = $dev_root_path . "src" . DS . "services" . DS;

        //**生成报表服务类[控制导出报表]: 模板文件中$service_template
        $filename = "Service" . $reportEname . ".php";
        if ( !empty($isProd) ) {
            $dir = $prod_service_path;
        } else {
            $dir = $dev_service_path;
        }
//        LogMe::log("service_template:".$service_template);
        self::saveDefineToDir($dir, $filename, $service_template);


        //**向管理报表服务类的类中添加实例化方法: 目录/home/report/src/services/Manager_ReportService.php
        $filePath = $prod_service_path . "Manager_ReportService.php";
        $fileContent = file_get_contents($filePath);
        if ( !empty($fileContent) ) {
            $endPos = strrpos($fileContent, "}");
            $startContent = substr_replace($fileContent, "", $endPos);
            $endContent = substr($fileContent, $endPos);
            $reportEnameLo = lcfirst($reportEname);
            $currentService = <<<SERVICE
    private static \${$reportEnameLo}Service;

    /**
     * 提供服务: $reportCname
     */
    public static function service$reportEname()
    {
        if (self::\${$reportEnameLo}Service == null) {
            self::\${$reportEnameLo}Service = new Service{$reportEname}();
        }
        return self::\${$reportEnameLo}Service;
    }


SERVICE;
            $hasContains = strpos($startContent, "\${$reportEnameLo}Service");
            if ( empty($hasContains) ) {
                $startContent = $startContent . $currentService;
            }
            $fileContent = $startContent . $endContent;
        }
        $filename = "Manager_ReportService.php";
        if ( !empty($isProd) ) {
            $dir = $prod_service_path;
        } else {
            $dir = $dev_service_path;
        }
        self::saveDefineToDir($dir, $filename, $fileContent);

    }
}

?>
