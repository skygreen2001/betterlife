<?php

/**
 * -----------| 工具类:自动生成代码-通用模版的表示层 |-----------
 * @category Betterlife
 * @package core.autocode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewModel extends AutoCodeView
{
    /**
     * 生成通用模版首页访问所有生成的链接
     * @param array|string $table_names
     * 示例如下:
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function createModelIndexFile($table_names = "")
    {
        $tableInfos  = self::tableInfosByTable_names($table_names);
        $tpl_content = "    <div><h1>这是首页列表(共计数据对象" . count($tableInfos) . "个)</h1></div>" . HH;
        $result      = "";
        $appname     = self::$appName;
        if ($tableInfos != null && count($tableInfos) > 0) {
            foreach ($tableInfos as $tablename => $tableInfo) {
                $table_comment = $tableInfos[$tablename]["Comment"];
                if (contain($table_comment, "\r") || contain($table_comment, "\n")) {
                    $table_comment = preg_split("/[\s,]+/", $table_comment);
                    $table_comment = $table_comment[0];
                }
                $instancename = self::getInstancename($tablename);
                if (empty($table_comment)) {
                    $table_comment = $tablename;
                }
                $result .= "        <tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>" . HH;
            }
        }
        $tpl_content .= "    <table class=\"viewdoblock\" style=\"width: 500px;\">" . HH .
                        $result .
                        "    </table>";
        $tpl_content  = self::tableToViewTplDefine($tpl_content);
        $filename     = "index" . ConfigF::SUFFIX_FILE_TPL;
        $dir          = self::$view_dir_full . "index" . DS;
        return self::saveDefineToDir($dir, $filename, $tpl_content);
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_lists($tablename, $fieldInfo)
    {
        $table_comment = self::tableCommentKey($tablename);
        $appname       = self::$appName;
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $fieldNameAndComments = array();
        $enumColumns          = array();
        $bitColumns           = array();
        foreach ($fieldInfo as $fieldname => $field) {
            $field_comment = $field["Comment"];
            if (contain($field_comment, "\r") || contain($field_comment, "\n")) {
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
            if (self::isNotColumnKeywork($key)) {
                $isImage = self::columnIsImage($key, $value);
                if ($isImage) {
                    continue;
                }

                // $is_no_relation = true;
                //关系列的显示
                if (is_array(self::$relation_viewfield) && (count(self::$relation_viewfield) > 0)) {
                    if (array_key_exists($classname, self::$relation_viewfield)) {
                        $relationSpecs = self::$relation_viewfield[$classname];
                        if (array_key_exists($key, $relationSpecs)) {
                            $relationShow = $relationSpecs[$key];
                            foreach ($relationShow as $key_r => $value_r) {
                                $realId = DataObjectSpec::getRealIDColumnName($key_r);
                                if (empty($realId)) {
                                    $realId = $key;
                                }
                                $show_fieldname = "";
                                if ((!array_key_exists($value_r, $fieldInfo)) || ($classname == $key_r)) {
                                    $show_fieldname = $value_r;
                                    if ($realId != $key) {
                                        if (contain($key, "_id")) {
                                            $key = str_replace("_id", "", $key);
                                        }
                                        // if ($key != "parent") {
                                        //     $show_fieldname .= "_" . $key;
                                        // }
                                    }
                                    if ($show_fieldname == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                } else {
                                    if ($value_r == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                }

                                if (!array_key_exists($show_fieldname, $fieldInfo)) {
                                    $field_comment  = $value;
                                    $field_comment  = self::columnCommentKey($field_comment, $key);
                                    $headers       .= "            <th class=\"header\">$field_comment</th>" . HH;

                                    $talname_rela   = self::getTablename($key_r);
                                    $insname_rela   = self::getInstancename($talname_rela);
                                    $classNameField = self::getShowFieldNameByClassname($key_r, true);
                                    if (empty($classNameField)) {
                                        $classNameField = $realId;
                                    }
                                    $showColName    = $insname_rela . "." . $classNameField;
                                    $contents      .= "            <td class=\"content\">{\${$instancename}.$showColName}</td>" . HH;
                                    // $is_no_relation = false;
                                }

                                $fieldInfo_relationshow = self::$fieldInfos[self::getTablename($key_r)];
                                $key_r = lcfirst($key_r);
                                if (array_key_exists("parent_id", $fieldInfo_relationshow)) {
                                    $headers  .= "            <th class=\"header\">{$field_comment}[全]</th>" . HH;
                                    $contents .= "            <td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td>" . HH;
                                }
                            }
                        }
                    }
                }
                // if ($is_no_relation) {
                $headers      .= "            <th class=\"header\">$value</th>" . HH;

                if ((count($enumColumns) > 0) && (in_array($key, $enumColumns))) {
                    $contents .= "            <td class=\"content\">{\${$instancename}.{$key}Show}</td>" . HH;
                } elseif ((count($bitColumns) > 0) && (in_array($key, $bitColumns))) {
                    $contents .= "            <td class=\"content\">{\${$instancename}.{$key}Show}</td>" . HH;
                } else {
                    $contents .= "            <td class=\"content\">{\${$instancename}.$key}</td>" . HH;
                }
                // }
            }
        }

        if (!empty($headers) && (strlen($headers) > strlen(HH))) {
            $headers  = substr($headers, 0, strlen($headers) - strlen(HH));
            $contents = substr($contents, 0, strlen($contents) - strlen(HH));
        }
        $realId = DataObjectSpec::getRealIDColumnName($classname);
        include("template" . DS . "default.php");
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
        $table_comment = self::tableCommentKey($tablename);
        $appname       = self::$appName;
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $text_area_fieldname  = array();

        $enumJsContent    = "";
        $belong_has_ones  = array();
        $rela_m2m_content = "";
        $rela_js_content  = "";
        $editMulSelColumn = "";
        $editM2MSelColumn = "";

        if (array_key_exists($classname, self::$relation_all)) {
            $relationSpec = self::$relation_all[ $classname ];
        }
        if (isset($relationSpec) && is_array($relationSpec) && (count($relationSpec) > 0)) {
            //从属一对一关系规范定义(如果存在)
            if (array_key_exists("belong_has_one", $relationSpec)) {
                $belong_has_one       = $relationSpec["belong_has_one"];
                foreach ($belong_has_one as $key => $value) {
                    $re_realId         = DataObjectSpec::getRealIDColumnName($key);
                    $classNameField    = self::getShowFieldName($key);
                    $relation_content  = "                    <select id=\"$re_realId\" name=\"$re_realId\" class=\"form-control\">" . HH .
                                         "                        <option value=\"-1\">请选择</option>" . HH .
                                         "                        {foreach item=$value from=\${$value}s}" . HH .
                                         "                        <option value=\"{\${$value}.$re_realId}\">{\${$value}.{$classNameField}}</option>" . HH .
                                         "                        {/foreach}" . HH .
                                         "                    </select>" . HH;
                    $rela_js_content .= "        var select_{$value} = {};" . HH .
                                        "        {if \${$instancename} && \${$instancename}.{$value}}" . HH .
                                        "        select_{$value}.id   = \"{\${$instancename}.{$value}.{$re_realId}}\";" . HH .
                                        "        select_{$value}.text = \"{\${$instancename}.{$value}.{$classNameField}}\";" . HH .
                                        "        select_{$value} = new Array(select_{$value});" . HH .
                                        "        {/if}" . HH . HH;
                    $belong_has_ones[$re_realId]["i"] = $value;
                    $belong_has_ones[$re_realId]["c"] = $relation_content;
                }
            }

            //多对多关系规范定义(如果存在)
            if (array_key_exists("many_many", $relationSpec)) {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename($key);
                    $instancename_rela = self::getInstancename($talname_rela);
                    $m2m_table_comment = self::tableCommentKey($talname_rela);
                    $classNameField    = self::getShowFieldName($key);
                    $rela_js_content  .= "        var select_{$instancename_rela} = new Array();" . HH .
                                         "        {if \${$instancename} && \${$instancename}.{$value}}" . HH .
                                         "        var select_{$instancename_rela} = new Array({count(\${$instancename}.{$value})});" . HH .
                                         "        {foreach \${$instancename}.{$value} as \$$instancename_rela}" . HH . HH .
                                         "        var $instancename_rela       = {};" . HH .
                                         "        $instancename_rela.id        = \"{\$$instancename_rela.$realId_m2m}\";" . HH .
                                         "        $instancename_rela.text      = \"{\$$instancename_rela.$classNameField}\";" . HH .
                                         "        select_{$instancename_rela}[{\${$instancename_rela}@index}] = $instancename_rela;" . HH .
                                         "        {/foreach}" . HH .
                                         "        {/if}" . HH . HH;
                    $rela_m2m_content .= "            <tr class=\"entry\">" . HH .
                                         "                <th class=\"head\">$m2m_table_comment</th>" . HH .
                                         "                <td class=\"content select\">" . HH .
                                         "                    <select id=\"$realId_m2m\" name=\"{$realId_m2m}[]\" class=\"form-control\" multiple ></select>" . HH .
                                         "                </td>" . HH .
                                         "            </tr>" . HH;
                    $editM2MSelColumn .= "        \$.edit.select2('#$realId_m2m', \"api/web/select/{$instancename_rela}.php\", select_{$instancename_rela});" . HH;
                }
            }
        }

        $edit_contents   = "";
        $idColumnName    = "id";
        $hasImgFormFlag  = "";
        $edit_js_content = "";
        $editEnumColumn  = "";
        foreach ($fieldInfo as $fieldname => $field) {
            $field_comment = $field["Comment"];
            if (contain($field_comment, "\r") || contain($field_comment, "\n")) {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            if (self::columnIsTextArea($fieldname, $field["Type"])) {
                $text_area_fieldname[$fieldname]  = $field_comment;
            }

            $idColumnName = DataObjectSpec::getRealIDColumnName($classname);
            if (self::isNotColumnKeywork($fieldname)) {
                $isImage = self::columnIsImage($fieldname, $field_comment);
                // $isImage = self::columnIsImage( $fieldname, $field_comment );
                if ($idColumnName == $fieldname) {
                    $edit_contents .= "            {if \${$instancename}}<tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$fieldname}</td></tr>{/if}" . HH;
                } elseif (self::columnIsTextArea($fieldname, $field["Type"])) {
                    $edit_contents .= "            <tr class=\"entry\">" . HH .
                                      "                <th class=\"head\">$field_comment</th>" . HH .
                                      "                <td class=\"content\">" . HH .
                                      "                    <textarea id=\"$fieldname\" name=\"$fieldname\" rows=\"6\" cols=\"60\" placeholder=\"" . $field_comment . "\">{\${$instancename}.$fieldname|default:''}</textarea>" . HH .
                                      "                </td>" . HH .
                                      "            </tr>" . HH;
                } elseif ($isImage) {
                    $hasImgFormFlag = " enctype=\"multipart/form-data\"";
                    $edit_contents .= "            <tr class=\"entry\">" . HH .
                                      "                <th class=\"head\">$field_comment</th>" . HH .
                                      "                <td class=\"content\">" . HH .
                                      "                    <div class=\"file-upload-container\">" . HH .
                                      "                        <input type=\"text\" id=\"{$fieldname}Txt\" readonly=\"readonly\" class=\"file-show-path\" />" . HH .
                                      "                        <span class=\"btn-file-browser\" id=\"{$fieldname}Div\">浏览 ...</span>" . HH .
                                      "                        <input type=\"file\" id=\"$fieldname\" name=\"{$fieldname}\" style=\"display:none;\" accept=\"image/*\" />" . HH .
                                      "                    </div>" . HH .
                                      "                </td>" . HH .
                                      "            </tr>" . HH;
                    $edit_js_content = "        \$.edit.fileBrowser(\"#{$fieldname}\", \"#{$fieldname}Txt\", \"#{$fieldname}Div\");" . HH;
                } elseif (in_array($fieldname, array_keys($belong_has_ones))) {
                    $field_comment  = str_replace("标识", "", $field_comment);
                    $field_comment  = str_replace("编号", "", $field_comment);
                    $re_content     = $belong_has_ones[$fieldname]["c"];
                    $edit_contents .= "            <tr class=\"entry\">" . HH .
                                      "                <th class=\"head\">$field_comment</th>" . HH .
                                      "                <td class=\"content select\">" . HH .
                                      $re_content .
                                      "                </td>" . HH .
                                      "            </tr>" . HH;
                    $editMulSelColumn .= "        \$.edit.select2('#{$fieldname}', \"\", select_" . $belong_has_ones[$fieldname]["i"] . ");" . HH;
                } else {
                    $datatype = self::comment_type($field["Type"]);
                    if (in_array($fieldname, ConfigAutoCode::IS_NOT_EDIT_COLUMN)) {
                        continue;
                    }
                    switch ($datatype) {
                        case "bit":
                            $edit_contents .= "            <tr class=\"entry\">" . HH .
                                            "                <th class=\"head\">$field_comment</th>" . HH .
                                            "                <td class=\"content\">" . HH .
                                            "                    <input type=\"radio\" id=\"{$fieldname}1\" name=\"{$fieldname}\" value=\"1\" {if \${$instancename} && \${$instancename}.{$fieldname}} checked {/if} /><label for=\"{$fieldname}1\" class=\"radio_label\">是</label>" . HH .
                                            "                    <input type=\"radio\" id=\"{$fieldname}0\" name=\"{$fieldname}\" value=\"0\" {if \${$instancename} && !\${$instancename}.{$fieldname}} checked {/if}/><label for=\"{$fieldname}0\" class=\"radio_label\">否</label>" . HH .
                                            "                </td>" . HH .
                                            "            </tr>" . HH;
                            break;
                        case "date":
                            $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"text\" placeholder=\"yyyy-mm-dd\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname|default:''}\"/></td></tr>" . HH;
                            break;
                        case "enum":
                            $edit_contents .= "            <tr class=\"entry\">" . HH .
                                            "                <th class=\"head\">$field_comment</th>" . HH .
                                            "                <td class=\"content select\">" . HH .
                                            "                    <select id=\"$fieldname\" name=\"$fieldname\" class=\"form-control\"></select>" . HH .
                                            "                </td>" . HH .
                                            "            </tr>" . HH;
                            $fieldname_u       = $fieldname;
                            $fieldname_u       = ucfirst($fieldname_u);
                            $editEnumColumn   .= "        \$.edit.select2('#$fieldname', \"api/web/data/" . $instancename . "$fieldname_u.json\", select_{$fieldname});" . HH;
                            break;
                        case "int":
                        case "bigint":
                            $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"number\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname|default:100}\"/></td></tr>" . HH;
                            break;
                        default:
                            $edit_contents .= "            <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\"><input type=\"text\" class=\"edit\" name=\"$fieldname\" value=\"{\${$instancename}.$fieldname|default:''}\"/></td></tr>" . HH;
                            break;
                    }
                    if (contain($datatype, 'enum')) {
                        $enumJsContent .= "        var select_{$fieldname} = {};" . HH .
                                          "        {if \${$instancename} && \${$instancename}.{$fieldname}}" . HH .
                                          "        select_{$fieldname}.id   = \"{\$" . $instancename . "." . $fieldname . "}\";" . HH .
                                          "        select_{$fieldname}.text = \"{\$" . $instancename . "." . $fieldname . "Show}\";" . HH .
                                          "        select_{$fieldname} = new Array(select_{$fieldname});" . HH .
                                          "        {/if}" . HH;
                    }
                }
            }
        }
        $edit_contents .= $rela_m2m_content;

        $ueTextareacontents   = "";
        if (count($text_area_fieldname) >= 1) {
            $ckeditor_prepare = "";
            $ueEditor_prepare = "";
            foreach ($text_area_fieldname as $key => $value) {
                // $ckeditor_prepare .= "ckeditor_replace_$key();";
                $ueEditor_prepare .= "pageInit_ue_$key();" . HH . HH .
                                     "                // 在线编辑器设置默认样式" . HH .
                                     "                ue_{$key}.ready(function() {" . HH .
                                     "                    UE.dom.domUtils.setStyles(ue_{$key}.body, {" . HH .
                                     "                        'background-color': '#4caf50','color': '#fff','font-family' : \"'Microsoft Yahei','Helvetica Neue', Helvetica, STHeiTi, Arial, sans-serif\", 'font-size' : '16px'" . HH .
                                     "                    });" . HH .
                                     "                });" . HH;
            }
            $textareapreparesentence = "";
//             $textareapreparesentence = <<<EDIT
//     {if (\$online_editor=='CKEditor')}
//         {\$editorHtml}
//         <script>
//         $(function() {
//             $ckeditor_prepare
//         });
//         </script>
//     {/if}
            // EDIT;
            $ueTextareacontents = <<<UETC
    {if (\$online_editor == 'UEditor')}
        <script>
        $(function() {
            if (typeof UE != 'undefined') {
                $ueEditor_prepare
            }
        });
        </script>
    {/if}
UETC;
        }
        $edit_js_content .= $rela_js_content . $enumJsContent;
        if (!empty($edit_js_content)) {
            $edit_js_content = "    <script type=\"text/javascript\">" . HH .
                              "    \$(function() {" . HH .
                              $edit_js_content . HH .
                              $editMulSelColumn .
                              $editM2MSelColumn .
                              $editEnumColumn .
                              "    });" . HH .
                              "    </script>" . HH;
        }
        if (!empty($edit_contents) && (strlen($edit_contents) > 2)) {
            $edit_contents = substr($edit_contents, 0, strlen($edit_contents) - 2);
        }
        include("template" . DS . "default.php");
        $result = $edit_template;
        if (count($text_area_fieldname) >= 1) {
            $result = $textareapreparesentence . HH . $result;
        }
        $result = self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tpl_view($tablename, $fieldInfo)
    {
        $table_comment = self::tableCommentKey($tablename);
        $appname       = self::$appName;
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);
        $fieldNameAndComments = array();
        $enumColumns          = array();
        $bitColumns           = array();
        foreach ($fieldInfo as $fieldname => $field) {
            $field_comment = $field["Comment"];
            if (contain($field_comment, "\r") || contain($field_comment, "\n")) {
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
        $view_contents .= "        <tr class=\"entry\"><td colspan=\"2\" class=\"v_g_t\"><h3>¶ <span>基本信息</span></h3></td></tr>" . HH;
        foreach ($fieldNameAndComments as $key => $value) {
            if (self::isNotColumnKeywork($key)) {
                $isImage = self::columnIsImage($key, $value);
                if ($isImage) {
                    $view_contents .= "        <tr class=\"entry\">" . HH .
                                      "            <th class=\"head\">$value</th>" . HH .
                                      "            <td class=\"content\">" . HH .
                                      "                {if \$$instancename.$key}" . HH .
                                      "                <div class=\"wrap_2_inner\"><img src=\"{\$uploadImg_url|cat:\$$instancename.$key}\" alt=\"$value\"></div><br/>" . HH .
                                      "                存储相对路径:{\$$instancename.$key}" . HH .
                                      "                {else}" . HH .
                                      "                无上传图片" . HH .
                                      "                {/if}" . HH .
                                      "            </td>" . HH .
                                      "        </tr>" . HH;
                    continue;
                }

                //关系列的显示
                // $is_no_relation=true;
                if (is_array(self::$relation_viewfield) && (count(self::$relation_viewfield) > 0)) {
                    if (array_key_exists($classname, self::$relation_viewfield)) {
                        $relationSpecs = self::$relation_viewfield[$classname];
                        if (array_key_exists($key, $relationSpecs)) {
                            $relationShow = $relationSpecs[$key];
                            foreach ($relationShow as $key_r => $value_r) {
                                $realId = DataObjectSpec::getRealIDColumnName($key_r);
                                if (empty($realId)) {
                                    $realId = $key;
                                }
                                $show_fieldname = "";
                                if ((!array_key_exists($value_r, $fieldInfo)) || ($classname == $key_r)) {
                                    $show_fieldname = $value_r;
                                    if ($realId != $key) {
                                        $key_m = $key;
                                        if (contain($key, "_id")) {
                                            $key_m = str_replace("_id", "", $key_m);
                                        }

                                        // if ($key_m != "parent") {
                                        //     $show_fieldname .= "_" . $key_m;
                                        // }
                                    }
                                    if ($show_fieldname == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                } else {
                                    if ($value_r == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                }
                                if (!array_key_exists($show_fieldname, $fieldInfo)) {
                                    $field_comment = $value;
                                    $field_comment = self::columnCommentKey($field_comment, $key);

                                    $talname_rela   = self::getTablename($key_r);
                                    $insname_rela   = self::getInstancename($talname_rela);
                                    $classNameField = self::getShowFieldNameByClassname($key_r, true);
                                    if (empty($classNameField)) {
                                        $classNameField = $realId;
                                    }
                                    $showColName    = $insname_rela . "." . $classNameField;
                                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$showColName}</td></tr>" . HH;
                                }

                                $fieldInfo_relationshow = self::$fieldInfos[self::getTablename($key_r)];
                                $key_r = lcfirst($key_r);
                                if (array_key_exists("parent_id", $fieldInfo_relationshow)) {
                                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">{$field_comment}[全]</th><td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td></tr>" . HH;
                                }
                            }
                        }
                    }
                }

                if ((count($enumColumns) > 0) && (in_array($key, $enumColumns))) {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.{$key}Show}</td></tr>" . HH;
                } elseif ((count($bitColumns) > 0) && (in_array($key, $bitColumns))) {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.{$key}Show}</td></tr>" . HH;
                } else {
                    $view_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.$key}</td></tr>" . HH;
                }
            }
        }

        $classNameField = self::getShowFieldName($classname);
        if (array_key_exists($classname, self::$relation_all)) {
            $relationSpec = self::$relation_all[$classname];
        }
        if (isset($relationSpec) && is_array($relationSpec) && (count($relationSpec) > 0)) {
            //多对多关系规范定义(如果存在)
            if (array_key_exists("many_many", $relationSpec)) {
                $many_many             = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $talname_rela      = self::getTablename($key);
                    $instancename_rela = self::getInstancename($talname_rela);
                    $m2m_table_comment = self::tableCommentKey($talname_rela);
                    $classNameField    = self::getShowFieldName($key);
                    $view_contents    .= "        <tr class=\"entry\">" . HH .
                                         "            <th class=\"head\">$m2m_table_comment</th>" . HH .
                                         "            <td class=\"content\">{foreach item=$instancename_rela from=\${$instancename}.{$value}}<span>{\${$instancename_rela}.{$classNameField}}</span> {/foreach}" . HH .
                                         "        </tr>" . HH;
                }
            }
        }

        $view_contents .= "        <tr class=\"entry v_g_b\"><td colspan=\"2\" class=\"v_g_t\"><h3>¶ <span>其他信息</span></h3></td></tr>" . HH;

        foreach ($fieldInfo as $fieldname => $field) {
            $comment = $fieldNameAndComments[$fieldname];
            $realId  = DataObjectSpec::getRealIDColumnName($classname);
            if (!self::isNotColumnKeywork($fieldname, $field_comment)) {
                $view_contents .= "        <tr class=\"entry\"><th class=\"head\">" . $comment . "</th><td class=\"content\">{\$$instancename.$fieldname|date_format:\"%Y-%m-%d %H:%M\"}</td></tr>" . HH;
            } elseif ($realId == $fieldname) {
                $view_contents .= "        <tr class=\"entry\"><th class=\"head\">" . $comment . "</th><td class=\"content\">{\$$instancename.$fieldname}</td></tr>" . HH;
            }
        }
        if (!empty($view_contents) && (strlen($view_contents) > strlen(HH))) {
            $view_contents = substr($view_contents, 0, strlen($view_contents) - strlen(HH));
        }
        $realId = DataObjectSpec::getRealIDColumnName($classname);
        include("template" . DS . "default.php");
        $result = $view_template;
        $result = self::tableToViewTplDefine($result);
        return $result;
    }
}
