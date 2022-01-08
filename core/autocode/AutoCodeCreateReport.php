<?php

/**
 * -----------| 工具类:自动生成代码-一键生成前后台报表模板 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen <skygreen2001@gmail.com>
 */

class AutoCodeCreateReport extends AutoCode
{
    /**
     * 英文名称字数限制
     */
    public static $ename_limit_count = 16;
    /**
     * 自动生成代码-一键生成前后台报表模板
     * 报表代码生成模板文件目录: /core/autocode/view/template/report.php
     * @param array $config 配置
     *              - $isProd: 生成至正式目录, false: 生成至model目录下
     *              - $reportType: 生成报表的类型，1: 默认，2:自定义
     *              - $reportCname: 中文名称
     *              - $reportEname: 英文名称
     *              - $reportDesc : 报表描述
     *              - $reportSql  : 报表SQL
     */
    public static function AutoCode($config)
    {
        extract($config);
        include( "template" . DS . "report.php" );

        // 初始化配置
        if (!isset($reportEname) || empty($reportEname)) {
            //没有定义英文名时，之后写算法取出中文名首字母
            $reportEname = UtilPinyin::getPinyinName( $reportCname );
            if (strlen($reportEname) > self::$ename_limit_count ) $reportEname = substr($reportEname, 0, self::$ename_limit_count);
        } else{
            $reportEname = ucfirst($reportEname);
        }
        if (!isset($reportDesc) || empty($reportDesc)) {
            $reportDesc = $reportCname;
        }

        if (!empty($reportSql)) {
            $reportSql = str_replace(";", "", $reportSql);
            $reportSql = trim($reportSql);
        }

        if ($isProd) {
            $dest_root_path = Gc::$nav_root_path;
        } else {
            $dest_root_path = self::$save_dir;
        }
        $dest_home_path = $dest_root_path . "home" . DS . "report" . DS;
        $dest_view_path = $dest_home_path . "view" . DS . "default" . DS;

        $selCols = ServiceReport::getSqlSelCols($reportSql);

        // 默认生成的报表文件内容
        if ($reportType == "1") {
            $report_config_file      = Gc::$nav_root_path . "misc" . DS . "sql" . DS . "report.php";
            $dest_report_config_file = $dest_root_path . "misc" . DS . "sql" . DS . "report.php";
            $fileContent = file_get_contents($report_config_file);

            $configCols = "";
            foreach ($selCols as $selCol) {
                $configCols .= "        \"" . $selCol . "\"," . HH;
            }
            if ($configCols ) $configCols = substr($configCols, 0, strlen($configCols) - strlen(HH) - 1);
            include( "template" . DS . "report.php" );

            $fileContent = $fileContent . $sql_config_template;
            self::saveDefineToFile($dest_report_config_file, $fileContent);

        } else {
            // 自定义生成的报表文件内容
            $tplColumns = "";
            foreach ($selCols as $selCol) {
                $tplColumns .= "                                    <th>" . $selCol . "</th>" . HH;
            }
            if ($tplColumns ) $tplColumns = substr($tplColumns, 0, strlen($tplColumns) - 2);

            $jsColumns = "";
            foreach ($selCols as $selCol) {
                $jsColumns .= "                { data: \"" . $selCol . "\" }," . HH;
            }
            if ($jsColumns ) $jsColumns = substr($jsColumns, 0, strlen($jsColumns) - strlen(HH) - 1);


            $reptTimeCol = ServiceReport::getFilterTime( $reportSql );
            $reptOrderBy = ServiceReport::getOrderBy( $reportSql );
            $filterCols  = ServiceReport::getFilterCols( $reportSql );
            $reptFiltCol = "";
            foreach ($filterCols as $filterCol) {
                $reptFiltCol .= "'$filterCol', ";
            }
            if ($reptFiltCol ) $reptFiltCol = substr($reptFiltCol, 0, strlen($reptFiltCol) - 2);

            include( "template" . DS . "report.php" );
            /**
             * api_file   : 生成API文件[为页面提供数据的接口文件]: 模板文件中$api_template
             * action_file: 生成Action类[控制某一页的报表]: 模板文件中$action_template
             * tpl_file   : 生成页面tpl文件: 模板文件中$tpl_template
             * js_file    : 生成页面js文件: 模板文件中$js_template
             */
            $dest_file_path = array(
                "api_file"    => $dest_root_path . "api" . DS."web" . DS . "report" . DS . "report" . $reportEname . ".php",
                "action_file" => $dest_home_path . "action" . DS . "Action_Report" . $reportEname . ".php",
                "tpl_file"    => $dest_view_path . "core" . DS . "report" . $reportEname . DS . "lists.tpl",
                "js_file"     => $dest_view_path . "js" . DS . "core" . DS . "report" . $reportEname . ".js",
            );
            self::saveDefineToFile($dest_file_path["api_file"], $api_template);
            self::saveDefineToFile($dest_file_path["action_file"], $action_template);
            self::saveDefineToFile($dest_file_path["tpl_file"], $tpl_template);
            self::saveDefineToFile($dest_file_path["js_file"], $js_template);

            self::createServiceFile( $isProd, $reportType, $reportCname, $reportEname, $reportDesc, $reportSql, $dest_home_path);
        }

        // 两种生成报表的类型都会创建的文件内容
        self::createLayoutFile( $isProd, $reportType, $reportCname, $reportEname, $reportDesc, $reportSql, $dest_view_path );
    }

    /**
     * 创建报表需修改的服务层文件
     * @param string $isProd: 生成至正式目录, false: 生成至model目录下
     * @param string $reportType: 生成报表的类型，1: 默认，2:自定义
     * @param string $reportCname: 中文名称
     * @param string $reportEname: 英文名称
     * @param string $reportDesc : 报表描述
     * @param string $reportSql  : 报表SQL
     * @param string $dest_home_path: 存储项目根路径
     */
    private static function createServiceFile($isProd, $reportType, $reportCname, $reportEname, $reportDesc, $reportSql, $dest_home_path) {
        include( "template" . DS . "report.php" );
        $dest_service_path = $dest_home_path . "src" . DS . "services" . DS;

        // 生成报表服务类[控制导出报表]: 模板文件中$service_template
        self::saveDefineToFile($dest_service_path . "Service" . $reportEname . ".php", $service_template);

        //**向管理报表服务类的类中添加实例化方法
        $prod_root_path           = Gc::$nav_root_path . "home" . DS . "report" . DS;
        $prod_manage_service_path = $prod_root_path . "src" . DS . "services" . DS . "Manager_ReportService.php";
        $fileContent = file_get_contents($prod_manage_service_path);
        if (!empty($fileContent)) {
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
            if (empty($hasContains)) {
                $startContent = $startContent . $currentService;
            }
            $fileContent = $startContent . $endContent;
        }
        $dest_manage_service_path = $dest_service_path . "Manager_ReportService.php";
        self::saveDefineToFile($dest_manage_service_path, $fileContent);
    }

    /**
     * 创建报表需修改的布局表示层页面
     * @param string $isProd: 生成至正式目录, false: 生成至model目录下
     * @param string $reportType: 生成报表的类型，1: 默认，2:自定义
     * @param string $reportCname: 中文名称
     * @param string $reportEname: 英文名称
     * @param string $reportDesc : 报表描述
     * @param string $reportSql  : 报表SQL
     * @param string $dest_view_path: 表示层存储根路径
     */
    private static function createLayoutFile($isProd, $reportType, $reportCname, $reportEname, $reportDesc, $reportSql, $dest_view_path) {
        include( "template" . DS . "report.php" );

        /**
         * navbar_file: 修改页面导航栏[navbar、sidebar 添加新增报表导航]
         * action_file: 生成Action类[控制某一页的报表]: 模板文件中$action_template
         */
        $dest_file_path = array(
            "navbar_file"  => $dest_view_path . "layout" . DS . "normal" . DS . "navbar.tpl",
            "sidebar_file" => $dest_view_path . "layout" . DS . "normal" . DS . "sidebar.tpl",
        );

        $prod_view_path = Gc::$nav_root_path . "home" . DS . "report" . DS . "view" . DS . "default" . DS;
        $prod_file_path = array(
            "navbar_file"  => $prod_view_path . "layout" . DS . "normal" . DS . "navbar.tpl",
            "sidebar_file" => $prod_view_path . "layout" . DS . "normal" . DS . "sidebar.tpl",
        );

        if ($reportType == "1") {
            $report_url = "report.reportone.index&rtype=" . $reportEname;
        } else {
            $report_url = "report.report$reportEname.lists";
        }
        $fileContent = file_get_contents($prod_file_path["navbar_file"]);
        if (!empty($fileContent)) {
            $endPos = strpos($fileContent, "<li id=\"searchbar-li\"");
            $startContent = substr($fileContent, 0, $endPos);
            $endContent = substr($fileContent, $endPos);
            $startPos = strpos($startContent, "</ul>");
            $startContent = substr($startContent, 0, $startPos);

            $currentNav = <<<NAV
                <li><a href="{\$url_base}index.php?go=$report_url">$reportCname</a></li>
              </ul>
            </li>

NAV;
            $hasContains = strpos($startContent, "report$reportEname");
            if (empty($hasContains)) {
                $startContent = $startContent . "  " . ltrim($currentNav);
            }
            $fileContent = $startContent . "            " . $endContent;
        }

        self::saveDefineToFile($dest_file_path["navbar_file"], $fileContent);

        $fileContent = file_get_contents($prod_file_path["sidebar_file"]);
        if (!empty($fileContent)) {
            $endPos = strrpos($fileContent, "</li>");
            $startContent = substr_replace($fileContent, "", $endPos);
            $endContent = substr($fileContent, $endPos);
            $currentSide = <<<SIDE
    <a href="{\$url_base}index.php?go=$report_url"><i class="fa fa-life-ring"></i> <span>$reportCname</span></a>

SIDE;
            $hasContains = strpos($startContent, "report$reportEname");
            if (empty($hasContains)) {
                $startContent = $startContent . $currentSide;
            }
            $fileContent = $startContent . "          " . $endContent;
        }
        self::saveDefineToFile($dest_file_path["sidebar_file"], $fileContent);
    }

}

?>
