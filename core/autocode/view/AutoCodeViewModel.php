<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-通用模版的表示层
 +---------------------------------<br/>
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

                $is_no_relation = true;
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
                                if ( ( !array_key_exists($value_r, $fieldInfo) ) || ( $classname == $key_r ) ) {
                                    $show_fieldname = $value_r;
                                    if ( $realId != $key ) {
                                        if ( contain( $key, "_id" ) ) {
                                            $key = str_replace("_id","",$key);
                                        }
                                        $show_fieldname .= "_" . $key;
                                    }
                                    if ($show_fieldname == "name") {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                } else {
                                    if ( $value_r == "name" ) {
                                        $show_fieldname = strtolower($key_r)."_".$value_r;
                                    }
                                }
                                if ( !array_key_exists("$show_fieldname", $fieldInfo) ) {
                                    $field_comment  = $value;
                                    $field_comment  = self::columnCommentKey( $field_comment, $key );
                                    $headers       .= "            <th class=\"header\">$field_comment</th>\r\n";

                                    $talname_rela   = self::getTablename( $key_r );
                                    $insname_rela   = self::getInstancename( $talname_rela );
                                    $classNameField = self::getShowFieldNameByClassname( $key_r, true );
                                    if ( empty($classNameField) ) $classNameField = $realId;
                                    $showColName    = $insname_rela . "." . $classNameField;
                                    $contents      .= "            <td class=\"content\">{\${$instancename}.$showColName}</td>\r\n";
                                    $is_no_relation = false;
                                }

                                $fieldInfo_relationshow = self::$fieldInfos[self::getTablename( $key_r )];
                                $key_r{0} = strtolower($key_r{0});
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
        $fieldNameAndComments = array();
        $text_area_fieldname  = array();
        $enumColumns          = array();
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
            } else {
                $fieldNameAndComments[$fieldname] = $field_comment;
            }
            $datatype = self::comment_type( $field["Type"] );
            if ( $datatype == 'enum' ) {
                $enumColumns[] = $fieldname;
            }
        }
        $edit_contents  = "";
        $idColumnName   = "id";
        $hasImgFormFlag = "";
        foreach ($fieldNameAndComments as $key => $value) {
            $idColumnName = DataObjectSpec::getRealIDColumnName( $classname );
            if ( self::isNotColumnKeywork( $key ) ){
                $isImage = self::columnIsImage( $key, $value );
                if ( $idColumnName == $key ) {
                    $edit_contents .= "        {if \${$instancename}}<tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\${$instancename}.$key}</td></tr>{/if}\r\n";
                } else if ( $isImage ) {
                    $hasImgFormFlag = " enctype=\"multipart/form-data\"";
                    $edit_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"file\" class=\"edit\" name=\"{$key}Upload\" accept=\"image/png,image/gif,image/jpg,image/jpeg\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
                } else {
                    $edit_contents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"text\" class=\"edit\" name=\"$key\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
                }
            }
        }

        $ueTextareacontents   = "";
        if ( count($text_area_fieldname) >= 1) {
            $ckeditor_prepare = "    ";
            $ueEditor_prepare = "";
            foreach ($text_area_fieldname as $key => $value) {
                $edit_contents   .= "        <tr class=\"entry\"><th class=\"head\">$value</th>\r\n".
                                    "            <td class=\"content\">\r\n".
                                    "                <textarea id=\"$key\" name=\"$key\" style=\"width:90%;height:300px;\">{\${$instancename}.$key}</textarea>\r\n".
                                    "            </td>\r\n".
                                    "        </tr>\r\n";
                $ckeditor_prepare .= "ckeditor_replace_$key();";
                $ueEditor_prepare .= "pageInit_ue_$key();";
            }

            $textareapreparesentence = <<<EDIT
    {if (\$online_editor=='CKEditor')}
        {\$editorHtml}
        <script>
        $(function(){
            $ckeditor_prepare
        });
        </script>
    {/if}
EDIT;
            $ueTextareacontents = <<<UETC
    {if (\$online_editor == 'UEditor')}
        <script>$ueEditor_prepare</script>
    {/if}
UETC;
        }
        if ( !empty($edit_contents) && (strlen($edit_contents)>2) ) {
            $edit_contents = substr($edit_contents, 0, strlen($edit_contents) - 2);
        }
        include("template" . DS . "default.php");
        $result = $edit_template;
        if ( count($text_area_fieldname) >= 1 ) {
            $result = $textareapreparesentence."\r\n".$result;
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
        $view_contents="";
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
                                if ( (!array_key_exists($value_r, $fieldInfo)) || ($classname == $key_r) ){
                                    $show_fieldname = $value_r;
                                    if ( $realId != $key ){
                                        $key_m = $key;
                                        if ( contain($key,"_id") ) {
                                            $key_m = str_replace("_id", "", $key_m);
                                        }
                                        $show_fieldname .= "_".$key_m;
                                    }
                                    if ( $show_fieldname == "name" ) {
                                        $show_fieldname = strtolower($key_r)."_".$value_r;
                                    }
                                } else {
                                    if ( $value_r == "name" ) {
                                        $show_fieldname = strtolower($key_r) . "_" . $value_r;
                                    }
                                }
                                if ( !array_key_exists("$show_fieldname", $fieldInfo) ) {
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
                                $key_r{0} = strtolower($key_r{0});
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
