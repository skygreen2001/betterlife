<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-Ajax请求数据<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeAjax extends AutoCodeView
{
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
            $field_comment = $field["Comment"];
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
        include("template" . DS . "ajax.php");
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
                    $class_relaField   = self::getShowFieldName( $classname_rela );

                    include("template" . DS . "ajax.php");
                    $phpName          = self::saveApiSelectDefineToDir( $talname_rela, $select_web_template );
                    self::$showReport.= "生成导出完成:$tablename => $phpName!<br/>";
                }
            }
        }
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

    /**
     * 保存生成的api web代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $defineApiWebFileContent 生成的代码
     */
    public static function saveApiWebDefineToDir($tablename, $defineApiWebFileContent)
    {
        $classname     = self::getClassname( $tablename );
        $instancename  = self::getInstancename( $tablename );
        $dir           = self::$save_dir . DS . "api" . DS . "web" . DS . "list" . DS;
        $filename      = $instancename . Config_F::SUFFIX_FILE_PHP;
        $relative_path = "api" . DS . "web" . DS . "list" . DS . $filename;
        AutoCodePreviewReport::$api_admin_files[$classname . $filename] = $relative_path;
        return self::saveDefineToDir( $dir, $filename, $defineApiWebFileContent );
    }

    /**
     * 保存生成的api select代码到指定命名规范的文件中
     * @param string $instantceName 对象实例名称
     * @param string $defineJsFileContent 生成的代码
     */
    public static function saveApiSelectDefineToDir($tablename, $defineApiSelectFileContent)
    {
        $classname     = self::getClassname( $tablename );
        $instancename  = self::getInstancename( $tablename );
        $dir           = self::$save_dir . DS . "api" . DS . "web" . DS . "select" . DS;
        $filename      = $instancename . Config_F::SUFFIX_FILE_PHP;
        $relative_path = "api" . DS . "web" . DS . "select" . DS . $filename;
        AutoCodePreviewReport::$api_select_files[$classname . $filename] = $relative_path;
        return self::saveDefineToDir( $dir, $filename, $defineApiSelectFileContent );
    }

    /**
     * 保存生成的Json代码到指定命名规范的文件中
     * @param string $instantceName 对象实例名称
     * @param string $fieldname field属性名称
     * @param string $defineJsFileContent 生成的代码
     */
    public static function saveJsonDefineToDir($instantceName, $fieldname, $defineJsonFileContent)
    {
        $dir           = self::$save_dir . "api" . DS . "web" . DS . "data" . DS;
        $fieldname{0}  = strtoupper($fieldname{0});
        $filename      = $instantceName . $fieldname . Config_F::SUFFIX_FILE_JSON;
        $relative_path = str_replace( self::$save_dir, "", $dir . $filename );
        AutoCodePreviewReport::$json_admin_files[$filename] = $relative_path;
        return self::saveDefineToDir( $dir, $filename, $defineJsonFileContent );
    }

}
