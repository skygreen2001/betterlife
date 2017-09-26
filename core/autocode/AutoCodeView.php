<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-前端默认的表示层
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeView extends AutoCode
{
    /**
     * 表示层生成定义的方式<br/>
     * 0.生成前台所需的表示层页面。<br/>
     * 1.生成标准的增删改查模板所需的表示层页面。<br/>
     */
    public static $type;
    /**
     * View生成tpl所在的应用名称，默认同网站应用的名称
     */
    public static $appName;
    /**
     * 表示层所在的目录
     */
    public static $view_core;
    /**
     * 表示层完整的保存路径
     */
    public static $view_dir_full;
    /**
     * 设置必需的路径
     */
    public static function pathset()
    {
        switch (self::$type) {
           case 0:
             self::$app_dir=Gc::$appName;
             if (empty(self::$appName)){
                self::$appName=Gc::$appName;
             }
             break;
           case 1:
             self::$app_dir="model";
             self::$appName="model";
             break;
        }
        self::$view_dir_full=self::$save_dir.self::$app_dir.DS.Config_F::VIEW_VIEW.DS.Gc::$self_theme_dir.DS.Config_F::VIEW_CORE.DS;
    }

    /**
     * 自动生成代码-前端默认的表示层
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names="")
    {
        self::pathset();
        self::init();
        if (self::$isOutputCss)self::$showReport.= UtilCss::form_css()."\r\n";
        switch (self::$type) {
           case 0:
                self::$showReport.=AutoCodeFoldHelper::foldEffectCommon("Content_41");
                self::$showReport.= "<font color='#FF0000'>生成前台所需的表示层页面:</font></a>";
                self::$showReport.= '<div id="Content_41" style="display:none;">';

                $link_view_default_dir_href="file:///".str_replace("\\", "/", self::$view_dir_full);
                self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_view_default_dir_href."'>".self::$view_dir_full."</a></font><br/><br/>";
                AutoCodeViewModel::sync(self::$appName, self::$view_dir_full);
                AutoCodeViewModel::createModelIndexFile($table_names);
                self::createFrontModelPages($table_names);
                self::$showReport.= "</div><br>";
             break;
           case 1:
                self::$showReport.=AutoCodeFoldHelper::foldEffectCommon("Content_42");
                self::$showReport.= "<font color='#FF0000'>生成标准的增删改查模板表示层页面:</font></a>";
                self::$showReport.= '<div id="Content_42" style="display:none;">';

                $link_view_default_dir_href="file:///".str_replace("\\", "/", self::$view_dir_full);
                self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_view_default_dir_href."'>".self::$view_dir_full."</a></font><br/><br/>";

                AutoCodeViewModel::sync(self::$appName, self::$view_dir_full);
                AutoCodeViewModel::createModelIndexFile($table_names);

                $fieldInfos=self::fieldInfosByTable_names($table_names);
                foreach ($fieldInfos as $tablename=>$fieldInfo){
                    $tpl_listsContent=AutoCodeViewModel::tpl_lists($tablename,$fieldInfo);
                    $filename="lists".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_listsContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                    $tpl_viewContent=AutoCodeViewModel::tpl_view($tablename,$fieldInfo);
                    $filename="view".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_viewContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                    $tpl_editContent=AutoCodeViewModel::tpl_edit($tablename,$fieldInfo);
                    $filename="edit".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_editContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                }
                self::$showReport.= "</div><br>";
             break;
        }
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value="", $title="", $inputArr=null, $more_content="")
    {
        $inputArr=array(
            "0"=>"生成前台所需的表示层页面",
            "1"=>"生成标准的增删改查模板所需的表示层页面"
        );
        return parent::UserInput("一键生成表示层页面",$inputArr,$default_value);
    }

    /**
     * 将表列定义转换成表示层tpl文件定义的内容
     * @param string $contents 页面内容
     */
    public static function tableToViewTplDefine($contents)
    {
        $result="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
                "{block name=body}\r\n".
                "$contents\r\n".
                "{/block}";
        return $result;
    }

    /**
     * 生成前台所需的表示层页面
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    private static function createFrontModelPages($table_names="")
    {

        $fieldInfos=self::fieldInfosByTable_names($table_names);
        foreach ($fieldInfos as $tablename=>$fieldInfo){
            if(self::$type==0) {
                $classname=self::getClassname($tablename);
                if ($classname=="Admin")continue;
            }
            $table_comment=self::tableCommentKey($tablename);
            $appname=self::$appName;
            $instancename=self::getInstancename($tablename);
            $link="    <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.view\">查看</my:a>|<my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.edit\">修改</my:a>";
            $back_index="    <my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a></div>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>".$table_comment."列表</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="lists".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
            $link="     <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">返回列表</my:a>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>查看".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="view".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>编辑".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="edit".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
        }
    }

    /**
     * 保存生成的tpl代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $defineTplFileContent 生成的代码
     * @param string $filename 文件名称
     */
    private static function saveTplDefineToDir($tablename,$defineTplFileContent,$filename)
    {
        $package =self::getInstancename($tablename);
        $dir=self::$view_dir_full.$package.DS;

        $classname=self::getClassname($tablename);
        $relative_path=str_replace(self::$save_dir, "", $dir.$filename);
        switch (self::$type) {
            case 0:
                AutoCodePreviewReport::$view_front_files[$classname.$filename]=$relative_path;
                break;
            case 1:
                AutoCodePreviewReport::$view_model_files[$classname.$filename]=$relative_path;
                break;
        }
        return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
    }
}
