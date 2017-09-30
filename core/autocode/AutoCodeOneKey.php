<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-一键生成前后台所有模板文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeOneKey extends AutoCode
{
    /**
     * 自动生成代码-一键生成前后台所有模板文件
     */
    public static function AutoCode()
    {
        $dest_directory = Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS;
        $filename       = $dest_directory."autocode.config.xml";
        AutoCodeValidate::run();
        if( Config_AutoCode::ALWAYS_AUTOCODE_XML_NEW ) AutoCodeConfig::run();
        if (!file_exists($filename)){
            AutoCodeConfig::run();
            die("<br><br><div align='center'>&nbsp;&nbsp;自动生成代码的配置文件已生成，请再次运行以生成所有web应用代码！</div>");
        }
        self::$showReport        .= AutoCodeFoldHelper::foldEffectReady();
        //生成实体数据对象类
        AutoCodeDomain::$type     = 2;
        self::$showReport        .= AutoCodeFoldHelper::foldbeforedomain();
        AutoCodeDomain::AutoCode();
        self::$showReport        .= AutoCodeFoldHelper::foldafterdomain();
        AutoCode::$isOutputCss    = false;

        //生成提供服务类[前端Service类]
        self::$showReport         .= AutoCodeFoldHelper::foldbeforeservice();
        AutoCodeService::$type     = 2;
        AutoCodeService::AutoCode();
        self::$showReport         .= AutoCodeFoldHelper::foldafterservice();

        //生成Action类[前端、后台和通用模版]
        AutoCodeAction::$type     = 0;
        self::$showReport        .= AutoCodeFoldHelper::foldbeforeaction();
        AutoCodeAction::$type     = EnumAutoCodeViewType::FRONT;
        AutoCodeAction::AutoCode( $table_names );
        AutoCodeAction::$type     = EnumAutoCodeViewType::MODEL;
        AutoCodeAction::AutoCode( $table_names );
        AutoCodeAction::$type     = EnumAutoCodeViewType::ADMIN;
        AutoCodeAction::AutoCode( $table_names );
        self::$showReport        .= AutoCodeFoldHelper::foldafteraction();

        //生成表示层[前端、后台和通用模版]
        self::$showReport      .= AutoCodeFoldHelper::foldbeforeviewdefault();
        AutoCodeView::$type     = EnumAutoCodeViewType::FRONT;
        AutoCodeView::AutoCode( $table_names );
        AutoCodeView::$type     = EnumAutoCodeViewType::MODEL;
        AutoCodeView::AutoCode( $table_names );
        AutoCodeView::$type     = EnumAutoCodeViewType::ADMIN;
        AutoCodeView::AutoCode( $table_names );
        self::$showReport      .= AutoCodeFoldHelper::foldafterviewdefault();

        if(Config_AutoCode::SHOW_PREVIEW_REPORT){
            echo "<div style='width: 1000px; margin-left: 24px;'>";
            echo "  <a href='javascript:' style='cursor:pointer;' onclick=\"(document.getElementById('showPrepareWork').style.display=(document.getElementById('showPrepareWork').style.display=='none')?'':'none')\">预备工作</a>";
            echo "  <div id='showPrepareWork' style='display: none;'>";
            echo self::$showPreviewReport;
            echo "  </div>";
            echo "</div>";
        }
        echo self::$showReport;
    }

    /**
     * 用户输入需求
     */
    public static function UserInput( $title="", $inputArr=null, $default_value="", $more_content="" )
    {
        parent::UserInput("一键生成前后台所有模板文件");
    }
}
