<?php
/**
 * -----------| 辅助工具类:预览生成代码的报告列表 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodePreviewReport extends AutoCode
{
    /**
     * 应用:模板名称
     */
    public static $m_model             = "model";
    /**
     * 第一次运行
     */
    public static $is_first_run        = true;
    public static $domain_files        = array();
    public static $enum_files          = array();
    public static $action_front_files  = array();
    public static $action_model_files  = array();
    public static $action_admin_files  = array();
    public static $service_files       = array();
    public static $view_front_files    = array();
    public static $view_model_files    = array();
    public static $view_admin_files    = array();
    public static $js_admin_files      = array();
    public static $json_admin_files    = array();
    public static $api_admin_files     = array();
    public static $admin_layout_menu   = array();
    public static $api_select_files    = array();
    public static $manage_service_file = "";
    private static $url_base           = "";

    /**
     * 初始化
     */
    public static function init()
    {
        self::$manage_service_file = Gc::$module_root . DS . "admin" . DS . self::$dir_src . DS . AutoCodeService::$service_dir . DS . "Manager_Service.php";
    }

    /**
     * 显示报告
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function showReport($table_names = "")
    {
        $file        = "";
        $origin_file = "";
        $url_base    = Gc::$url_base;

        if ( contain( strtolower(php_uname()), "darwin" ) ) {
            $url_base     = UtilNet::urlbase();
            $file_sub_dir = str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])) . DS;
            if ( contain( $file_sub_dir, "tools" . DS ) )
                $file_sub_dir = substr($file_sub_dir, 0, strpos($file_sub_dir, "tools" . DS));
            $domainSubDir = str_replace($_SERVER["DOCUMENT_ROOT"] . "/", "", $file_sub_dir);
            if ( !endwith($url_base,$domainSubDir) ) $url_base .= $domainSubDir;
        }
        self::$url_base = $url_base;

        $dir_autocode   = $url_base . "tools/tools/autocode/";
        $layer_autocode = $dir_autocode . "layer";
        $title_model    = <<<MODEL
    <tr class="overwrite"><td colspan="3">[title]</td></tr>
MODEL;
        $color_b        = UtilCss::$color_b;
        $module_model   = <<<MODEL
    <tr class="overwrite" style="background-color:$color_b;color:white;"><td><input type="checkbox" [checked] id="select[module_name]" name="select[module_name]"  onclick="toggleGroup(this, '[module_name]')" /></td><td colspan="2">[title]</td></tr>
MODEL;
        $title       = "<a href='$layer_autocode/domain/db_domain.php' target='_blank' style='color:white;'>数据模型<Domain|Model></a>";
        $moreContent = str_replace("[title]", $title, $module_model);
        $moreContent = str_replace("[module_name]", "domain", $moreContent);

        $title        = "<a href='$layer_autocode/domain/db_domain.php' target='_blank'>实体数据对象类</a>";
        $moreContent .= str_replace("[title]", $title, $title_model);
        //[前台]生成实体数据对象
        $moreContent .= self::groupFileContentsStatus( self::$domain_files, "domain", true );

        //[前台]生成枚举类型
        if ( self::$enum_files && ( count(self::$enum_files) > 0 ) ) {
            $title        = "<a href='$layer_autocode/db_domain.php' target='_blank'>枚举类型类</a>";
            $moreContent .= str_replace("[title]", $title, $title_model);
            $moreContent .= self::groupFileContentsStatus( self::$enum_files, "domain", true );
        }

        $title        = "<a href='$layer_autocode/domain/db_domain.php' target='_blank' style='color:white;'>Ajax请求数据</a>";
        $moreContent .= str_replace("[title]", $title, $module_model);
        $moreContent  = str_replace("[module_name]", "ajax", $moreContent);

        if ( self::$json_admin_files && ( count(self::$json_admin_files) > 0 ) ) {
            $title        = "<a href='$layer_autocode/db_domain.php' target='_blank'>枚举后台所需数据Json文件</a>";
            $moreContent .= str_replace("[title]", $title, $title_model);
            $moreContent .= self::groupFileContentsStatus( self::$json_admin_files, "ajax" );
        }

        if ( self::$api_admin_files && ( count(self::$api_admin_files) > 0 ) ) {
            $title        = "<a href='$layer_autocode/db_domain.php' target='_blank'>列表后台所需Api Web文件</a>";
            $moreContent .= str_replace("[title]", $title, $title_model);
            $moreContent .= self::groupFileContentsStatus( self::$api_admin_files, "ajax" );
        }

        if ( self::$api_select_files && ( count(self::$api_select_files) > 0 ) ) {
            $title        = "<a href='$layer_autocode/view/db_view_admin.php?type=1' target='_blank'>编辑所需Api Web文件</a>";
            $moreContent .= str_replace("[title]", $title, $title_model);
            $moreContent .= self::groupFileContentsStatus( self::$api_select_files, "ajax" );
        }

        if ( Config_AutoCode::ONLY_DOMAIN ) {
            $showResult = self::modelShowDetailReport( $table_names, $moreContent );
            return $showResult;
        }

        if (Config_AutoCode::SHOW_REPORT_FRONT)
        {
            $title        = "<a href='$dir_autocode/db_all.php' target='_blank' style='color:white;'>[前台]</a>";
            $moreContent .= str_replace("[title]", $title, $module_model);
            $moreContent  = str_replace("[module_name]", "front", $moreContent);
            $moreContent  = str_replace("[checked]", "", $moreContent);

            // 生成前端Action，继承基本Action
            if ( self::$action_front_files && ( count(self::$action_front_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/db_action.php' target='_blank'>前端控制器</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$action_front_files, "front" );
            }

            // 生成前台所需的表示层页面
            if ( self::$view_front_files && ( count(self::$view_front_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/view/db_view_default.php' target='_blank'>表示层页面</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$view_front_files, "front" );
            }
        }

        if ( Config_AutoCode::SHOW_REPORT_ADMIN )
        {
            $title        = "<a href='$dir_autocode/db_admin.php' target='_blank' style='color:white;'>[后台]</a>";
            $moreContent .= str_replace("[title]", $title, $module_model);
            $moreContent  = str_replace("[module_name]", "admin", $moreContent);
            $moreContent  = str_replace("[checked]", "", $moreContent);

            //生成标准方法的Service文件
            if ( self::$service_files && ( count(self::$service_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/db_service.php?type=2' target='_blank'>标准方法的服务层文件</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$service_files, "admin" );

                //生成服务管理器
                $title             = "<a href='$layer_autocode/db_service.php?type=2' target='_blank'>服务管理类</a>";
                $moreContent      .= str_replace("[title]", $title, $title_model);
                $moreContent      .= self::groupFileContentsStatus( array(self::$manage_service_file), "admin" );
            }

            // 生成后台Action，继承基本Action
            if ( self::$action_admin_files && ( count(self::$action_admin_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/db_action.php?type=2' target='_blank'>后台控制器</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$action_admin_files, "admin" );
            }

            // 生成后台管理表示层页面
            if ( self::$admin_layout_menu && ( count(self::$admin_layout_menu) > 0 ) ) {
                $title        = "<a href='$layer_autocode/view/db_view_admin.php?type=1' target='_blank'>布局菜单页面</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$admin_layout_menu, "admin" );
            }

            if ( self::$view_admin_files && ( count(self::$view_admin_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/view/db_view_admin.php?type=1' target='_blank'>表示层页面</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$view_admin_files, "admin" );
            }

            if ( self::$js_admin_files && ( count(self::$js_admin_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/view/db_view_admin.php?type=1' target='_blank'>表示层Js文件</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$js_admin_files, "admin" );
            }

        }

        $model_module = Gc::$nav_root_path . Gc::$module_root . DS . self::$m_model . DS;
        if ( is_dir($model_module) ) {
            $title        = "<a href='$dir_autocode/db_all.php' target='_blank' style='color:white;'>[通用模版]</a>";
            $moreContent .= str_replace("[title]", $title, $module_model);
            $moreContent  = str_replace("[module_name]", "model", $moreContent);
            $moreContent  = str_replace("[checked]", "", $moreContent);

            // 生成标准的增删改查模板Action，继承基本Action
            if ( self::$action_model_files&&(count(self::$action_model_files)>0) ) {
                $title        = "<a href='$layer_autocode/db_action.php?type=1' target='_blank'>通用模版控制器</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
            }

            // 生成控制器Index和模板父类:Action_Index
            $action_model_index = Gc::$module_root . DS . "model" . DS . "action" . DS . "Action_Index.php";
            $arr_action_models  = array( $action_model_index );
            $moreContent       .= self::groupFileContentsStatus( $arr_action_models, "model" );
            $moreContent       .= self::groupFileContentsStatus( self::$action_model_files, "model" );

            //生成首页
            $title             = "<a href='$layer_autocode/view/db_view_default.php?type=1' target='_blank'>模板首页</a>";
            $moreContent      .= str_replace("[title]", $title, $title_model);
            $model_index_file  = Gc::$module_root . DS . self::$m_model . DS . Config_F::VIEW_VIEW . DS . Gc::$self_theme_dir . DS . Config_F::VIEW_CORE . DS . "index" . DS . "index" . Config_F::SUFFIX_FILE_TPL;
            $moreContent      .= self::groupFileContentsStatus( array($model_index_file), "model" );

            // 生成标准的增删改查模板表示层页面
            if ( self::$view_model_files && ( count(self::$view_model_files) > 0 ) ) {
                $title        = "<a href='$layer_autocode/view/db_view_default.php?type=1' target='_blank'>表示层页面</a>";
                $moreContent .= str_replace("[title]", $title, $title_model);
                $moreContent .= self::groupFileContentsStatus( self::$view_model_files, "model" );
            }
        }

        $showResult = self::modelShowDetailReport( $table_names, $moreContent );
        return $showResult;
    }

    private static function model() {
        $url_base = self::$url_base;
        $url_base = substr($url_base, 0, strlen($url_base)-1);
        $model = <<<MODEL
    <tr class="overwrite">
        <td class="confirm">[status]<input type="checkbox" [checked] name="overwrite[module_name][]" value="[relative_file]" /></td>
        <td class="file" style="word-wrap: break-word;">
          <a target="_blank" href="$url_base/tools/file/viewfilebyline.php?f=[file]&l=false">[file]</a>
        </td>
        <td><a href="$url_base/tools/file/viewfilebyline.php?f=[file]" target='_blank'>查看</a>|<a href="$url_base/tools/file/editfile.php?f=[file]" target='_blank'>编辑</a>|<a href="$url_base/tools/file/diff.php?old_file=[origin_file]&new_file=[file]" target="_blank">比较差异</a></td>
    </tr>
MODEL;
        return $model;
    }
    /**
     * 一组同类型文件状态报告编写
     */
    private static function groupFileContentsStatus($files, $replace_module_name, $only_once = false, $save_dir = "") {
        if ( empty($save_dir) ) $save_dir = self::$save_dir;
        $result = "";
        $status = array("<font color='#cc5854'>[会覆盖]</font>","<font color='#77cc6d'>[新生成]</font>","[未修改]");
        $model  = self::model();
        foreach ( $files as $file ) {
            $file_content = str_replace("[file]", $save_dir . $file, $model);
            $origin_file  = Gc::$nav_root_path . DS . $file;
            $file_content = str_replace("[origin_file]", $origin_file, $file_content);
            $file_content = str_replace("[relative_file]", $file, $file_content);
            if ( file_exists($origin_file) ) {
                $file_content_old = file_get_contents( $origin_file );
                $file_content_new = file_get_contents( $save_dir . $file );
                if ( $file_content_old == $file_content_new ) {
                    $file_content = str_replace("[status]", $status[2], $file_content);
                } else {
                    $file_content = str_replace("[status]", $status[0], $file_content);
                }
            } else {
                $file_content = str_replace("[status]", $status[1], $file_content);
                $file_content = str_replace("[checked]", "checked", $file_content);
                //新生成代码文件无旧代码文件就直接显示新生成代码文件
                $file_content = str_replace("old_file=" . $origin_file, "old_file=" . $save_dir . $file, $file_content);
            }

            if ( $only_once ) {
                if ( !self::$is_first_run ) {
                    $file_content = str_replace("[checked]", "", $file_content);
                }
            } else {
                $file_content = str_replace("[checked]", "", $file_content);
            }
            $file_content = str_replace("[module_name]", $replace_module_name, $file_content);
            $result      .= $file_content;
        }
        return $result;
    }

    /**
     * 生成代码的报告可交互操作
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     * @param string $content 生成代码的报告主要内容
     * @return 生成代码的报告可交互操作
     */
    private static function modelShowDetailReport($table_names, $content)
    {
        $save_dir = self::$save_dir;
        if ( is_array($table_names) ) $table_names = implode(",", $table_names);
        $preview_report_info = UtilCss::preview_report_info();
        $showResult = <<<REPORT
    $preview_report_info
    <script language="JavaScript">
    function toggleGroup(source, toggleEle)
    {
        var selectEle = 'select' + toggleEle;
        var overwriteEle = 'overwrite' + toggleEle;
        var checkbox = document.getElementById(selectEle);
        if (checkbox)checkbox.checked = source.checked;

        var checkboxes = document.getElementsByName(overwriteEle + '[]');
        if (checkboxes) {
            for(var i = 0, n = checkboxes.length; i < n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    }

    function toggle(source)
    {
        toggleGroup(source, 'domain');
        toggleGroup(source, 'ajax');
        toggleGroup(source, 'bg');
        toggleGroup(source, 'front');
        toggleGroup(source, 'model');
        toggleGroup(source, 'admin');
    }
    </script>

    <div align="center" style="margin-top:36px;">
        <form method="post"><input type="hidden" name="model_save_dir" value="$save_dir" /><input type="hidden" name="table_names" value="$table_names" />
            <table class="preview">
            <tbody>
                <tr>
                    <th class="confirm">全&nbsp;&nbsp;选<input type="checkbox" id="overwrite" name="selectAll" onclick="toggle(this)"></th>
                    <th class="file">文件路径</th>
                    <th class="file">操作</th>
                </tr>
            $content
            </tbody>
            </table>
            <input class="btnSubmit" type="submit" value='覆盖生成' /><br/><br/><br/><br/><br/><br/>
        </form>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    </div>
REPORT;
        return $showResult;
    }
}
