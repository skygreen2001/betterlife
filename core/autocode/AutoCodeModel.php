<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-生成单张表或者对应类的前后台所有模板文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeModel extends AutoCode
{
    /**
     * 自动生成代码-一键生成前后台所有模板文件
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names="")
    {
        $dest_directory=Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS;
        $filename=$dest_directory."autocode.config.xml";
        AutoCodeValidate::run($table_names);
        if(Config_AutoCode::ALWAYS_AUTOCODE_XML_NEW)AutoCodeConfig::run();
        if (!file_exists($filename)){
            AutoCodeConfig::run();
            die("&nbsp;&nbsp;自动生成代码的配置文件已生成，请再次运行以生成所有web应用代码！");
        }
        self::$showReport.=AutoCodeFoldHelper::foldEffectReady();
        //生成实体数据对象类
        AutoCodeDomain::$save_dir =self::$save_dir;
        AutoCodeDomain::$type     =2;
        self::$showReport.=AutoCodeFoldHelper::foldbeforedomain();
        AutoCodeDomain::AutoCode($table_names);
        self::$showReport.=AutoCodeFoldHelper::foldafterdomain();
        AutoCode::$isOutputCss=false;

        if(Config_AutoCode::ONLY_DOMAIN){
            self::$showReport.= "</div>";

            //将新添加的内容放置在文件最后作为可覆盖的内容
            AutoCodePreviewReport::init();
            return;
        }
        //生成提供服务类[前端Service类]
        AutoCodeService::$save_dir =self::$save_dir;
        self::$showReport.=AutoCodeFoldHelper::foldbeforeservice();
        AutoCodeService::$type     =2;
        AutoCodeService::AutoCode($table_names);
        self::$showReport.=AutoCodeFoldHelper::foldafterservice();

        //生成Action类[前端]
        AutoCodeAction::$save_dir =self::$save_dir;
        self::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
        AutoCodeAction::$type     =0;
        AutoCodeAction::AutoCode($table_names);
        AutoCodeAction::$type     =1;
        AutoCodeAction::AutoCode($table_names);
        self::$showReport.=AutoCodeFoldHelper::foldafteraction();
        //生成前端表示层
        self::$showReport.=AutoCodeFoldHelper::foldbeforeviewdefault();
        AutoCodeViewDefault::$save_dir =self::$save_dir;
        AutoCodeViewDefault::$type     =0;
        AutoCodeViewDefault::AutoCode($table_names);
        AutoCodeViewDefault::$type     =1;
        AutoCodeViewDefault::AutoCode($table_names);
        self::$showReport.=AutoCodeFoldHelper::foldafterviewdefault();

        //将新添加的内容放置在文件最后作为可覆盖的内容
        AutoCodePreviewReport::init();

        //前台
        self::createManageService($table_names);

        //模板
        self::createModelIndex($table_names);
        self::$showReport.= "</div>";
    }

    /**
     * 用户输入需求
     */
    public static function UserInput( $title="", $inputArr=null, $default_value="", $more_content="" )
    {
        $default_dir=Gc::$nav_root_path."model".DS;
        self::$save_dir=$default_dir;

        self::init();
        $inputArr = array();
        if (self::$tableList){
            foreach (self::$tableList as $tablename) {
                $inputArr[$tablename]=$tablename;
            }
        }
        include("template" . DS . "form" . DS . "model.php");
        echo $userinput_model;
    }

    /**
     * 创建服务管理类
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function createManageService($table_names="")
    {
        $file_manage_service_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReport::$manage_service_file;
        if(file_exists($file_manage_service_file))
        {
            $tableList=self::tableListByTable_names($table_names);
            $content=file_get_contents($file_manage_service_file);
            foreach($tableList as $tablename){
                $result=AutoCodeService::createManageService($tablename);
                $section_define  = $result["section_define"];
                $section_content = $result["section_content"];

                if(!contain($content,$section_define)){
                    $ctrl=substr($content,0,strpos($content, "     * 提供服务:")-8);
                    $ctrr=substr($content, strpos($content, "     * 提供服务:")-8);
                    $content=$ctrl.$section_define.$ctrr;
                    $content=trim($content);
                    $ctrl=substr($content,0,strrpos($content,"}"));
                    $ctrr=substr($content,strrpos($content,"}"));
                    $content=trim($ctrl)."\r\n\r\n".rtrim($section_content)."\r\n".$ctrr;
                }
            }
            $ffile_manage_service_file_model=self::$save_dir.AutoCodePreviewReport::$manage_service_file;
            file_put_contents($ffile_manage_service_file_model, $content);
        }
    }

    /**
     * 生成后台服务配置文件:service.config.xml
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function createModelIndex($table_names="")
    {
        $file_model_index_file=Gc::$nav_root_path.Gc::$module_root.DS.AutoCodePreviewReport::$model_index_file;
        if(file_exists($file_model_index_file))
        {
            $tableList=self::tableListByTable_names($table_names);
            $content=file_get_contents($file_model_index_file);
            $appname=AutoCodePreviewReport::$m_model;
            foreach($tableList as $tablename){
                $instancename=self::getInstancename($tablename);
                if(!contain($content,"go=model.$instancename.lists")){
                    $table_comment=self::tableCommentKey($tablename);
                    $section_content="        <tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>\r\n";
                    $ctrl=substr($content,0,strrpos($content, "</tr>")+5);
                    $ctrr=substr($content, strrpos($content,"</tr>")+5);
                    $content=$ctrl."\r\n".$section_content.$ctrr;
                }
            }
            $file_model_index_file_model=self::$save_dir.AutoCodePreviewReport::$model_index_file;
            file_put_contents($file_model_index_file_model, $content);
        }
    }

    /**
     * 覆盖原文件内容
     * @param array $files 需覆盖的文件
     * @param string $model_save_dir 模板文件存储的路径
     */
    public static function overwrite($files,$model_save_dir)
    {
        $overwrite_not_arr=array();//发现Mac电脑因为权限不能写文件需提示
        foreach ($files as $file)
        {
            $file_overwrite=Gc::$nav_root_path.Gc::$module_root.DS.$file;
            $content=file_get_contents($model_save_dir.$file);
            $dir_overwrite=dirname($file_overwrite);
            UtilFileSystem::createDir($dir_overwrite);
            file_put_contents($file_overwrite, $content) or
            $overwrite_not_arr[]=$dir_overwrite;
        }
        if(count($overwrite_not_arr)>0){
            $overwrite_not_dir_str="";
            $overwrite_not_run_arr=array();
            $app_dir="model";
            foreach ($overwrite_not_arr as $overwrite_not_dir) {
                if(contain($overwrite_not_dir,Gc::$nav_root_path.Gc::$module_root.DS.$app_dir.DS))
                {
                    if(!in_array(Gc::$nav_root_path.Gc::$module_root.DS.$app_dir.DS, $overwrite_not_run_arr)){
                        $overwrite_not_run_arr[]=Gc::$nav_root_path.Gc::$module_root.DS.$app_dir.DS;
                    }
                }else{
                    $overwrite_not_run_arr[]=$overwrite_not_dir;
                }
            }

            $isMac = (contain(strtolower(php_uname()),"darwin")) ? true : false;
            foreach ($overwrite_not_run_arr as $overwrite_not_dir) {
                $overwrite_not_dir_str .=
                    "sudo mkdir -p " . $overwrite_not_dir . "<br/>" . str_repeat("&nbsp;",8);

                $overwrite_not_dir_str .=
                    "sudo chmod -R 0777 " . $overwrite_not_dir . "<br/>" . str_repeat("&nbsp;",8);
                // if (!$isMac){
                //     $info .=
                //         "sudo chown -R www-data:www-data " . $overwrite_not_dir . "<br/>" . str_repeat("&nbsp;",8) .
                //         "sudo chmod -R 0755 " . $overwrite_not_dir . "</p>";
                // }
            }

            $os = $isMac ? "MacOS" : "Linux";
            die("<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>因为安全原因，需要手动在操作系统中创建以下目录<br/>" .
                "$os 系统需要执行指令:<br/>" . str_repeat("&nbsp;",8) .
                $overwrite_not_dir_str . "</p>");
        }

    }

}
