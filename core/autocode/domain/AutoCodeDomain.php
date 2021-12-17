<?php
/**
 * -----------| 工具类:自动生成代码-实体类 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeDomain extends AutoCode
{
    /**
     * 实体数据对象类完整的保存路径
     */
    public static $domain_dir_full;
    /**
     *实体数据对象类文件所在的路径
     */
    public static $enum_dir = "enum";
    /**
     * 生成枚举类型类
     */
    public static $enumClass;
    /**
     * 数据对象生成定义的方式
     * 
     *     1. 所有的列定义的对象属性都是private,同时定义setter和getter方法。
     * 
     *     2. 所有的列定义的对象属性都是public。
     */
    public static $type;

    private static $spec_comment = "";
    private static $spec_content = "";
    private static $relation_spec_comment = "";
    private static $relation_spec_content = "";
    /**
     * 自动生成代码-实体类
     * 
     * 示例如下:
     * 
     *     1. array:array('bb_user_admin','bb_core_blog')
     *     2. 字符串:'bb_user_admin,bb_core_blog'
     * @param array|string $table_names
     */
    public static function AutoCode($table_names = "")
    {
        self::$app_dir         = Gc::$appName;
        self::$domain_dir_full = self::$save_dir . Gc::$module_root . DS . self::$app_dir . DS.self::$dir_src . DS . self::$domain_dir . DS;
        
        self::init();
        if (self::$isOutputCss) self::$showReport .= UtilCss::form_css() . "\r\n";
        self::$enumClass       = "";
        self::$showReport     .= '<div id="Content_11" style="display:none;">';
        $link_domain_dir_href  ="file:///".str_replace("\\", "/", self::$domain_dir_full);
        self::$showReport     .= "<font color='#AAA'>存储路径:<a target='_blank' href='" . $link_domain_dir_href . "'>" . self::$domain_dir_full . "</a></font><br/><br/>";

        $fieldInfos=self::fieldInfosByTable_names( $table_names );
        foreach ($fieldInfos as $tablename => $fieldInfo){
           //print_r($fieldInfo);
           //self::$showReport.="<br/>";
           $definePhpFileContent=self::tableToDataObjectDefine($tablename,$fieldInfo);
           if ( isset(self::$save_dir) && !empty(self::$save_dir) && isset($definePhpFileContent) ) {
               $classname         = self::saveDataObjectDefineToDir( $tablename, $definePhpFileContent );
               self::$showReport .= "生成导出完成:$tablename => $classname!<br/>";
           }else{
               self::$showReport .= $definePhpFileContent . "<br/>";
           }
           self::tableToEnumClass( $tablename, $fieldInfo );
        }
        self::$showReport .= "</div><br/>";
        self::$showReport .= AutoCodeFoldHelper::foldEffectCommon("Content_12");
        self::$showReport .= '<font color="#237319">生成枚举类型↓</font><br/>';
        self::$showReport .= '</a>';
        self::$showReport .= '<div id="Content_12" style="display:none;">';
        self::$showReport .= "<font color='#AAA'>存储路径:<a target='_blank' href='" . $link_domain_dir_href . self::$enum_dir . "'>" . self::$domain_dir_full . self::$enum_dir."</a></font><br/><br/>";
        self::$showReport .= self::$enumClass;
        self::$showReport .= "</div>";
        
        // 获取类主键的时候，需要获取类，第一次新建的时候，目标路径下还没有该类定义
        load_module("model", self::$domain_dir_full);
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value="", $title="", $inputArr=null, $more_content="")
    {
        $url_base = Gc::$url_base;
        if ( contain( strtolower(php_uname()), "darwin") ) {
            $url_base     = UtilNet::urlbase();
            $file_sub_dir = str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])) . DS;
            if ( contain( $file_sub_dir, "tools" . DS ) )
                $file_sub_dir = substr($file_sub_dir, 0, strpos($file_sub_dir,"tools".DS));
            $domainSubDir = str_replace($_SERVER["DOCUMENT_ROOT"] . "/", "", $file_sub_dir);
            if ( !endwith( $url_base, $domainSubDir ) ) $url_base .= $domainSubDir;
        }
        $db_domian_java = $url_base . "tools/tools/autocode/layer/domain/db_domain_java.php";
        $inputArr = array(
              "2" => "所有的列定义的对象属性都是public",
              "1" => "对象属性都是private,定义setter和getter方法",
        );
        $more_content = "<br/><br/><a class='more_java' href='$db_domian_java' target='_blank'>生成Java实体类</a>";
        parent::UserInput( "一键生成数据对象定义实体类层", $inputArr, $default_value, $more_content );
    }

    /**
     * 将表枚举列生成枚举类型类
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToEnumClass($tablename, $fieldInfo)
    {
        $category  = Gc::$appName;
        $author    = self::$author;
        foreach ($fieldInfo as $fieldname => $field){
            $datatype = self::comment_type($field["Type"]);
            if ($datatype == 'enum'){
                $enumclassname     = self::enumClassName( $fieldname, $tablename );
                $enum_columnDefine = self::enumDefines( $field["Comment"] );
                if ( isset($enum_columnDefine) && ( count($enum_columnDefine) > 0 ) )
                {
                    $comment = $field["Comment"];
                    if ( contains( $comment, array("\r", "\n") ) ) {
                        $comment = preg_split("/[\s,]+/", $comment);
                        $comment = $comment[0];
                    }
                    $result = "<?php\r\n".
                              "/**\r\n".
                              " * -----------| 枚举类型:$comment |-----------\r\n".
                              " * @category $category\r\n".
                              " * @package domain\r\n".
                              " * @subpackage enum\r\n".
                              " * @author $author\r\n".
                              " */\r\n".
                              "class $enumclassname extends Enum\r\n".
                              "{\r\n";
                    foreach ($enum_columnDefine as $enum_column) {
                        $enumname    = strtoupper($enum_column['name']) ;
                        $enumvalue   = $enum_column['value'];
                        $enumcomment = $enum_column['comment'];
                        $result     .= "    /**\r\n".
                                       "     * $comment:$enumcomment\r\n".
                                       "     */\r\n".
                                       "    const $enumname='$enumvalue';\r\n";
                    }
                    $result  .="\r\n";
                    $comment  =str_replace("\r\n", "     * ", $field["Comment"]);
                    $comment  =str_replace("\r", "     * ", $comment);
                    $comment  =str_replace("\n", "     * ", $comment);
                    $comment  =str_replace("     * ", "<br/>\r\n     * ", $comment);
                    $result  .="    /**\r\n".
                               "     * 显示".$comment."<br/>\r\n".
                               "     */\r\n".
                               "    public static function {$fieldname}Show(\${$fieldname})\r\n".
                               "    {\r\n".
                               "        switch(\${$fieldname}){\r\n";
                    foreach ($enum_columnDefine as $enum_column) {
                        $enumname    = strtoupper($enum_column['name']) ;
                        $enumcomment = $enum_column['comment'];
                        $result     .= "            case self::{$enumname}:\r\n".
                                       "                return \"{$enumcomment}\";\r\n";
                    }
                    $result .= "        }\r\n";
                    $result .= "        return \"未知\";\r\n".
                               "    }\r\n\r\n";
                    $comment = explode("<br/>",$comment);
                    if ( count($comment) > 0 ) {
                        $comment = $comment[0];
                    }
                    $result .= "    /**\r\n".
                               "     * 根据{$comment}显示文字获取{$comment}<br/>\r\n".
                               "     * @param mixed \${$fieldname}Show {$comment}显示文字\r\n".
                               "     */\r\n".
                               "    public static function {$fieldname}ByShow(\${$fieldname}Show)\r\n".
                               "    {\r\n".
                               "        switch(\${$fieldname}Show){\r\n";
                    foreach ($enum_columnDefine as $enum_column) {
                        $enumname    = strtoupper($enum_column['name']);
                        $enumcomment = $enum_column['comment'];
                        $result     .= "            case \"{$enumcomment}\":\r\n".
                                       "                return self::{$enumname};\r\n";
                    }
                    $result .= "        }\r\n";
                    if ( !empty($enum_columnDefine) && ( count($enum_columnDefine) > 0 ) ) {
                        $enumname  = strtoupper($enum_columnDefine[0]['name']);
                        $result   .= "        return self::{$enumname};\r\n";
                    } else {
                        $result   .= "        return null;\r\n";
                    }
                    $result .= "    }\r\n\r\n";
                    $result .= "    /**\r\n".
                               "     * 通过枚举值获取枚举键定义<br/>\r\n".
                               "     */\r\n".
                               "    public static function {$fieldname}EnumKey(\${$fieldname})\r\n".
                               "    {\r\n".
                               "        switch(\${$fieldname}){\r\n";
                    foreach ($enum_columnDefine as $enum_column) {
                        $enumname  = strtoupper($enum_column['name']);
                        $enumvalue = $enum_column['value'];
                        $result   .= "            case '{$enumvalue}':\r\n".
                                     "                return \"{$enumname}\";\r\n";
                    }
                    $result .= "        }\r\n";
                    if ( !empty($enum_columnDefine) && (count($enum_columnDefine)>0) ) {
                        $enumname = strtoupper($enum_columnDefine[0]['name']);
                        $result  .= "        return \"{$enumname}\";\r\n";
                    } else {
                        $result  .= "        return null;\r\n";
                    }
                    $result .= "    }\r\n\r\n";
                    $result .= "}\r\n\r\n";
                    self::$enumClass .= "生成导出完成:" . $tablename . "[" . $fieldname . "] => " . self::saveEnumDefineToDir( $enumclassname, $result ) . "!<br/>";
                }
            }
        }
    }

    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToDataObjectDefine($tablename, $fieldInfo)
    {
        $result = "<?php\r\n";
        if ( (self::$tableInfoList != null) && ( count(self::$tableInfoList) > 0) && ( array_key_exists("$tablename", self::$tableInfoList ) ) ) {
            $table_comment = self::$tableInfoList[$tablename]["Comment"];
            $table_comment = str_replace("关系表","",$table_comment);
            if ( contain( $table_comment, "\r" ) || contain( $table_comment, "\n" ) ) {
                $table_comment_arr = preg_split("/[\s,]+/", $table_comment);
                $table_comment     = "";
                foreach ($table_comment_arr as $tcomment) {
                    $table_comment .= " * $tcomment<br/>\r\n";
                }
            }else{
                $table_comment = " * " . $table_comment . "<br/>\r\n";
            }
        } else {
            $table_comment = "关于 $tablename 的描述";
        }
        $category  = Gc::$appName;
        $author    = self::$author;
        $package   = self::getPackage($tablename);
        $classname = self::getClassname($tablename);
        $result   .= "/**\r\n".
                     " * -----------| $table_comment |-----------\r\n".
                     " * @category $category\r\n".
                     " * @package $package\r\n".
                     " * @author $author\r\n".
                     " */\r\n";
        $result   .= "class $classname extends DataObject\r\n{\r\n";
        $datatype  = "string";
        switch (self::$type) {
            case 2:
                $result .= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
                foreach ($fieldInfo as $fieldname => $field) {
                    if ( self::isNotColumnKeywork( $fieldname ) ) {
                        $datatype = self::comment_type($field["Type"]);
                        $comment  = str_replace("\r\n", "     * ", $field["Comment"]);
                        $comment  = str_replace("\r", "     * ", $comment);
                        $comment  = str_replace("\n", "     * ", $comment);
                        $comment  = str_replace("     * ", "<br/>\r\n     * ", $comment);
                        $result  .=
                                    "    /**\r\n".
                                    "     * ".$comment."\r\n".
                                    "     * @var $datatype\r\n".
                                    "     * @access public\r\n".
                                    "     */\r\n".
                                    "    public \$".$fieldname.";\r\n";
                    }
                };
                $result .= "    //</editor-fold>\r\n";
                break;
            default:
                $result .= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
                foreach ($fieldInfo as $fieldname => $field) {
                  if ( self::isNotColumnKeywork( $fieldname ) ) {
                       $datatype = self::comment_type( $field["Type"] );
                       $comment  = str_replace("\r\n", "     * ", $field["Comment"]);
                       $comment  = str_replace("\r", "     * ", $comment);
                       $comment  = str_replace("\n", "     * ", $comment);
                       $comment  = str_replace("     * ", "\r\n     * ", $comment);
                       $result  .=
                                "    /**\r\n".
                                "     * ".$comment."\r\n".
                                "     * @var $datatype\r\n".
                                "     * @access private\r\n".
                                "     */\r\n".
                                "    private \$".$fieldname.";\r\n";
                    };
                }
                $result .= "    //</editor-fold>\r\n\r\n";
                $result .= '    //<editor-fold defaultstate="collapsed" desc="setter和getter">'."\r\n";
                foreach ($fieldInfo as $fieldname => $field) {
                    if ( self::isNotColumnKeywork( $fieldname ) ) {
                        $result .=
                            "    public function set".ucfirst($fieldname)."(\$".$fieldname.")\r\n".
                            "    {\r\n".
                            "        \$this->".$fieldname."=\$".$fieldname.";\r\n".
                            "    }\r\n";
                        $result .=
                            "    public function get".ucfirst($fieldname)."()\r\n".
                            "    {\r\n".
                            "        return \$this->".$fieldname.";\r\n".
                            "    }\r\n";
                    };
                }
                $result .= "    //</editor-fold>\r\n";
                break;
        }

        self::$spec_comment          = "";
        self::$spec_content          = "";
        self::$relation_spec_comment = "";
        self::$relation_spec_content = "";
        $result .= self::domainDataobjectSpec( $fieldInfo, $tablename );
        $result .= self::domainDataobjectRelationSpec( $fieldInfo, $classname );
        if ( !empty(self::$spec_content) || !empty(self::$relation_spec_content) ) {
            $result .= "    /**\r\n".
                       "     * 规格说明\r\n".
                       self::$spec_comment.
                       self::$relation_spec_comment.
                       "     * @var mixed\r\n".
                       "     */\r\n".
                       "    public \$field_spec=array(\r\n".
                       self::$spec_content.
                       self::$relation_spec_content.
                       "    );\r\n";
        }

        $result .= self::domainEnumPropertyShow( $fieldInfo, $tablename );
        $result .= self::domainEnumShow( $fieldInfo, $tablename );
        $result .= self::domainTreeLevelDefine( $fieldInfo, $classname, $tablename );
        $result .= self::domainBitShow( $fieldInfo, $tablename );
        $result .= "}\r\n\r\n";
        return $result;
    }

    /**
     * 生成数据对象之间关系规范定义
     * 
     * 所有的数据对象关系:
     * 
     * 一对一，一对多，多对多
     * 
     * 包括 has_one,  belong_has_one, has_many, many_many, belongs_many_many.
     * 
     * 参考说明: EnumTableRelation
     * 
     * @param array $fieldInfo 表列信息列表
     * @param string $classname 数据对象类名称
     */
    private static function domainDataobjectRelationSpec($fieldInfo, $classname)
    {
        $result = "";
        if ( array_key_exists($classname, self::$relation_all) ) {
            $relationSpec = self::$relation_all[$classname];
        }
        if ( isset($relationSpec) && is_array($relationSpec) && ( count($relationSpec) > 0 ) ) {
            //导出一对一关系规范定义(如果存在)
            if ( array_key_exists("has_one", $relationSpec) ) {
                $has_one        = $relationSpec["has_one"];
                $has_one_effect = "";
                foreach ($has_one as $key => $value) {
                    $has_one_effect .= "        \"$value\" => \"$key\",\r\n";
                }
                if ( !empty($has_one_effect) ) {
                    $has_one_effect = substr($has_one_effect, 0, strlen($has_one_effect) - 3);
                }
                $result .= "\r\n".
                           "    /**\r\n".
                           "     * 一对一关系\r\n".
                           "     */\r\n".
                           "    static \$has_one = array(\r\n".
                           $has_one_effect . "\r\n".
                           "    );\r\n";
            }
            //导出从属一对一关系规范定义(如果存在)
            if ( array_key_exists("belong_has_one", $relationSpec) )
            {
                $belong_has_one        = $relationSpec["belong_has_one"];
                $belong_has_one_effect = "";
                foreach ($belong_has_one as $key => $value) {
                   $belong_has_one_effect .= "        \"$value\" => \"$key\",\r\n";
                }
                if ( !empty($belong_has_one_effect) ) $belong_has_one_effect = substr($belong_has_one_effect, 0, strlen($belong_has_one_effect) - 3);
                $result .= "\r\n".
                           "    /**\r\n".
                           "     * 从属一对一关系\r\n".
                           "     */\r\n".
                           "    static \$belong_has_one = array(\r\n".
                           $belong_has_one_effect."\r\n".
                           "    );\r\n";
                $classname_lc = $classname;
                $classname_lc = lcfirst($classname_lc);
                foreach ($belong_has_one as $key => $value) {
                    if ( $value == $classname_lc . "_p" ) {
                        self::$relation_spec_comment = "     * 外键特殊定义声明: FOREIGN_ID\r\n";
                        self::$relation_spec_content = "        EnumDataSpec::FOREIGN_ID => array(\r\n".
                                                       "            \"" . $classname_lc . "_p" . "\" => \"parent_id\"\r\n".
                                                       "        )\r\n";
                    }
                }
            }
            //导出一对多关系规范定义(如果存在)
            if ( array_key_exists("has_many", $relationSpec) )
            {
                $has_many        = $relationSpec["has_many"];
                $has_many_effect = "";
                foreach ($has_many as $key => $value) {
                   $has_many_effect .= "        \"" . $value . "\" => \"$key\",\r\n";
                }
                if ( !empty($has_many_effect) ) $has_many_effect = substr($has_many_effect, 0, strlen($has_many_effect) - 3);
                $result .= "\r\n".
                           "    /**\r\n".
                           "     * 一对多关系\r\n".
                           "     */\r\n".
                           "    static \$has_many = array(\r\n".
                           $has_many_effect."\r\n".
                           "    );\r\n";
            }
            //导出多对多关系规范定义(如果存在)
            if ( array_key_exists("many_many", $relationSpec) )
            {
                $many_many        = $relationSpec["many_many"];
                $many_many_effect = "";
                foreach ($many_many as $key => $value) {
                   $many_many_effect .= "        \"".$value."\" => \"$key\",\r\n";
                }
                if ( !empty($many_many_effect) ) $many_many_effect = substr($many_many_effect, 0, strlen($many_many_effect) - 3);
                $result .= "\r\n".
                           "    /**\r\n".
                           "     * 多对多关系\r\n".
                           "     */\r\n".
                           "    static \$many_many = array(\r\n".
                           $many_many_effect."\r\n".
                           "    );\r\n";
            }
            //导出从属于多对多关系规范定义(如果存在)
            if ( array_key_exists("belongs_many_many", $relationSpec) ) {
                $belongs_many_many        = $relationSpec["belongs_many_many"];
                $belongs_many_many_effect = "";
                foreach ($belongs_many_many as $key => $value) {
                   $belongs_many_many_effect .= "        \"" . $value . "\" => \"$key\",\r\n";
                }
                if ( !empty($belongs_many_many_effect) ) {
                    $belongs_many_many_effect = substr($belongs_many_many_effect, 0, strlen($belongs_many_many_effect) - 3);
                }
                $result .= "\r\n".
                           "    /**\r\n".
                           "     * 从属于多对多关系\r\n".
                           "     */\r\n".
                           "    static \$belongs_many_many = array(\r\n".
                           $belongs_many_many_effect . "\r\n".
                           "    );\r\n";
            }
        }
        return $result;
    }

    /**
     * 不符合规范的表定义需要用规格在数据文件里进行说明
     * 
     * 移除默认字段: commitTime, updateTime
     * 
     * @param array $fieldInfo 表列信息列表
     * @param string $tablename 表名称
     */
    private static function domainDataobjectSpec($fieldInfo, $tablename)
    {
        $result = "";
        $table_keyfield = array(
            EnumColumnNameDefault::COMMITTIME,
            EnumColumnNameDefault::UPDATETIME
        );
        $removefields = array();
        foreach ($table_keyfield as $keyfield) {
            if ( !array_key_exists($keyfield, $fieldInfo) ) {
                $removefields[] = $keyfield;
            }
        }
        $removeStr = "";
        foreach ($removefields as $removefield) {
            $removeStr .= "            '$removefield',\r\n";
        }
        if ( !empty($removeStr) ) {
            $removeStr = substr($removeStr, 0, strlen($removeStr) - 3);
            self::$spec_comment = "     * 表中不存在的默认列定义:" . implode(",", $removefields) . "\r\n";
            self::$spec_content = "        EnumDataSpec::REMOVE => array(\r\n".
                                  $removeStr . "\r\n".
                                  "        ),\r\n";
        }
        return $result;
    }

    /**
     * 在实体数据对象定义中定义枚举类型的显示
     * @param array $fieldInfo 表列信息列表
     * @param string $tablename 表名称
     */
    private static function domainEnumShow($fieldInfo, $tablename)
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field){
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::comment_type( $field["Type"] );
                if ( $datatype == 'enum' ) {
                    $comment = str_replace("\r\n", "     * ", $field["Comment"]);
                    $comment = str_replace("\r", "     * ", $comment);
                    $comment = str_replace("\n", "     * ", $comment);
                    $comment = str_replace("     * ", "<br/>\r\n     * ", $comment);
                    $result .= "\r\n".
                               "    /**\r\n".
                               "     * 显示" . $comment . "\r\n".
                               "     */\r\n";
                    $enumclassname = self::enumClassName($fieldname, $tablename);
                    $result .= "    public static function {$fieldname}Show(\${$fieldname})\r\n".
                               "    {\r\n".
                               "        return {$enumclassname}::{$fieldname}Show( \${$fieldname} );\r\n".
                               "    }\r\n";
                }
            }
        }
        return $result;
    }

    /**
     * 在实体数据对象定义中定义枚举类型的显示
     * @param array $fieldInfo 表列信息列表
     * @param string $tablename 表名称
     */
    private static function domainEnumPropertyShow($fieldInfo, $tablename)
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::comment_type($field["Type"]);
                if ( $datatype == 'enum' ) {
                    $comment  = str_replace("\r\n", "     * ", $field["Comment"]);
                    $comment  = str_replace("\r", "     * ", $comment);
                    $comment  = str_replace("\n", "     * ", $comment);
                    $comment  = str_replace("     * ", "<br/>\r\n     * ", $comment);
                    $result  .= "\r\n".
                                "    /**\r\n".
                                "     * 显示" . $comment . "\r\n".
                                "     */\r\n";
                    $enumclassname = self::enumClassName($fieldname, $tablename);
                    $fieldname_up  = ucfirst($fieldname);
                    $result  .= "    public function get{$fieldname_up}Show()\r\n".
                                "    {\r\n".
                                "        return self::{$fieldname}Show(\$this->{$fieldname});\r\n".
                                "    }\r\n";
                }
            }
        }
        return $result;
    }

    /**
     * 在实体数据对象定义中定义布尔类型bit的显示
     * @param array $fieldInfo 表列信息列表
     * @param string $tablename 表名称
     */
    private static function domainBitShow($fieldInfo, $tablename)
    {
        $result = "";
        foreach ($fieldInfo as $fieldname=>$field) {
            if ( self::isNotColumnKeywork($fieldname) ) {
                $datatype = self::comment_type($field["Type"]);
                if ( $datatype == 'bit' ) {
                    $comment  = str_replace("\r\n", "     * ", $field["Comment"]);
                    $comment  = str_replace("\r", "     * ", $comment);
                    $comment  = str_replace("\n", "     * ", $comment);
                    $comment  = str_replace("     * ", "<br/>\r\n     * ", $comment);
                    $result.= "\r\n".
                              "    /**\r\n".
                              "     * " . $comment . "\r\n".
                              "     */\r\n";
                    $result.= "    public function {$fieldname}Show()\r\n".
                              "    {\r\n".
                              "        if ( \$this->$fieldname ) {\r\n".
                              "            return \"是\";\r\n".
                              "        }\r\n".
                              "        return \"否\";\r\n".
                              "    }\r\n";
                }
            }
        }
        return $result;
    }

    /**
     * 只有带有目录树数据模型的数据对象定义中才拥有的方法
     * @param array $fieldInfo 表列信息列表
     * @param string $classname 当前类名称
     */
    private static function domainTreeLevelDefine($fieldInfo, $classname, $tablename)
    {
        $result = "\r\n";
        if ( array_key_exists("parent_id", $fieldInfo) ) {
            if ( array_key_exists("countChild", $fieldInfo) || array_key_exists("childCount", $fieldInfo) ) {
                $realId  = DataObjectSpec::getRealIDColumnName( $classname );
                $result .= "    /**\r\n".
                           "     * 计算所有的子元素数量并存储\r\n".
                           "     */\r\n".
                           "    public static function allCountChild()\r\n".
                           "    {\r\n".
                           "        \$max_id = $classname::max();\r\n".
                           "        for (\$i = 1;\$i <= \$max_id;\$i++) {\r\n".
                           "            \$countChild = $classname::select( \"count(*)\", \"parent_id=\" . \$i );\r\n".
                           "            $classname::updateBy( \"$realId=\" . \$i, \"countChild=\" . \$countChild );\r\n".
                           "        }\r\n".
                           "    }\r\n\r\n";
            }
            if ( array_key_exists("level", $fieldInfo) ) {
                $result .= "    /**\r\n".
                           "     * 最高的层次，默认为3\r\n".
                           "     */\r\n".
                           "    public static function maxlevel()\r\n".
                           "    {\r\n".
                           "        return $classname::select( \"max(level)\" );//return 3;\r\n".
                           "    }\r\n\r\n";
                $instance_name = self::getInstancename( $tablename );
                if ( is_array(self::$relation_viewfield) && ( count( self::$relation_viewfield ) > 0 ) )
                {
                    if ( array_key_exists($classname, self::$relation_viewfield) ) {
                        $relationSpecs = self::$relation_viewfield[$classname];
                        $fieldname     = "parent_id";
                        $relationShow  = $relationSpecs[$fieldname];
                        $isTreeLevelHad = false;
                        foreach ($relationShow as $key => $value) {
                            $classNameField = self::getShowFieldName( $key );
                            $field          = $fieldInfo[$fieldname];
                            $field_comment  = $field["Comment"];
                            $field_comment  = self::columnCommentKey( $field_comment, $fieldname );
                            if ( !$isTreeLevelHad ) {
                                $result .= "    /**\r\n".
                                "     * 显示{$field_comment}[全]\r\n".
                                "     */\r\n".
                                "    public function get{$classname}ShowAll()\r\n".
                                "    {\r\n".
                                "        return self::{$instance_name}ShowAll( \$this->parent_id, \$this->level );\r\n". 
                                "    }\r\n\r\n";

                                $result .= "    /**\r\n".
                                "     * 显示{$field_comment}[全]\r\n".
                                "     * 注:采用了递归写法\r\n".
                                "     * @param int \$parent_id {$field_comment}标识\r\n".
                                "     * @param int \$level 目录层级\r\n".
                                "     */\r\n".
                                "    public static function {$instance_name}ShowAll(\$parent_id, \$level)\r\n".
                                "    {\r\n".
                                "        \${$instance_name}_p = $classname::get_by_id( \$parent_id );\r\n".
                                "        if ( \$level == 1 ) {\r\n".
                                "             \${$instance_name}ShowAll = \${$instance_name}_p->$classNameField;\r\n".
                                "        } else {\r\n".
                                "             \$parent_id     = \${$instance_name}_p->parent_id;\r\n".
                                "             \${$instance_name}ShowAll = self::{$instance_name}ShowAll( \$parent_id, \$level - 1 ) . \"->\" . \${$instance_name}_p->$classNameField;\r\n". 
                                "        }\r\n". 
                                "        return \${$instance_name}ShowAll;\r\n". 
                                "    }\r\n\r\n";
                                $isTreeLevelHad = true;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 从表名称获取子文件夹的信息。
     * @param string $tablename 表名称
     * @return string 返回对象所在的Package名
     */
    private static function getPackage($tablename)
    {
        $pacre   = str_replace(Config_Db::$table_prefix, "", $tablename);
        $pacre   = str_replace(Config_Db::TABLENAME_RELATION, Config_Db::TABLENAME_DIR_RELATION, $pacre);
        $package = str_replace("_", ".", $pacre);
        $packageSplit = explode(".", $package);
        unset($packageSplit[count($packageSplit) - 1]);
        $package = implode(".", $packageSplit);
        return $package;
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码
     */
    private static function saveDataObjectDefineToDir($tablename, $definePhpFileContent)
    {
        $package   = self::getPackage($tablename);
        $classname = self::getClassname($tablename);
        $filename  = "$classname.php";
        $package   = str_replace(".", DS, $package);
        $relative_path = str_replace(self::$save_dir, "", self::$domain_dir_full . $package . DS . $filename);
        AutoCodePreviewReport::$domain_files[$classname] = $relative_path;
        return self::saveDefineToDir( self::$domain_dir_full . $package, $filename, $definePhpFileContent );
    }

    /**
     * 保存生成的枚举类型代码到指定命名规范的文件中
     * @param string $enumclassname 枚举类名称
     * @param string $definePhpFileContent 生成的代码
     */
    private static function saveEnumDefineToDir($enumclassname, $definePhpFileContent)
    {
        $filename      = $enumclassname . ".php";
        $relative_path = str_replace(self::$save_dir, "", self::$domain_dir_full . self::$enum_dir . DS . $filename);
        AutoCodePreviewReport::$enum_files[$enumclassname] = $relative_path;
        return self::saveDefineToDir( self::$domain_dir_full . self::$enum_dir, $filename, $definePhpFileContent );
    }
}
