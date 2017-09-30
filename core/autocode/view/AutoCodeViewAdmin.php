<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-后台管理的表示层
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewAdmin extends AutoCodeView
{
    /**
     * 将表列定义转换成表示层js文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function js_core($tablename, $fieldInfo)
    {
        $appname      = self::$appName;
        $classname    = self::getClassname($tablename);
        $instancename = self::getInstancename($tablename);
        $realId       = DataObjectSpec::getRealIDColumnName($classname);

        $column_contents  = "";
        $imgColumnDefs    = "";
        $bitColumnDefs    = "";
        $statusColumnDefs = "";
        $editImgColumn    = "";
        $editEnumColumn   = "";
        $editDateColumn   = "";
        $editBitColumn    = "";
        $editMulSelColumn = "";
        $editValidRules   = "";
        $editValidMsg     = "";
        $row_no           = 0;
        foreach ($fieldInfo as $fieldname=>$field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ){
                $column_contents .= "                { data: \"$fieldname\" },\r\n";
                // $show_columns[]   = $fieldname;
                $field_comment    = $field["Comment"];
                $isImage          = self::columnIsImage( $fieldname, $field_comment );
                if ( $isImage ) {
                    $editImgColumn .= '        $.edit.fileBrowser("#iconImage", "#iconImageTxt", "#iconImageDiv");';
                    include("template" . DS . "admin.php");
                    //todo: alt="' + row.$realId + '"
                    $imgColumnDefs .= $js_sub_template_img;
                }
                // todo:
                // $editValidRules
                // blog_name:{
                //     required:true
                // },
                // sequenceNo: {
                //     required:true,
                //     number:true,
                // }
                //
                // $editValidMsg
                // blog_name:"此项为必填项",
                // sequenceNo:{
                //     required:"此项为必填项",
                //     number:"此项必须为数字"
                // }
                $datatype = self::comment_type($field["Type"]);
                switch ($datatype) {
                  case 'bit':
                    include("template" . DS . "admin.php");
                    $bitColumnDefs .= $js_sub_template_bit;
                    $editBitColumn .= "        \$(\"input[name='$fieldname']\").bootstrapSwitch();\r\n";
                    $editBitColumn .= "        \$('input[name=\"$fieldname\"]').on('switchChange.bootstrapSwitch', function(event, state) {\r\n";
                    $editBitColumn .= "            console.log(state);\r\n";
                    $editBitColumn .= "        });\r\n";
                    break;
                  case 'enum':
                    $enum_columnDefine = self::enumDefines($field["Comment"]);
                    if ( isset($enum_columnDefine ) && ( count($enum_columnDefine)>0 ) )
                    {
                        $status_switch_show = "";
                        $color_status       = "status-fail";
                        $edit_json_enums = "";
                        foreach ($enum_columnDefine as $enum_column) {
                            $enum_val = $enum_column['value'];
                            if ( $enum_val == '0' ) $color_status = "status-wait";
                            if ( $enum_val == '1' ) $color_status = "status-pass";
                            $enumcomment         = $enum_column['comment'];
                            $status_switch_show .= "                      case '$enum_val':\r\n";
                            $status_switch_show .= "                        return '<span class=\"$color_status\">$enumcomment</span>';\r\n";
                            $status_switch_show .= "                        break;\r\n";
                            $edit_json_enums .= "        {\r\n";
                            $edit_json_enums .= "            \"id\": \"$enum_val\",\r\n";
                            $edit_json_enums .= "            \"text\": \"$enumcomment\"\r\n";
                            $edit_json_enums .= "        },\r\n";
                        }
                        $edit_json_enums = substr($edit_json_enums, 0, strlen($edit_json_enums) - 3);
                        include("template" . DS . "admin.php");
                        $statusColumnDefs .= $js_sub_template_status;
                        $fieldname{0}      = strtoupper($fieldname{0});
                        $editEnumColumn   .= "        \$.edit.select2('#$fieldname', \"home/admin/data/" . $instancename . "$fieldname.json\", default_keyword_id, default_keyword_text);\r\n";
                        $defineJsonFileContent = $edit_sub_json_template;
                        self::saveJsonDefineToDir( $instancename, $fieldname, $defineJsonFileContent );
                    }
                    break;
                  case 'date':
                    $editDateColumn .= "        \$.edit.datetimePicker('#$fieldname');\r\n";
                    break;
                  // todo:
                  // $editMultiselectColumn
                  // $.edit.multiselect('#categoryIds');
                  default:
                    break;
                }
                $row_no ++;
            }
        }
        $column_contents .= "                { data: \"$realId\" }";
        include("template" . DS . "admin.php");
        $idColumnDefs     = $js_sub_template_id;
        include("template" . DS . "admin.php");
        $result = $js_template;
        return $result;
    }

    /**
     * 将表列定义转换成表示层js所需Api Web文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function api_web_admin($tablename, $fieldInfo)
    {
        $appname      = self::$appName;
        $classname    = self::getClassname($tablename);
        $instancename = self::getInstancename($tablename);
        $realId       = DataObjectSpec::getRealIDColumnName($classname);
        $editApiImg   = "";
        $editApiRela  = "";
        foreach ($fieldInfo as $fieldname=>$field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ){
                $field_comment    = $field["Comment"];
                $isImage          = self::columnIsImage( $fieldname, $field_comment );
                if ( $isImage ) {
                    $editApiImg  .= "    if (!empty(\${$instancename}->$fieldname)){\r\n".
                                    "      \${$instancename}->$fieldname = Gc::\$upload_url . \"images/\" . \${$instancename}->$fieldname;\r\n".
                                    "    }\r\n";
                }
                $datatype = self::comment_type($field["Type"]);
                switch ($datatype) {
                  case 'bit':
                    break;
                  case 'enum':
                    break;
                  case 'date':
                    break;
                  default:
                    break;
                }
                //TODO
                // if (!empty($blog->user_id)){
                //   $user = User::get_by_id($blog->user_id);
                //   if ($user) $blog->user_name = $user->username;
                // }
            }
        }
        include("template" . DS . "admin.php");
        $result = $api_web_template;
        return $result;
    }

    /**
     * 保存生成的Json代码到指定命名规范的文件中
     * @param string $instantceName 对象实例名称
     * @param string $fieldname field属性名称
     * @param string $defineJsFileContent 生成的代码
     */
    private static function saveJsonDefineToDir($instantceName, $fieldname, $defineJsonFileContent)
    {
        $dir           = self::$save_dir . Gc::$module_root . DS . "admin" . DS . "data" . DS;
        $fieldname{0}  = strtoupper($fieldname{0});
        $filename      = $instantceName . $fieldname . Config_F::SUFFIX_FILE_JSON;
        $relative_path = str_replace( self::$save_dir, "", $dir . $filename );
        AutoCodePreviewReport::$json_admin_files[$classname . $filename] = $relative_path;
        return self::saveDefineToDir($dir, $filename, $defineJsonFileContent );
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_lists($tablename,$fieldInfo)
    {
        $appname       = self::$appName;
        $table_comment = self::tableCommentKey($tablename);
        $instancename  = self::getInstancename($tablename);
        $classname     = self::getClassname($tablename);
        $realId        = DataObjectSpec::getRealIDColumnName($classname);

        $column_contents = "";
        foreach ($fieldInfo as $fieldname=>$field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ){
                $field_comment=$field["Comment"];
                if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                {
                    $field_comment=preg_split("/[\s,]+/", $field_comment);
                    $field_comment=$field_comment[0];
                }
                $column_contents .= "                                    <th>$field_comment</th>\r\n";
            }
        }

        include("template" . DS . "admin.php");
        $result = $list_template;
        $result = self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_edit($tablename, $fieldInfo)
    {
        $appname       = self::$appName;
        $table_comment = self::tableCommentKey($tablename);
        $appname       = self::$appName;
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $edit_contents = "";
        $enumJsContent = "";
        $realId        = "id";

        $text_area_fieldname = array();
        $hasImgFormFlag      = "";

        $ueTextareacontents  = "";
        $ckeditor_prepare    = "    ";
        $ueEditor_prepare    = "";
        foreach ( $fieldInfo as $fieldname => $field )
        {
            $field_comment = $field["Comment"];
            if ( contain($field_comment,"\r") || contain($field_comment,"\n") )
            {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            $realId = DataObjectSpec::getRealIDColumnName( $classname );
            if ( ( $realId != $fieldname ) && self::isNotColumnKeywork( $fieldname, $field_comment ) ){
                $isImage = self::columnIsImage( $fieldname, $value );
                if ( self::columnIsTextArea( $fieldname, $field["Type"] ) ) {
                    $edit_contents .= "                     <div class=\"form-group\">\r\n".
                                      "                          <label for=\"" . $fieldname . "\" class=\"col-sm-2 control-label\">" . $field_comment . "</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      "                              <div class=\"clearfix\">\r\n".
                                      "                                  <textarea class=\"form-control\" id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" rows=\"6\" cols=\"60\" placeholder=\"" . $field_comment ."\">{\$" . $instancename . "." . $fieldname . "}</textarea>\r\n".
                                      "                              </div>\r\n".
                                      "                          </div>\r\n".
                                      "                      </div>\r\n";
                    $ckeditor_prepare .= "ckeditor_replace_$fieldname();";
                    $ueEditor_prepare .= "pageInit_ue_$fieldname();";
                } else if ( $isImage ) {
                    $hasImgFormFlag = "enctype=\"multipart/form-data\"";
                    $edit_contents .= "                      <div class=\"form-group\">\r\n".
                                      "                          <label for=\"iconImage\" class=\"col-sm-2 control-label\">" . $field_comment ."</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      "                              <div class=\"input-group col-sm-9\">\r\n".
                                      "                                  <input type=\"text\" id=\"iconImageTxt\" readonly=\"readonly\" class=\"form-control\" />\r\n".
                                      "                                  <span class=\"btn-file-browser btn-success input-group-addon\" id=\"iconImageDiv\">浏览 ...</span>\r\n".
                                      "                                  <input type=\"file\" id=\"iconImage\" name=\"icon_url\" style=\"display:none;\" accept=\"image/*\" />\r\n".
                                      "                              </div>\r\n".
                                      "                          </div>\r\n".
                                      "                      </div>\r\n";
                } else {
                    $datatype = self::comment_type($field["Type"]);
                    $edit_contents .= "                      <div class=\"form-group\">\r\n".
                                      "                          <label for=\"" . $fieldname . "\" class=\"col-sm-2 control-label\">" . $field_comment ."</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n";
                    switch ($datatype) {
                        //todo
                        //<select id="categoryIds" name="categoryId[]" class="form-control" multiple>
                        case "bit":
                          $edit_contents .= "                              <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"checkbox\" {if \$" . $instancename . "." . $fieldname . "} checked {/if} data-on-text=\"是\" data-off-text=\"否\" />\r\n";
                          break;
                        case "enum":
                          $edit_contents .= "                              <select id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" class=\"form-control\"></select>\r\n";
                          $enumJsContent .= "    <script type=\"text/javascript\">\r\n".
                                            "        var default_keyword_id   = \"{\$" . $instancename . "." . $fieldname . "}\";\r\n".
                                            "        var default_keyword_text = \"{\$" . $instancename . "." . $fieldname . "Show}\";\r\n".
                                            "    </script>\r\n";
                          break;
                        case "date":
                          $edit_contents .= "                              <div class=\"input-group col-sm-9 datetimeStyle\" id=\"" . $fieldname . "\">\r\n".
                                            "                                  <input id=\"" . $fieldname . "Str\" name=\"" . $fieldname . "\" class=\"form-control date-picker\" type=\"text\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n".
                                            "                                  <span class=\"input-group-addon\"><i class=\"icon-calendar bigger-110\"></i></span>\r\n".
                                            "                              </div>\r\n";
                          break;
                        case "int":
                        case "bigint":
                          $edit_contents .= "                              <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"number\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n";
                          break;
                        default:
                          $edit_contents .= "                              <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"text\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n";
                          break;
                    }
                    $edit_contents .= "                          </div>\r\n".
                                      "                      </div>\r\n";
                }
            }
        }

        if ( !empty($ueEditor_prepare) ) {
            $textareapreparesentence = <<<EDIT
    {if (\$online_editor=="CKEditor")}
        {\$editorHtml}
        <script>
        $(function(){
            $ckeditor_prepare
        });
        </script>
    {/if}
EDIT;
            $ueTextareacontents = <<<UETC
    {if (\$online_editor == "UEditor")}
        <script>$ueEditor_prepare</script>
    {/if}
UETC;
        }
        if ( !empty($edit_contents) && (strlen($edit_contents) > 2) ) {
            $edit_contents = substr($edit_contents, 0, strlen($edit_contents) - 2);
        }
        include("template" . DS . "admin.php");
        $result = $edit_template;
        if ( !empty($ueEditor_prepare) ) {
            $result = $textareapreparesentence . "\r\n" . $result;
        }
        $result = self::tableToViewTplDefine( $result );
        return $result;
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_view($tablename, $fieldInfo)
    {
        $appname       = self::$appName;
        $table_comment = self::tableCommentKey($tablename);
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $realId        = DataObjectSpec::getRealIDColumnName($classname);
        $showColumns   = "";
        foreach ($fieldInfo as $fieldname => $field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ) {
                $field_comment = $field["Comment"];
                $isImage       = self::columnIsImage( $fieldname, $field_comment );
                if ( contain($field_comment,"\r") || contain($field_comment,"\n") )
                {
                    $field_comment = preg_split("/[\s,]+/", $field_comment);
                    $field_comment = $field_comment[0];
                }
                $showColName  = $fieldname;
                $datatype     = self::comment_type($field["Type"]);
                switch ($datatype) {
                    case 'bit':
                    case 'enum':
                      $showColName .= "Show";
                      break;
                    case 'date':
                      $showColName .= "|date_format:\"%Y-%m-%d\"";
                      break;
                }
                $showColumns .= "                    <dl>\r\n";
                $showColumns .= "                      <dt><span>$field_comment</span></dt>\r\n";
                if ( $isImage ) {
                    $showColumns .= "                      <dd>\r\n";
                    $showColumns .= "                        {if $$instancename.$showColName}\r\n";
                    // todo
                    // alt="{$blog.blog_name}"
                    $showColumns .= "                        <span><a href=\"{\$$instancename.$showColName}\" target=\"_blank\"><img class=\"img-thumbnail\" src=\"{\$$instancename.$showColName}\" alt=\"{\$$instancename.$realId}\" /></a></span><br>\r\n";
                    $showColumns .= "                        <span>存储路径:</span><br><span>{\$$instancename.$showColName}</span>\r\n";
                    $showColumns .= "                        {else}\r\n";
                    $showColumns .= "                        <span></span>\r\n";
                    $showColumns .= "                        {/if}\r\n";
                    $showColumns .= "                      </dd>\r\n";
                } else {
                    $showColumns .= "                      <dd><span>{\$$instancename.$showColName}</span></dd>\r\n";
                }
                $showColumns .= "                    </dl>\r\n";
                // todo
                // <dd><span>{foreach item=category from=$blog.categorys}{$category.name}&nbsp;{/foreach}</span></dd>
            }
        }

        $commitTimeStr = EnumColumnNameDefault::COMMITTIME;
        $updateTimeStr = EnumColumnNameDefault::UPDATETIME;
        include("template" . DS . "admin.php");
        $result = $view_template;
        $result = self::tableToViewTplDefine($result);
        return $result;
    }
}
