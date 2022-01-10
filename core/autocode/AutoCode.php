<?php

/**
 * -----------| 所有自动生成代码工具的父类 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen <skygreen2001@gmail.com>
 */
class AutoCode extends BBObject
{
    /**
     * 显示生成前结果
     * @var string
     */
    public static $showPreviewReport;
    /**
     * 显示生成结果
     * @var string
     */
    public static $showReport;
    /**
     * 是否还需要输出页面的css样式
     *
     * 确保css只生成一次
     * @var boolean
     */
    public static $isOutputCss = true;
    /**
     * 开发者
     * @var string
     */
    public static $author = "skygreen skygreen2001@gmail.com";
    /**
     * 生成Php文件保存的路径
     * @var string
     */
    public static $save_dir;
    /**
     * 应用所在的路径
     * @var string
     */
    public static $app_dir;
    /**
     * 生成源码[services|domain]所在目录名称
     * @var string
     */
    public static $dir_src = "src";
    /**
     * 实体数据对象类文件所在的路径
     * @var string
     */
    public static $domain_dir = "domain";
    /**
     * 表列表
     * @var mixed
     */
    public static $tableList;
    /**
     * 所有表信息
     * @var mixed
     */
    public static $tableInfoList;
    /**
     * 所有表列信息
     * @var mixed
     */
    public static $fieldInfos = array();
    /**
     * 数据对象关系显示字段
     * @var mixed
     */
    public static $relation_viewfield;
    /**
     * 所有的数据对象关系:
     *    一对一，一对多，多对多
     *
     * 包括 has_one, belong_has_one, has_many, many_many, belongs_many_many.
     * 参考说明: EnumTableRelation
     * @var mixed
     */
    public static $relation_all;
    /**
     * 数据对象引用另一个数据对象同样值的冗余字段
     * @var mixed
     */
    public static $redundancy_table_fields;
    /**
     * 获取类和注释的说明
     * @var string
     */
    public static $class_comments;

    /**
     * 初始化
     * @return void
     */
    public static function init()
    {
        UtilFileSystem::createDir(self::$save_dir);
        system_dir_info(self::$save_dir);
        if (empty(self::$tableList)) {
            self::$tableInfoList = ManagerDb::newInstance()->dbinfo()->tableInfoList();
            if (self::$tableInfoList) {
                self::$tableList = array_keys(self::$tableInfoList);
            }
        }
        if (empty(self::$fieldInfos) && (!empty(self::$tableList))) {
            $ignoreTables = array();
            foreach (self::$tableList as $tablename) {
                $classname = self::getClassname($tablename);
                $prefix    = ConfigDb::$table_prefix;
                if ((!empty($prefix)) && (!contain($tablename, $prefix))) {
                    $ignoreTables[] = $tablename;
                    continue;
                }

                if (startWith(strtolower($classname), "copy")) {
                    $ignoreTables[] = $tablename;
                    continue;
                }
                $fieldInfoList = ManagerDb::newInstance()->dbinfo()->fieldInfoList($tablename);
                foreach ($fieldInfoList as $fieldname => $field) {
                    self::$fieldInfos[$tablename][$fieldname]["Field"]   = $field["Field"];
                    self::$fieldInfos[$tablename][$fieldname]["Type"]    = $field["Type"];
                    self::$fieldInfos[$tablename][$fieldname]["Comment"] = $field["Comment"];
                    self::$fieldInfos[$tablename][$fieldname]["Key"]     = $field["Key"];
                    if ($field["Null"] == 'NO') {
                        self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"] = false;
                    } else {
                        self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"] = true;
                    }
                }
                self::$class_comments[$classname] = self::$tableInfoList[$tablename]["Comment"];
            }
            self::$tableList = array_diff(self::$tableList, $ignoreTables);
            foreach ($ignoreTables as $tablename) {
                unset(self::$tableInfoList[$tablename]);
            }
        }
    }

    /**
     * 从表名称获取对象的类名【头字母大写】。
     * @param string $tablename 表名称
     * @return string 返回对象的类名
     */
    protected static function getClassname($tablename)
    {
        if (ConfigAutoCode::DB_TABLE_DOMAIN_EQUAL) {
            $classname = ucfirst($tablename);
            return $classname;
        }
        if (in_array($tablename, ConfigDb::$orm)) {
            $classname = array_search($tablename, ConfigDb::$orm);
        } else {
            $classnameSplit = explode("_", $tablename);
            $classnameSplit = array_reverse($classnameSplit);
            $classname      = ucfirst($classnameSplit[0]);
        }
        return $classname;
    }

    /**
     * 根据类名获取表名
     * @param mixed $class
     * @return string
     */
    protected static function getTablename($class)
    {
        $tableList = ManagerDb::newInstance()->dbinfo()->tableList();
        foreach ($tableList as $tablename) {
            $classname = self::getClassname($tablename);
            if ($class == $classname) {
                return $tablename;
            }
        }
        return null;
    }

    /**
     * 获取指定的表的列信息
     * @param array|string $table_names 表列表
     * 示例如下:
     *
     *     1. array:array('bb_user_admin','bb_core_blog')
     *     2. 字符串:'bb_user_admin,bb_core_blog'
     * @return array
     */
    protected static function fieldInfosByTable_names($table_names)
    {
        $fieldInfos = self::$fieldInfos;
        if (!empty($table_names)) {
            $fieldInfos = array();

            if (is_string($table_names)) {
                $table_names = explode(",", $table_names);
            }
            if ($table_names && (count($table_names) > 0)) {
                for ($i = 0; $i < count($table_names); $i++) {
                    if (!empty($table_names[$i])) {
                        $tablename              = $table_names[$i];
                        $fieldInfos[$tablename] = self::$fieldInfos[$tablename];
                    }
                }
            }
        }
        return $fieldInfos;
    }

    /**
     * 获取指定的表的表信息
     * @param array|string $table_names 表列表
     * 示例如下:
     *
     *     1. array:array('bb_user_admin','bb_core_blog')
     *     2. 字符串:'bb_user_admin,bb_core_blog'
     * @return array
     */
    protected static function tableInfosByTable_names($table_names)
    {
        $tableInfos = self::$tableInfoList;
        if (!empty($table_names)) {
            $tableInfos = array();
            if (is_string($table_names)) {
                $table_names = explode(",", $table_names);
            }
            if ($table_names && (count($table_names) > 0)) {
                for ($i = 0; $i < count($table_names); $i++) {
                    if (!empty($table_names[$i])) {
                        $tablename = $table_names[$i];
                        $tableInfos[$tablename] = self::$tableInfoList[$tablename];
                    }
                }
            }
        }
        return $tableInfos;
    }

    /**
     * 获取指定的表的表名
     * @param array|string $table_names 表列表
     * 示例如下:
     *
     *     1. array:array('bb_user_admin','bb_core_blog')
     *     2. 字符串:'bb_user_admin,bb_core_blog'
     * @return mixed
     */
    protected static function tableListByTable_names($table_names)
    {
        $tableList = self::$tableList;
        if (!empty($table_names)) {
            if (is_string($table_names)) {
                $table_names = explode(",", $table_names);
            }
            if ($table_names && (count($table_names) > 0)) {
                $tableList = $table_names;
            }
        }
        return $tableList;
    }

    /**
     * 从表名称获取对象的类名实例化名【头字母小写】。
     * @param string $tablename 表名称
     * @return string 返回对象的类名
     */
    protected static function getInstancename($tablename)
    {
        if (in_array($tablename, ConfigDb::$orm)) {
            $classname = array_search($tablename, ConfigDb::$orm);
        } else {
            $classnameSplit = explode("_", $tablename);
            $classnameSplit = array_reverse($classnameSplit);
            $classname      = $classnameSplit[0];
        }
        return $classname;
    }

    /**
     * 表中列的类型定义
     * @param string $type
     * @return mixed
     */
    protected static function column_type($type)
    {
        if (contain($type, "(")) {
            list($typep, $length) = preg_split("/[()]/", $type);
        } else {
            $typep = $type;
        }
        return $typep;
    }

    /**
     * 将表中的类型定义转换成对象field的注释类型说明
     * @param string $type 类型定义
     * @return string
     */
    protected static function comment_type($type)
    {
        $typep = self::column_type($type);
        switch ($typep) {
            case "int":
            case "enum":
                return $typep;
            case "timestamp":
            case "datetime":
            case "date":
                return 'date';
            case "bigint":
                return "int";
            case "bit":
                return "bit";
            case "decimal":
                return "float";
            case "varchar":
                return "string";
            default:
                return "string";
        }
    }

    /**
     * 表中列的长度定义
     * @param string $type
     * @return int
     */
    protected static function column_length($type)
    {
        if (contain($type, "(")) {
            list($typep, $length) = preg_split("/[()]/", $type);
        } else {
            $length = 1;
        }
        return $length;
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $dir 保存路径
     * @param string $filename 文件名称
     * @param string $definePhpFileContent 生成的代码
     * @return string
     */
    protected static function saveDefineToDir($dir, $filename, $definePhpFileContent)
    {
        UtilFileSystem::createDir($dir);
        UtilFileSystem::save_file_content($dir . DS . $filename, $definePhpFileContent);
        return basename($filename, ".php");
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $dir 保存路径
     * @param string $filename 文件名称
     * @param string $definePhpFileContent 生成的代码
     * @return string
     */
    protected static function saveDefineToFile($filename, $definePhpFileContent)
    {
        UtilFileSystem::createDir(dirname($filename));
        UtilFileSystem::save_file_content($filename, $definePhpFileContent);
        return basename($filename, ".php");
    }

    /**
     * 用户输入需求
     * @param $title 标题
     * @param $inputArr 输入用户需求的选项
     * @param $default_value 默认值
     * @param $more_content 更多个性化内容
     * @return void
     */
    protected static function UserInput($title = "", $inputArr = null, $default_value = "", $more_content = "")
    {
        ob_clean();
        include("view" . DS . "form" . DS . "userinput.php");
        echo $userinput_model;
    }

    /**
     * 根据表列枚举类型生成枚举类名称
     * @param string $columnname 枚举列名称
     * @param string $tablename 表名称
     * @return string
     */
    protected static function enumClassName($columnname, $tablename = null)
    {
        $enumclassname = "Enum";
        if ((strtolower($columnname) == 'type') || (strtolower($columnname) == 'statue') || (strtolower($columnname) == 'status')) {
            $enumclassname .= self::getClassname($tablename) . ucfirst($columnname);
        } else {
            if (contain($columnname, "_")) {
                $c_part = explode("_", $columnname);
                foreach ($c_part as $column) {
                    $enumclassname .= ucfirst($column);
                }
            } else {
                $enumclassname .= ucfirst($columnname);
            }
        }
        return $enumclassname;
    }

    /**
     * 表枚举类型列注释转换成可以处理的数组数据
     * 注释风格如下:
     *    用户性别
     *    0: 女-female
     *    1: 男-mail
     *    -1: 待确认-unknown
     *    默认男
     * @param mixed $fieldComment 表枚举类型列注释
     * @return array
     */
    protected static function enumDefines($fieldComment)
    {
        $comment_arr = preg_split("/[\r\n;]+/", $fieldComment);
        unset($comment_arr[0]);
        $enum_columnDefine = array();
        if ((!empty($comment_arr)) && (count($comment_arr) > 0)) {
            foreach ($comment_arr as $comment) {
                if (!UtilString::is_utf8($comment)) {
                    $comment = UtilString::gbk2utf8($comment);
                }
                if (contain($comment, "：")) {
                    $comment = str_replace("：", ':', $comment);
                }
                $part_arr = preg_split("/[.:]+/", $comment);
                if ((!empty($part_arr)) && (count($part_arr) == 2)) {
                    $cn_en_arr    = array();
                    $enum_comment = $part_arr[1];
                    if (is_numeric($part_arr[0])) {
                        if (contain($enum_comment, "-")) {
                            $cn_en_arr[0] = substr($enum_comment, 0, strrpos($enum_comment, "-"));
                            $cn_en_arr[1] = substr($enum_comment, strrpos($enum_comment, "-") + 1);
                            $enum_columnDefine[] = array(
                                'name'    => strtolower($cn_en_arr[1]),
                                'value'   => $part_arr[0],
                                'comment' => $cn_en_arr[0]
                            );
                        }
                    } else {
                        $enum_columnDefine[] = array(
                            'name'    => strtolower($part_arr[0]),
                            'value'   => strtolower($part_arr[0]),
                            'comment' => $part_arr[1]
                        );
                    }
                }
            }
        }
        return $enum_columnDefine;
    }

    /**
     * 列是否大量文本输入应该TextArea输入
     * @param string $column_name 列名称
     * @param string $column_type 列类型
     * @return boolean
     */
    protected static function columnIsTextArea($column_name, $column_type)
    {
        $column_name = strtoupper($column_name);
        if (contain($column_name, "ID")) {
            return false;
        }
        if (
            ((self::column_length($column_type) >= 500) && (!contains($column_name, array("URL", "LINK", "PASSWORD", "EMAIL", "PHONE", "ADDRESS"))))
             || (contains($column_name, array("INTRO","MEMO","CONTENT"))) || (self::column_type($column_type) == 'text') || (self::column_type($column_type) == 'longtext')
        ) {
            if (self::columnIsImage($column_name)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 列是否是图片路径
     * @param string $column_name 列名称
     * @param mixed $column_comment 列注释
     * @return boolean
     */
    protected static function columnIsImage($column_name, $column_comment = "")
    {
        $column_name = strtoupper($column_name);
        if (contain($column_name, "ID")) {
            return false;
        }
        if (contains($column_name, array("THUMBNAIL", "PROFILE", "IMAGE", "IMG", "ICO", "LOGO", "PIC"))) {
            return true;
        }
        if (contains($column_comment, array("图像地址", "图片地址"))) {
            return true;
        }
        return false;
    }

    /**
     * 列是否是email
     * @param string $column_name 列名称
     * @param mixed $column_comment 列注释
     * @return boolean
     */
    protected static function columnIsEmail($column_name, $column_comment)
    {
        $column_name = strtoupper($column_name);
        if ((contain($column_name, "EMAIL") || contains($column_comment, array("邮件","邮箱"))) && (!contain($column_name, "IS"))) {
            return true;
        }
        return false;
    }

    /**
     * 列是否是密码
     * @param string $tablename 表名称
     * @param string $column_name 列名称
     * @return boolean
     */
    protected static function columnIsPassword($table_name, $column_name)
    {
        $table_name = strtoupper($table_name);
        if (contains($table_name, array("MEMBER", "ADMIN", "USER"))) {
            $column_name = strtoupper($column_name);
            if (contain($column_name, "PASSWORD")) {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否默认的列关键字: id,committime,updateTime
     * @param string $fieldname 列名称
     * @return boolean
     */
    protected static function isNotColumnKeywork($fieldname)
    {
        $fieldname = strtoupper($fieldname);
        if ($fieldname == strtoupper(EnumColumnNameDefault::COMMITTIME) || $fieldname == strtoupper(EnumColumnNameDefault::UPDATETIME)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取数据对象的ID列名称
     * @param mixed $dataobject 数据对象实体|对象名称
     * @return string
     */
    protected static function keyIDColumn($dataobject)
    {
        return DataObjectSpec::getRealIDColumnNameStatic($dataobject);
    }

    /**
     * 获取表注释第一行关键词说明
     * @param string $tablename 表名称
     * @return string
     */
    protected static function tableCommentKey($tablename)
    {
        if (self::$tableInfoList != null && count(self::$tableInfoList) > 0 && array_key_exists("$tablename", self::$tableInfoList)) {
            $table_comment = self::$tableInfoList[$tablename]["Comment"];
            $table_comment = str_replace("关系表", "", $table_comment);
            $table_comment = str_replace("表", "", $table_comment);
            if (contain($table_comment, "\r") || contain($table_comment, "\n")) {
                $table_comment = preg_split("/[\s,]+/", $table_comment);
                $table_comment = $table_comment[0];
            }

            if (empty($table_comment)) {
                $table_comment = self::getInstancename($tablename);
            }
        } else {
            $table_comment = self::getClassname($tablename);
        }
        return $table_comment;
    }

    /**
     * 获取列注释第一行关键词说明
     * @param mixed $field_comment 列注释
     * @param mixed $default 默认返回值
     * @return mixed
     */
    protected static function columnCommentKey($field_comment, $default = "")
    {
        if (empty($field_comment)) {
            return $default;
        }
        if (contain($field_comment, "\r") || contain($field_comment, "\n")) {
            $field_comment = preg_split("/[\s,]+/", $field_comment);
            $field_comment = $field_comment[0];
        }
        if ($field_comment) {
            if (($field_comment != "标识") && ($field_comment != "编号") && ($field_comment != "主键")) {
                $field_comment = str_replace(array('标识', '编号', '主键'), "", $field_comment);
            }
        }
        return $field_comment;
    }

    /**
     * 根据类名获取表代表列显示名称
     * @param string $classname 数据对象类名
     * @param bool $isReturnEmpty 是否没有就返回空
     * @return string
     */
    protected static function getShowFieldNameByClassname($classname, $isReturnNull = false)
    {
        $fieldInfo  = self::$fieldInfos[self::getTablename($classname)];
        $fieldNames = array_keys($fieldInfo);
        foreach ($fieldNames as $fieldname) {
            $fieldname_filter = strtolower($fieldname);
            if (!contain($fieldname_filter, "id")) {
                if (contains($fieldname_filter, array("name", "title"))) {
                    return $fieldname;
                }
                $classname_filter = strtolower($classname);
                if (contain($fieldname_filter, $classname_filter)) {
                    return $fieldname;
                }
            }
        }
        if ($isReturnNull) {
            return "";
        } else {
            return "name";
        }
    }

    /**
     * 根据类名和表信息获取表代表列显示名称
     * @param string $classname 数据对象类名
     * @return string
     */
    protected static function getShowFieldName($classname)
    {
        $classNameField = self::getShowFieldNameByClassname($classname, true);
        if (empty($classNameField)) {
            $fieldInfo  = self::$fieldInfos[self::getTablename($classname)];
            $fieldNames = array_keys($fieldInfo);
            foreach ($fieldNames as $fieldname) {
                if (!contain($fieldname, "id")) {
                    $classNameField = $fieldname;
                    break;
                }
            }
        }
        return $classNameField;
    }

    /**
     * 根据类名判断是不是多对多关系，存在中间表表名
     * @param string $classname 数据对象类名
     * @return boolean
     */
    protected static function isMany2ManyByClassname($classname)
    {
        $tablename = self::getTablename($classname);
        if (contain($tablename, ConfigDb::TABLENAME_RELATION . "_")) {
            $fieldInfo = self::$fieldInfos[self::getTablename($classname)];
            $realId    = DataObjectSpec::getRealIDColumnName($classname);
            unset($fieldInfo[$realId]);
            $countC    = 0;
            foreach (array_keys($fieldInfo) as $fieldname) {
                if (contain($fieldname, "_id")) {
                    $countC += 1;
                }
            }
            if ($countC <= 1) {
                return false;
            }

            return true;
        }
        return false;
    }

    /**
     * 根据类名判断是不是多对多关系，如果存在其他显示字段则需要在显示Tab中显示has_many
     * @param string $classname 数据对象类名
     * @return boolean
     */
    protected static function isMany2ManyShowHasMany($classname)
    {
        if (self::isMany2ManyByClassname($classname)) {
            $fieldInfo = self::$fieldInfos[self::getTablename($classname)];
            unset($fieldInfo[EnumColumnNameDefault::COMMITTIME], $fieldInfo[EnumColumnNameDefault::UPDATETIME]);
            $realId    = DataObjectSpec::getRealIDColumnName($classname);
            unset($fieldInfo[$realId]);
            if (count($fieldInfo) == 2) {
                return false;
            }
        }
        return true;
    }
}
