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
     * 后台关系显示
     */
    private static $admin_relation_view = array(

    );
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
        $editM2MSelColumn = "";
        $editValidRules   = "";
        $editValidMsg     = "";
        $row_no           = 0;

        $classNameField   = self::getShowFieldName( $classname );
        $belong_has_ones  = array();

        if ( array_key_exists($classname, self::$relation_all) ) $relationSpec = self::$relation_all[$classname];
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) )
        {
            //从属一对一关系规范定义(如果存在)
            if ( array_key_exists("belong_has_one", $relationSpec) )
            {
                $belong_has_one       = $relationSpec["belong_has_one"];
                foreach ($belong_has_one as $key => $value) {
                    $re_realId        = DataObjectSpec::getRealIDColumnName( $key );
                    $classNameField   = self::getShowFieldName( $key );
                    $belong_has_ones[$re_realId]["i"] = $value;
                    $belong_has_ones[$re_realId]["s"] = $classNameField;
                }
            }
            //多对多关系规范定义(如果存在)
            if ( array_key_exists("many_many", $relationSpec) )
            {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename( $key );
                    $instancename_rela = self::getInstancename( $talname_rela );
                    $editM2MSelColumn .= "        \$.edit.select2('#$realId_m2m', \"api/web/select/{$instancename_rela}.php\", select_{$instancename_rela});\r\n";
                }
            }
        }

        foreach ($fieldInfo as $fieldname => $field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ) {
                if ( in_array($fieldname, array_keys($belong_has_ones)) ) {
                    $show_fieldname    = $belong_has_ones[$fieldname]["s"];
                    $instancename_rela = $belong_has_ones[$fieldname]["i"];
                    if ( $show_fieldname == "name" ) $show_fieldname = strtolower($instancename_rela) . "_" . $show_fieldname;
                    $column_contents  .= "                { data: \"" . $show_fieldname . "\" },\r\n";
                    $editMulSelColumn .= "        \$.edit.select2('#{$fieldname}', \"\", select_" . $instancename_rela . ");\r\n";
                } else {
                    $column_contents  .= "                { data: \"$fieldname\" },\r\n";
                }

                $field_comment    = $field["Comment"];
                $isImage          = self::columnIsImage( $fieldname, $field_comment );
                if ( $isImage ) {
                    $editImgColumn .= '        $.edit.fileBrowser("#iconImage", "#iconImageTxt", "#iconImageDiv");';
                    $altImgVal      = $classNameField;
                    if ( empty($altImgVal) ) $altImgVal = $realId;
                    include("template" . DS . "admin.php");
                    $imgColumnDefs .= $js_sub_template_img;
                }
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
                        $fieldname_u       = $fieldname;
                        $fieldname_u{0}    = strtoupper($fieldname_u{0});
                        $editEnumColumn   .= "        \$.edit.select2('#$fieldname', \"home/admin/data/" . $instancename . "$fieldname_u.json\", select_{$fieldname});";
                        $defineJsonFileContent = $edit_sub_json_template;
                        self::saveJsonDefineToDir( $instancename, $fieldname, $defineJsonFileContent );
                    }
                    break;
                  case 'date':
                    $editDateColumn .= "        \$.edit.datetimePicker('#$fieldname');";
                    break;
                  default:
                    break;
                }
                $row_no ++;
            }
        }
        if ( !empty($classNameField) ) {
            $editValidRules = "                $classNameField: {\r\n".
                              "                    required: true\r\n".
                              "                }";
            $editValidMsg   = "                $classNameField: \"此项为必填项\"";
        }
        $column_contents .= "                { data: \"$realId\" }\r\n";
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
                    $editApiImg  .= "    if ( !empty(\${$instancename}->$fieldname) ) {\r\n".
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
                $editApiRela = self::relationFieldShow($instancename, $classname, $fieldInfo);
            }
        }
        $classNameField = self::getShowFieldName( $classname );
        include("template" . DS . "admin.php");
        $result = $api_web_template;
        return $result;
    }

    /**
     * 将表列定义转换成表示层js所需Select Web文件定义的内容
     * @param string $tablename 表名
     */
    public static function save_select_web_admin($tablename)
    {
        $classname    = self::getClassname($tablename);
        if ( array_key_exists($classname, self::$relation_all) ) $relationSpec = self::$relation_all[$classname];
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) )
        {
            //多对多关系规范定义(如果存在)
            if ( array_key_exists("many_many", $relationSpec) )
            {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename( $key );
                    $classname_rela    = self::getClassname($talname_rela);
                    $instancename_rela = self::getInstancename( $talname_rela );

                    include("template" . DS . "admin.php");
                    $phpName          = self::saveApiSelectDefineToDir( $talname_rela, $select_web_template );
                    self::$showReport.= "生成导出完成:$tablename => $phpName!<br/>";
                }
            }
        }
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
        return self::saveDefineToDir( $dir, $filename, $defineJsonFileContent );
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
        foreach ($fieldInfo as $fieldname => $field)
        {
            if ( ($realId != $fieldname) && self::isNotColumnKeywork( $fieldname, $field_comment ) ) {
                $field_comment = $field["Comment"];
                if ( contain( $field_comment, "\r" ) || contain( $field_comment, "\n" ) )
                {
                    $field_comment = preg_split("/[\s,]+/", $field_comment);
                    $field_comment = $field_comment[0];
                }
                $field_comment = str_replace( "标识", "", $field_comment );
                $field_comment = str_replace( "编号", "", $field_comment );
                $column_contents .= "                                    <th>$field_comment</th>\r\n";
            }
        }

        include("template" . DS . "admin.php");
        $result = $list_template;
        $result = self::tableToViewTplDefine( $result );
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
        $table_comment = self::tableCommentKey( $tablename );
        $appname       = self::$appName;
        $classname     = self::getClassname( $tablename );
        $instancename  = self::getInstancename( $tablename );
        $edit_contents = "";
        $enumJsContent = "";
        $realId        = "id";

        $text_area_fieldname = array();
        $hasImgFormFlag      = "";

        $ueTextareacontents  = "";
        $ckeditor_prepare    = "    ";
        $ueEditor_prepare    = "";

        $belong_has_ones     = array();
        $rela_m2m_content    = "";
        $rela_js_content     = "";

        if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) )
        {
            //从属一对一关系规范定义(如果存在)
            if ( array_key_exists("belong_has_one", $relationSpec) )
            {
                $belong_has_one       = $relationSpec["belong_has_one"];
                foreach ($belong_has_one as $key => $value) {
                    $realId           = DataObjectSpec::getRealIDColumnName($key);
                    $classNameField   = self::getShowFieldName( $key );
                    $relation_content = "                              <select id=\"$realId\" name=\"$realId\" class=\"form-control\">\r\n".
                                        "                                  <option value=\"-1\">请选择</option>\r\n".
                                        "                                  {foreach item=$value from=\${$value}s}\r\n".
                                        "                                  <option value=\"{\${$value}.$realId}\">{\${$value}.{$classNameField}}</option>\r\n".
                                        "                                  {/foreach}\r\n".
                                        "                              </select>\r\n";
                    $rela_js_content .= "        var select_{$value} = {};\r\n".
                                        "        {if \${$instancename}.{$value}}\r\n".
                                        "        select_{$value}.id   = \"{\${$instancename}.{$value}.{$realId}}\";\r\n".
                                        "        select_{$value}.text = \"{\${$instancename}.{$value}.{$classNameField}}\";\r\n".
                                        "        select_{$value} =  new Array(select_{$value});\r\n".
                                        "        {/if}\r\n\r\n";
                    $belong_has_ones[$realId] = $relation_content;
                }
            }


            //多对多关系规范定义(如果存在)
            if ( array_key_exists("many_many", $relationSpec) )
            {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename( $key );
                    $instancename_rela = self::getInstancename( $talname_rela );
                    $m2m_table_comment = self::tableCommentKey($talname_rela);
                    $classNameField    = self::getShowFieldName( $key );
                    $rela_js_content  .= "        var select_{$instancename_rela} =  new Array({count(\${$instancename}.{$value})});\r\n".
                                         "        {foreach \${$instancename}.{$value} as \$$instancename_rela}\r\n\r\n".
                                         "        var $instancename_rela       = {};\r\n".
                                         "        $instancename_rela.id        = \"{\$$instancename_rela.$realId_m2m}\";\r\n".
                                         "        $instancename_rela.text      = \"{\$$instancename_rela.$classNameField}\";\r\n".
                                         "        select_{$instancename_rela}[{\${$instancename_rela}@index}] = $instancename_rela;\r\n".
                                         "        {/foreach}\r\n\r\n";
                    $rela_m2m_content .= "                      <div class=\"form-group\">\r\n".
                                         "                          <label for=\"$realId_m2m\" class=\"col-sm-2 control-label\">$m2m_table_comment</label>\r\n".
                                         "                          <div class=\"col-sm-9\">\r\n".
                                         "                              <select id=\"$realId_m2m\" name=\"{$realId_m2m}[]\" class=\"form-control\" multiple ></select>\r\n".
                                         "                          </div>\r\n".
                                         "                      </div>\r\n";
                }
            }
        }

        foreach ($fieldInfo as $fieldname => $field)
        {
            $field_comment = $field["Comment"];
            if ( contain( $field_comment, "\r" ) || contain( $field_comment, "\n" ) )
            {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            $realId = DataObjectSpec::getRealIDColumnName( $classname );
            if ( ( $realId != $fieldname ) && self::isNotColumnKeywork( $fieldname, $field_comment ) ) {
                $isImage = self::columnIsImage( $fieldname, $value );
                $edit_contents .= "                      <div class=\"form-group\">\r\n";
                if ( self::columnIsTextArea( $fieldname, $field["Type"] ) ) {
                    $edit_contents .= "                          <label for=\"" . $fieldname . "\" class=\"col-sm-2 control-label\">" . $field_comment . "</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      "                              <div class=\"clearfix\">\r\n".
                                      "                                  <textarea class=\"form-control\" id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" rows=\"6\" cols=\"60\" placeholder=\"" . $field_comment ."\">{\$" . $instancename . "." . $fieldname . "}</textarea>\r\n".
                                      "                              </div>\r\n".
                                      "                          </div>\r\n";
                    $ckeditor_prepare .= "ckeditor_replace_$fieldname();";
                    $ueEditor_prepare .= "pageInit_ue_$fieldname();";
                } else if ( $isImage ) {
                    $hasImgFormFlag = "enctype=\"multipart/form-data\"";
                    $edit_contents .= "                          <label for=\"iconImage\" class=\"col-sm-2 control-label\">" . $field_comment ."</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      "                              <div class=\"input-group col-sm-9\">\r\n".
                                      "                                  <input type=\"text\" id=\"iconImageTxt\" readonly=\"readonly\" class=\"form-control\" />\r\n".
                                      "                                  <span class=\"btn-file-browser btn-success input-group-addon\" id=\"iconImageDiv\">浏览 ...</span>\r\n".
                                      "                                  <input type=\"file\" id=\"iconImage\" name=\"icon_url\" style=\"display:none;\" accept=\"image/*\" />\r\n".
                                      "                              </div>\r\n".
                                      "                          </div>\r\n";
                } else if ( in_array($fieldname, array_keys($belong_has_ones)) ) {
                    $field_comment = str_replace( "标识", "", $field_comment );
                    $field_comment = str_replace( "编号", "", $field_comment );
                    $edit_contents .= "                          <label for=\"" . $fieldname . "\" class=\"col-sm-2 control-label\">" . $field_comment ."</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      $belong_has_ones[$fieldname].
                                      "                          </div>\r\n";
                } else {
                    $datatype = self::comment_type($field["Type"]);
                    $edit_contents .= "                          <label for=\"" . $fieldname . "\" class=\"col-sm-2 control-label\">" . $field_comment ."</label>\r\n".
                                      "                          <div class=\"col-sm-9\">\r\n".
                                      "                              <div class=\"clearfix\">\r\n";
                    switch ($datatype) {
                        case "bit":
                          $edit_contents .= "                                  <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"checkbox\" {if \$" . $instancename . "." . $fieldname . "} checked {/if} data-on-text=\"是\" data-off-text=\"否\" />\r\n";
                          break;
                        case "enum":
                          $edit_contents .= "                                  <select id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" class=\"form-control\"></select>\r\n";
                          $enumJsContent .= "    <script type=\"text/javascript\">\r\n".
                                            "        var select_{$fieldname} = {};\r\n".
                                            "        {if \${$instancename}.{$fieldname}}\r\n".
                                            "        select_{$fieldname}.id   = \"{\$" . $instancename . "." . $fieldname . "}\";\r\n".
                                            "        select_{$fieldname}.text = \"{\$" . $instancename . "." . $fieldname . "Show}\";\r\n".
                                            "        {/if}\r\n".
                                            "        select_{$fieldname} =  new Array(select_{$fieldname});\r\n".
                                            "    </script>\r\n";
                          break;
                        case "date":
                          $edit_contents .= "                                  <div class=\"input-group col-sm-9 datetimeStyle\" id=\"" . $fieldname . "\">\r\n".
                                            "                                      <input id=\"" . $fieldname . "Str\" name=\"" . $fieldname . "\" class=\"form-control date-picker\" type=\"text\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n".
                                            "                                      <span class=\"input-group-addon\"><i class=\"icon-calendar bigger-110\"></i></span>\r\n".
                                            "                                  </div>\r\n";
                          break;
                        case "int":
                        case "bigint":
                          $edit_contents .= "                                  <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"number\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n";
                          break;
                        default:
                          $edit_contents .= "                                  <input id=\"" . $fieldname . "\" name=\"" . $fieldname . "\" placeholder=\"" . $field_comment . "\" class=\"form-control\" type=\"text\" value=\"{\$" . $instancename . "." . $fieldname . "}\"/>\r\n";
                          break;
                    }
                    $edit_contents .= "                              </div>\r\n".
                                      "                          </div>\r\n";
                }
                $edit_contents .= "                      </div>\r\n";
            }
        }
        $edit_contents .= $rela_m2m_content;
        if ( !empty($rela_js_content) ) {
            $rela_js_content = "    <script type=\"text/javascript\">\r\n".
                               $rela_js_content.
                               "    </script>\r\n";
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
        $viewM2MCols   = "";

        $classNameField = self::getShowFieldName( $classname );

        if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) )
        {
            //多对多关系规范定义(如果存在)
            if ( array_key_exists("many_many", $relationSpec) )
            {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename( $key );
                    $instancename_rela = self::getInstancename( $talname_rela );
                    $m2m_table_comment = self::tableCommentKey($talname_rela);
                    $classNameField    = self::getShowFieldName( $key );
                    $viewM2MCols      .= "                    <dl>\r\n".
                                         "                      <dt><span>$m2m_table_comment</span></dt>\r\n".
                                         "                      <dd>{foreach item=$instancename_rela from=\${$instancename}.{$value}}<span>{\${$instancename_rela}.{$classNameField}}</span> {/foreach}</dd>\r\n".
                                         "                    </dl>\r\n";
                }
            }
        }

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

                if ( is_array(self::$relation_viewfield) && ( count(self::$relation_viewfield) > 0 ) )
                {
                    if ( array_key_exists($classname, self::$relation_viewfield) ) {
                        $relationSpecs  = self::$relation_viewfield[$classname];
                        if ( array_key_exists($fieldname, $relationSpecs) ) {
                            $relationShow = $relationSpecs[$fieldname];
                            foreach ( $relationShow as $key => $value ) {
                                $talname_rela   = self::getTablename( $key );
                                $insname_rela   = self::getInstancename( $talname_rela );
                                $classNameField = self::getShowFieldNameByClassname( $key, true );
                                if ( empty($classNameField) ) $classNameField = $realId;
                                $showColName   = $insname_rela . "." . $classNameField;
                                $field_comment = str_replace( "标识", "", $field_comment );
                                $field_comment = str_replace( "编号", "", $field_comment );
                            }
                        }
                    }
                }

                $showColumns .= "                    <dl>\r\n";
                $showColumns .= "                      <dt><span>$field_comment</span></dt>\r\n";
                if ( $isImage ) {
                    $showColumns .= "                      <dd>\r\n";
                    $showColumns .= "                        {if $$instancename.$showColName}\r\n";
                    if ( empty($classNameField) ) $classNameField = $realId;
                    $showColumns .= "                        <span><a href=\"{\$$instancename.$showColName}\" target=\"_blank\"><img class=\"img-thumbnail\" src=\"{\$$instancename.$showColName}\" alt=\"{\$$instancename.$classNameField}\" /></a></span><br>\r\n";
                    $showColumns .= "                        <span>存储路径:</span><br><span>{\$$instancename.$showColName}</span>\r\n";
                    $showColumns .= "                        {else}\r\n";
                    $showColumns .= "                        <span></span>\r\n";
                    $showColumns .= "                        {/if}\r\n";
                    $showColumns .= "                      </dd>\r\n";
                } else {
                    $showColumns .= "                      <dd><span>{\$$instancename.$showColName}</span></dd>\r\n";
                }
                $showColumns .= "                    </dl>\r\n";
            }
        }
        $showColumns  .= $viewM2MCols;
        $commitTimeStr = EnumColumnNameDefault::COMMITTIME;
        $updateTimeStr = EnumColumnNameDefault::UPDATETIME;
        include("template" . DS . "admin.php");
        $result = $view_template;
        $result = self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 显示关系列
     * @param mixed $instance_name 实体变量
     * @param mixed $classname 数据对象列名
     * @param mixed $fieldInfo 表列信息列表
     */
    protected static function relationFieldShow($instance_name, $classname, $fieldInfo)
    {
        $result = "";
        if ( is_array(self::$relation_viewfield) && ( count(self::$relation_viewfield) > 0 ) )
        {
            if ( array_key_exists($classname, self::$relation_viewfield) ) {
                $relationSpecs  = self::$relation_viewfield[$classname];
                foreach ( $fieldInfo as $fieldname => $field ) {
                    if ( array_key_exists($fieldname, $relationSpecs) ) {
                        $relationShow = $relationSpecs[$fieldname];
                        foreach ( $relationShow as $key => $value ) {
                            $realId         = DataObjectSpec::getRealIDColumnName($key);
                            $show_fieldname = $value;
                            if ( $realId != $fieldname ) {
                                $show_fieldname .= "_" . $fieldname;
                                if ( contain( $show_fieldname, "_id" ) ) {
                                    $show_fieldname = str_replace("_id", "", $show_fieldname);
                                }
                            }
                            if ( $show_fieldname == "name" ) $show_fieldname = strtolower($key) . "_" . $value;
                            $i_name      = $key;
                            $i_name{0}   = strtolower($i_name{0});
                            if ( !array_key_exists("$show_fieldname", $fieldInfo) ){
                                $result .= "    if ( !empty(\${$instance_name}->$fieldname) ) {\r\n".
                                           "      \${$i_name}_i = $key::get_by_id(\${$instance_name}->$fieldname);\r\n".
                                           "      if ( \${$i_name}_i ) \${$instance_name}->$show_fieldname = \${$i_name}_i->$value;\r\n".
                                           "    }\r\n";
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
