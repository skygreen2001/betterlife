<?php
/**
 * -----------| 工具类:自动生成代码-通用模版的表示层 |-----------
 * @category betterlife
 * @package core.autocode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewModel extends AutoCodeView
{
    /**
     * 生成通用模版首页访问所有生成的链接
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function createModelIndexFile($table_names = "")
    {
        $tableInfos  = self::tableInfosByTable_names($table_names);
        $tpl_content = "    <div><h1>这是首页列表(共计数据对象".count($tableInfos)."个)</h1></div>\r\n";
        $result      = "";
        $appname     = self::$appName;
        if ( $tableInfos!=null && count($tableInfos)>0 ) {
            foreach ($tableInfos as $tablename => $tableInfo){
                $table_comment = $tableInfos[$tablename]["Comment"];
                if ( contain($table_comment,"\r") || contain($table_comment,"\n") ) {
                    $table_comment = preg_split("/[\s,]+/", $table_comment);
                    $table_comment = $table_comment[0];
                }
                $instancename = self::getInstancename($tablename);
                if ( empty($table_comment) ) $table_comment = $tablename;
                $result .= "        <tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>\r\n";
            }
        }
        $tpl_content.="    <table class=\"viewdoblock\" style=\"width: 500px;\">\r\n".
                     $result.
                     "    </table>";
        $tpl_content = self::tableToViewTplDefine($tpl_content);
        $filename    = "index" . Config_F::SUFFIX_FILE_TPL;
        $dir         = self::$view_dir_full . "index" . DS;
        return self::saveDefineToDir( $dir, $filename, $tpl_content );
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_lists($tablename,$fieldInfo)
    {
        $table_comment = self::tableCommentKey( $tablename );
        $appname       = self::$appName;
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $fieldNameAndComments = array();
        $enumColumns          = array();
        $bitColumns           = array();
        foreach ($fieldInfo as $fieldname => $field)
        {
            $field_comment = $field["Comment"];
            if ( contain( $field_comment, "\r" ) || contain( $field_comment, "\n" ) )
            {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            $fieldNameAndComments[$fieldname] = $field_comment;
            $datatype = self::comment_type($field["Type"]);
            switch ($datatype) {
              case 'bit':
                $bitColumns[] = $fieldname;
                break;
              case 'enum':
                $enumColumns[] = $fieldname;
                break;
              default:
                break;
            }
        }
        $headers  = "";
        $contents = "";

        foreach ($fieldNameAndComments as $key => $value) {
            if ( self::isNotColumnKeywork( $key ) ) {
                $isImage = self::columnIsImage( $key, $value );
                if ($isImage) continue;

                // $is_no_relation = true;
                //关系列的显示
                if ( is_array(self::$relation_viewfield) && ( count( self::$relation_viewfield )>0 ) )
                {
                    if ( array_key_exists($classname, self::$relation_viewfield) )
                    {
                        $relationSpecs = self::$relation_viewfield[$classname];
                        if ( array_key_exists($key, $relationSpecs) )
                        {
                            $relationShow = $relationSpecs[$key];
                            foreach ( $relationShow as $key_r => $value_r ) {
                                $realId = DataObjectSpec::getRealIDColumnName( $key_r );
                                if ( empty( $realId ) ) $realId = $key;
                                $show_fieldname = "";
                                if ( ( !array_key_exists($value_r, $fieldInfo) ) || ( $classname == $key_r ) ) {
                                    $show_fieldname = $value_r;
                                    if ( $realId != $key ) {
                                        if ( contain( $key, "_id" ) ) {
                                            $key = str_replace("_id", "", $key);
                                        }
                                        // if ( $key != "parent" ) {
                                        //     $show_fieldname .= "_" . $key;
                                        // }
                                    }
                                    if ($show_fieldname == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                } else {
                                    if ( $value_r == "name" ) {
                                        $show_fieldname = strtolower($key_r)."_".$value_r;
                                    }
                                }
                                
                                if ( !array_key_exists($show_fieldname, $fieldInfo) ) {
                                    $field_comment  = $value;
                                    $field_comment  = self::columnCommentKey( $field_comment, $key );
                                    $headers       .= "            <th class=\"header\">$field_comment</th>\r\n";

                                    $talname_rela   = self::getTablename( $key_r );
                                    $insname_rela   = self::getInstancename( $talname_rela );
                                    $classNameField = self::getShowFieldNameByClassname( $key_r, true );
                                    if ( empty($classNameField) ) $classNameField = $realId;
                                    $showColName    = $insname_rela . "." . $classNameField;
                                    $contents      .= "            <td class=\"content\">{\${$instancename}.$showColName}</td>\r\n";
                                    // $is_no_relation = false;
                                }

                                $fieldInfo_relationshow = self::$fieldInfos[self::getTablename( $key_r )];
                                $key_r = lcfirst($key_r);
                                if ( array_key_exists("parent_id", $fieldInfo_relationshow) ) {
                                    $headers  .= "            <th class=\"header\">{$field_comment}[全]</th>\r\n";
                                    $contents .= "            <td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td>\r\n";
                                }
                            }
                        }
                    }
                }
                // if ( $is_no_relation ) {
                    $headers      .= "            <th class=\"header\">$value</th>\r\n";

                    if ( ( count($enumColumns) > 0 ) && ( in_array($key, $enumColumns) ) ) {
                        $contents .= "            <td class=\"content\">{\${$instancename}.{$key}Show}</td>\r\n";
                    } else if ( ( count($bitColumns) > 0 ) && ( in_array($key, $bitColumns) ) ) {
                        $contents .= "            <td class=\"content\">{\${$instancename}.{$key}Show}</td>\r\n";
                    } else {
                        $contents .= "            <td class=\"content\">{\${$instancename}.$key}</td>\r\n";
                    }
                // }
            }
        }

        if ( !empty($headers) && ( strlen($headers)>2 ) ) {
            $headers  = substr($headers,0,strlen($headers)-2);
            $contents = substr($contents,0,strlen($contents)-2);
        }
        $realId = DataObjectSpec::getRealIDColumnName( $classname );
        include("template" . DS . "default.php");
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
        $table_comment = self::tableCommentKey( $tablename );
        $appname       = self::$appName;
        $classname     = self::getClassname( $tablename );
        $instancename  = self::getInstancename( $tablename );
        $text_area_fieldname  = array();

        $enumJsContent    = "";
        $belong_has_ones  = array();
        $rela_m2m_content = "";
        $rela_js_content  = "";
        $editMulSelColumn = "";
        $editM2MSelColumn = "";

        if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) )
        {
            //从属一对一关系规范定义(如果存在)
            if ( array_key_exists("belong_has_one", $relationSpec) )
            {
                $belong_has_one       = $relationSpec["belong_has_one"];
                foreach ($belong_has_one as $key => $value) {
                    $re_realId         = DataObjectSpec::getRealIDColumnName($key);
                    $classNameField    = self::getShowFieldName( $key );
                    $relation_content  = "                    <select id=\"$re_realId\" name=\"$re_realId\" class=\"form-control\">\r\n".
                                         "                        <option value=\"-1\">请选择</option>\r\n".
                                         "                        {foreach item=$value from=\${$value}s}\r\n".
                                         "                        <option value=\"{\${$value}.$re_realId}\">{\${$value}.{$classNameField}}</option>\r\n".
                                         "                        {/foreach}\r\n".
                                         "                    </select>\r\n";
                    $rela_js_content .= "        var select_{$value} = {};\r\n".
                                        "        {if \${$instancename}.{$value}}\r\n".
                                        "        select_{$value}.id   = \"{\${$instancename}.{$value}.{$re_realId}}\";\r\n".
                                        "        select_{$value}.text = \"{\${$instancename}.{$value}.{$classNameField}}\";\r\n".
                                        "        select_{$value} = new Array(select_{$value});\r\n".
                                        "        {/if}\r\n\r\n";
                    $belong_has_ones[$re_realId]["i"] = $value;
                    $belong_has_ones[$re_realId]["c"] = $relation_content;

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
                    $rela_js_content  .= "        var select_{$instancename_rela} = new Array();\r\n".
                                         "        {if \${$instancename}.{$value}}\r\n".
                                         "        var select_{$instancename_rela} = new Array({count(\${$instancename}.{$value})});\r\n".
                                         "        {foreach \${$instancename}.{$value} as \$$instancename_rela}\r\n\r\n".
                                         "        var $instancename_rela       = {};\r\n".
                                         "        $instancename_rela.id        = \"{\$$instancename_rela.$realId_m2m}\";\r\n".
                                         "        $instancename_rela.text      = \"{\$$instancename_rela.$classNameField}\";\r\n".
                                         "        select_{$instancename_rela}[{\${$instancename_rela}@index}] = $instancename_rela;\r\n".
                                         "        {/foreach}\r\n".
                                         "        {/if}\r\n\r\n";
                    $rela_m2m_content .= "            <tr class=\"entry\">\r\n".
                                         "                <th class=\"head\">$m2m_table_comment</th>\r\n".
                                         "                <td class=\"content select\">\r\n".
                                         "                    <select id=\"$realId_m2m\" name=\"{$realId_m2m}[]\" class=\"form-control\" multiple ></select>\r\n".
                                         "                </td>\r\n".
                                         "            </tr>\r\n";
                    $editM2MSelColumn .= "        \$.edit.select2('#$realId_m2m', \"api/web/select/{$instancename_rela}.php\", select_{$instancename_rela});\r\n";
                }
            }
        }

        $edit_contents   = "";
        $idColumnName    = "id";
        $hasImgFormFlag  = "";
        $edit_js_content = "";
        $editEnumColumn  = "";
        foreach ($fieldInfo as $fieldname => $field)
        {
            $field_comment = $field["Comment"];
            if ( contain( $field_comment, "\r" ) || contain( $field_comment, "\n" ) )
            {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            if ( self::columnIsTextArea( $fieldname, $field["Type"] ) )
            {
                $text_area_fieldname[$fieldname]  = $field_comment;
            }

            $idColumnName = DataObjectSpec::getRealIDColumnName( $classname );
            if ( self::isNotColumnKeywork( $fieldname ) ){
                $isImage = self::columnIsImage( $fieldname, $field_comment );
                $isImage = self::columnIsImage( $fieldname, $field_comment );
                if ( $idColumnName == $fieldname ) {
                    $edit_contents .= "            {if \${$instancename}}<tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$fieldname}</td></tr>{/if}\r\n";
                } else if ( self::columnIsTextArea( $fieldname, $field["Type"] ) ) {
                    $edit_contents .= "            <tr class=\"entry\">\r\n".
                                      "                <th class=\"head\">$field_comment</th>\r\n".
                                      "                <td class=\"content\">\r\n".
                                      "                    <textarea id=\"$fieldname\" name=\"$fieldname\">{\${$instancename}.$fieldname}</textarea>\r\n".
                                      "                </td>\r\n".
                                      "            </tr>\r\n";
                } else if ( $isImage ) {
                    $hasImgFormFlag = " enctype=\"multipart/form-data\"";
                    $edit_contents .= "            <tr class=\"entry\">\r\n".
                                      "                <th class=\"head\">$field_comment</th>\r\n".
                                      "                <td class=\"content\">\r\n".
                                      "                    <div class=\"file-upload-container\">\r\n".
                                      "                        <input type=\"text\" id=\"{$fieldname}Txt\" readonly=\"readonly\" class=\"file-show-path\" />\r\n".
                                      "                        <span class=\"btn-file-browser\" id=\"{$fieldname}Div\">浏览 ...</span>\r\n".
                                      "                        <input type=\"file\" id=\"$fieldname\" name=\"{$fieldname}\" style=\"display:none;\" accept=\"image/*\" />\r\n".
                                      "                    </div>\r\n".
                                      "                </td>\r\n".
                                      "            </tr>\r\n";
                    $edit_js_content= "        \$.edit.fileBrowser(\"#{$fieldname}\", \"#{$fieldname}Txt\", \"#{$fieldname}Div\");\r\n";
                } else if ( in_array($fieldname, array_keys($belong_has_ones)) ) {
                    $field_comment  = str_replace( "标识", "", $field_comment );
                    $field_comment  = str_replace( "编号", "", $field_comment );
                    $re_content     = $belong_has_ones[$fieldname]["c"];
                    $edit_contents .= "            <tr class=\"entry\">\r\n".
                                      "                <th class=\"head\">$field_comment</th>\r\n".
                                      "                <td class=\"content select\">\r\n".
                                      $re_content.
                                      "                </td>\r\n".
                                      "            </tr>\r\n";
                    $editMulSelColumn .= "        \$.edit.select2('#{$fieldname}', \"\", select_" . $belong_has_ones[$fieldname]["i"] . ");\r\n";
                } else {
                    $datatype = self::comment_type( $field["Type"] );
                    if ( in_array($fieldname, Config_AutoCode::IS_NOT_EDIT_COLUMN) ) continue;
                    switch ($datatype) {
                        case "bit":
                          $edit_contents .= "            <tr class=\"entry\">\r\n".
                                            "                <th class=\"head\">$field_comment</th>\r\n".
                                            "                <td class=\"content\">\r\n".
                                            "                    <input type=\"radio\" id=\"{$fieldname}1\" name=\"{$fieldname}\" value=\"1\" {if \${$instancename}.{$fieldname}} checked {/if} /><label for=\"{$fieldname}1\" class=\"radio_label\">是</label>\r\n".
                                            "                    <input type=\"radio\" id=\"{$fieldname}0\" name=\"{$fieldname}\" value=\"0\" {if !\${$instancename}.{$fieldname}} checked {/if}/><label for=\"{$fieldname}0\" class=\"radio_label\">否</label>\r\n".
                                            "                </td>\r\n".
                                            "            </tr>\r\n";
                          break;
                        case "date":
                          $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"text\" placeholder=\"yyyy-mm-dd\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname}\"/></td></tr>\r\n";
                          break;
                        case "enum":
                          $edit_contents .= "            <tr class=\"entry\">\r\n".
                                            "                <th class=\"head\">$field_comment</th>\r\n".
                                            "                <td class=\"content select\">\r\n".
                                            "                    <select id=\"$fieldname\" name=\"$fieldname\" class=\"form-control\"></select>\r\n".
                                            "                </td>\r\n".
                                            "            </tr>\r\n";
                          $fieldname_u       = $fieldname;
                          $fieldname_u       = ucfirst($fieldname_u);
                          $editEnumColumn   .= "        \$.edit.select2('#$fieldname', \"api/web/data/" . $instancename . "$fieldname_u.json\", select_{$fieldname});\r\n";
                          break;
                        case "int":
                        case "bigint":
                          $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"number\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname|default:100}\"/></td></tr>\r\n";
                          break;
                        default:
                          $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"text\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname}\"/></td></tr>\r\n";
                          break;
                    }

                    if ( $datatype == 'enum' ) {
                        $enumJsContent .= "        var select_{$fieldname} = {};\r\n".
                                          "        {if \${$instancename}.{$fieldname}}\r\n".
                                          "        select_{$fieldname}.id   = \"{\$" . $instancename . "." . $fieldname . "}\";\r\n".
                                          "        select_{$fieldname}.text = \"{\$" . $instancename . "." . $fieldname . "Show}\";\r\n".
                                          "        select_{$fieldname} = new Array(select_{$fieldname});\r\n".
                                          "        {/if}\r\n";
                    }
                }
            }
        }
        $edit_contents .= $rela_m2m_content;

        $ueTextareacontents   = "";
        if ( count($text_area_fieldname) >= 1) {
            $ckeditor_prepare = "";
            $ueEditor_prepare = "";
            foreach ($text_area_fieldname as $key => $value) {
                // $ckeditor_prepare .= "ckeditor_replace_$key();";
                $ueEditor_prepare .= "pageInit_ue_$key();";
            }
            $textareapreparesentence = "";
//             $textareapreparesentence = <<<EDIT
//     {if (\$online_editor=='CKEditor')}
//         {\$editorHtml}
//         <script>
//         $(function(){
//             $ckeditor_prepare
//         });
//         </script>
//     {/if}
// EDIT;
            $ueTextareacontents = <<<UETC
    {if (\$online_editor == 'UEditor')}
        <script>$ueEditor_prepare</script>
    {/if}
UETC;
        }
        $edit_js_content .= $rela_js_content . $enumJsContent;
        if ( !empty($edit_js_content) ){
            $edit_js_content= "    <script type=\"text/javascript\">\r\n".
                              "    \$(function() {\r\n".
                              $edit_js_content . "\r\n".
                              $editMulSelColumn.
                              $editM2MSelColumn.
                              $editEnumColumn.
                              "    });\r\n".
                              "    </script>\r\n";
        }
        if ( !empty($edit_contents) && (strlen($edit_contents)>2) ) {
            $edit_contents = substr($edit_contents, 0, strlen($edit_contents) - 2);
        }
        include("template" . DS . "default.php");
        $result = $edit_template;
        if ( count($text_area_fieldname) >= 1 ) {
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
        $table_comment = self::tableCommentKey( $tablename );
        $appname       = self::$appName;
        $classname     = self::getClassname( $tablename );
        $instancename  = self::getInstancename( $tablename );
        $fieldNameAndComments = array();
        $enumColumns          = array();
        $bitColumns           = array();
        foreach ($fieldInfo as $fieldname => $field)
        {
            $field_comment = $field["Comment"];
            if ( contain( $field_comment, "\r" ) || contain( $field_comment, "\n" ) )
            {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            $fieldNameAndComments[$fieldname] = $field_comment;
            $datatype = self::comment_type($field["Type"]);
            switch ($datatype) {
              case 'bit':
                $bitColumns[] = $fieldname;
                break;
              case 'enum':
                $enumColumns[] = $fieldname;
                break;
              default:
                break;
            }
        }
        $view_contents = "";
        $view_contents .= "        <tr class=\"entry\"><td colspan=\"2\" class=\"v_g_t\"><h3>¶ <span>基本信息</span></h3></td></tr>\r\n";
        foreach ($fieldNameAndComments as $key => $value) {
            if ( self::isNotColumnKeywork( $key ) ) {
                $isImage = self::columnIsImage( $key, $value );
                if ( $isImage ) {
                    $view_contents .= "        <tr class=\"entry\">\r\n".
                                      "            <th class=\"head\">$value</th>\r\n".
                                      "            <td class=\"content\">\r\n".
                                      "                {if \$$instancename.$key}\r\n".
                                      "                <div class=\"wrap_2_inner\"><img src=\"{\$uploadImg_url|cat:\$$instancename.$key}\" alt=\"$value\"></div><br/>\r\n".
                                      "                存储相对路径:{\$$instancename.$key}\r\n".
                                      "                {else}\r\n".
                                      "                无上传图片\r\n".
                                      "                {/if}\r\n".
                                      "            </td>\r\n".
                                      "        </tr>\r\n";
                    continue;
                }

                //关系列的显示
                // $is_no_relation=true;
                if ( is_array(self::$relation_viewfield) && ( count(self::$relation_viewfield) > 0 ) )
                {
                    if ( array_key_exists($classname, self::$relation_viewfield) )
                    {
                        $relationSpecs = self::$relation_viewfield[$classname];
                        if ( array_key_exists($key, $relationSpecs) )
                        {
                            $relationShow = $relationSpecs[$key];
                            foreach ($relationShow as $key_r => $value_r) {
                                $realId = DataObjectSpec::getRealIDColumnName( $key_r );
                                if ( empty($realId) ) $realId = $key;
                                $show_fieldname = "";
                                if ( (!array_key_exists($value_r, $fieldInfo)) || ($classname == $key_r) ){
                                    $show_fieldname = $value_r;
                                    if ( $realId != $key ){
                                        $key_m = $key;
                                        if ( contain($key,"_id") ) {
                                            $key_m = str_replace("_id", "", $key_m);
                                        }

                                        // if ( $key_m != "parent" ) {
                                        //     $show_fieldname .= "_" . $key_m;
                                        // }
                                    }
                                    if ( $show_fieldname == "name" ) {
                                        $show_fieldname = strtolower($key_r)."_".$value_r;
                                    }
                                } else {
                                    if ( $value_r == "name" ) {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                }
                                if ( !array_key_exists($show_fieldname, $fieldInfo) ) {
                                    $field_comment = $value;
                                    $field_comment = self::columnCommentKey($field_comment,$key);

                                    $talname_rela   = self::getTablename( $key_r );
                                    $insname_rela   = self::getInstancename( $talname_rela );
                                    $classNameField = self::getShowFieldNameByClassname( $key_r, true );
                                    if ( empty($classNameField) ) $classNameField = $realId;
                                    $showColName    = $insname_rela . "." . $classNameField;
                                    $view_contents.="        <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$showColName}</td></tr>\r\n";

                                }

                                $fieldInfo_relationshow = self::$fieldInfos[self::getTablename($key_r)];
                                $key_r = lcfirst($key_r);
                                if ( array_key_exists("parent_id", $fieldInfo_relationshow) ) {
                                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">{$field_comment}[全]</th><td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td></tr>\r\n";
                                }
                            }
                        }
                    }
                }

                if( ( count($enumColumns) > 0 ) && (in_array($key, $enumColumns)) ) {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.{$key}Show}</td></tr>\r\n";
                } else if ( ( count($bitColumns) > 0 ) && ( in_array($key, $bitColumns) ) ) {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.{$key}Show}</td></tr>\r\n";
                }  else {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.$key}</td></tr>\r\n";
                }
            }
        }

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
                    $view_contents    .= "        <tr class=\"entry\">\r\n".
                                         "            <th class=\"head\">$m2m_table_comment</th>\r\n".
                                         "            <td class=\"content\">{foreach item=$instancename_rela from=\${$instancename}.{$value}}<span>{\${$instancename_rela}.{$classNameField}}</span> {/foreach}\r\n".
                                         "        </tr>\r\n";
                }
            }
        }

        $view_contents .= "        <tr class=\"entry v_g_b\"><td colspan=\"2\" class=\"v_g_t\"><h3>¶ <span>其他信息</span></h3></td></tr>\r\n";

        foreach ($fieldInfo as $fieldname => $field)
        {
            $comment = $fieldNameAndComments[$fieldname];
            $realId  = DataObjectSpec::getRealIDColumnName($classname);
            if ( !self::isNotColumnKeywork( $fieldname, $field_comment ) ) {
                $view_contents .= "        <tr class=\"entry\"><th class=\"head\">" . $comment . "</th><td class=\"content\">{\$$instancename.$fieldname|date_format:\"%Y-%m-%d %H:%M\"}</td></tr>\r\n";
            } else if ( $realId == $fieldname ) {
                $view_contents .= "        <tr class=\"entry\"><th class=\"head\">" . $comment . "</th><td class=\"content\">{\$$instancename.$fieldname}</td></tr>\r\n";
            }
        }
        if ( !empty($view_contents) && (strlen($view_contents)>2) ) {
            $view_contents = substr($view_contents, 0, strlen($view_contents) - 2);
        }
        $realId = DataObjectSpec::getRealIDColumnName( $classname );
        include("template" . DS . "default.php");
        $result = $view_template;
        $result = self::tableToViewTplDefine( $result );
        return $result;
    }
}
