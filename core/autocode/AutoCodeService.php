<?php

/**
 * -----------| 工具类:自动生成代码-服务类 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeService extends AutoCode
{
    /**
     * Service类所在的目录
     */
    public static $package = "services";
    /**
     * Service类所在的目录
     */
    public static $service_dir = "services";
    /**
     * Ext js Service文件所在的路径
     */
    public static $ext_dir = "ext";
    /**
    * Service完整的保存路径
    */
    public static $service_dir_full;
    /**
     * 服务类生成定义的方式
     * 1.继承具有标准方法的Service。
     * 2.生成标准方法的Service。
     */
    public static $type;

    /**
     * 自动生成代码-服务类
     * @param array|string $table_names
     * 示例如下:
     *  1. array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names = "")
    {
        self::$app_dir = Gc::$appName;
        if (!UtilString::is_utf8(self::$service_dir_full)) {
            self::$service_dir_full = UtilString::gbk2utf8(self::$service_dir_full);
        }
        // self::$service_dir_full = self::$save_dir . Gc::$module_root . DS  . self::$app_dir . DS . self::$dir_src . DS . self::$service_dir . DS;
        self::$service_dir_full = self::$save_dir . Gc::$module_root . DS  . "admin" . DS . self::$dir_src . DS . self::$service_dir . DS;

        self::init();

        if (self::$isOutputCss) {
            self::$showReport .= UtilCss::form_css() . HH;
        }

        switch (self::$type) {
            case 1:
                self::$showReport .= AutoCodeFoldHelper::foldEffectCommon("Content_22");
                self::$showReport .= "<font color='#237319'>生成继承具有标准方法的Service文件导出↓</font></a>";
                self::$showReport .= '<div id="Content_22" style="display:none;">';
                break;
            case 2:
                self::$showReport .= AutoCodeFoldHelper::foldEffectCommon("Content_21");
                self::$showReport .= "<font color='#237319'>标准方法的Service文件导出↓</font></a>";
                self::$showReport .= '<div id="Content_21" style="display:none;">';
                break;
        }
        $link_service_dir_href = "file:///" . str_replace("\\", "/", self::$service_dir_full);
        self::$showReport     .= "<font color='#AAA'>存储路径:<a target='_blank' href='" . $link_service_dir_href . "'>" . self::$service_dir_full . "</a></font>";

        $tableList = self::tableListByTable_names($table_names);
        foreach ($tableList as $tablename) {
            $definePhpFileContent = self::tableToServiceDefine($tablename);
            if (isset($definePhpFileContent) && (!empty($definePhpFileContent) )) {
                $classname = self::saveServiceDefineToDir($tablename, $definePhpFileContent);
                self::$showReport .= "生成导出完成:$tablename => $classname!";
            } else {
                self::$showReport .= $definePhpFileContent . "";
            }
        }
        self::$showReport .= '</div><br>';

        $category = Gc::$appName;
        $author   = self::$author;
        $package  = self::$package;

        /**
         * 需要在管理类Manager_Service.php里添加的代码
         */
        self::$showReport .= "<font color='#237319'>[需要在管理类Manager_Service里添加没有的代码]</font><br />";

        // 创建后台管理服务类
        $result = self::createManageService($table_names);
        $section_define  = $result["section_define"];
        $section_content = $result["section_content"];
        $e_result = "<?php" . HH .
                    "/**" . HH .
                    " * -----------| 服务类:所有Service的管理类 |-----------" . HH .
                    " * @category $category" . HH .
                    " * @package $package" . HH .
                    " * @author $author" . HH .
                    " */" . HH .
                    "class Manager_Service extends Manager" . HH .
                    "{" . HH . $section_define . HH . $section_content . "}" . HH;
        self::saveDefineToDir(self::$service_dir_full, "Manager_Service.php", $e_result);
        $link_service_manage_dir_href = "file:///" . str_replace("\\", "/", self::$service_dir_full) . "Manager_Service.php";
        self::$showReport .=  "新生成的Manager_Service文件路径:<br />" . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                              "<font color='#0000FF'style='word-break: break-all;'><a target='_blank' href='$link_service_manage_dir_href'>" . self::$service_dir_full . "Manager_Service.php</a></font><br />";
    }

    /**
     * 创建前台管理服务类
     * @param array|string $table_names
     * 示例如下:
     *  1. array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function createManageService($table_names = "")
    {
        $section_define  = "";
        $section_content = "";
        $result          = array();

        $tableList = self::tableListByTable_names($table_names);
        foreach ($tableList as $tablename) {
            $table_comment = self::tableCommentKey($tablename);
            $classname     = self::getClassname($tablename);
            $classname     = lcfirst($classname);
            $service_classname = self::getServiceClassname($tablename);
            $section_define   .= "    private static \$" . $classname . "Service;" . HH;
            $section_content  .= "    /**" . HH .
                                 "     * 提供服务: " . $table_comment . HH .
                                 "     */" . HH;
            $section_content  .= "    public static function " . $classname . "Service()" . HH .
                                 "    {" . HH .
                                 "        if (self::\$" . $classname . "Service == null) {" . HH .
                                 "            self::\$" . $classname . "Service = new $service_classname();" . HH .
                                 "        }" . HH .
                                 "        return self::\$" . $classname . "Service;" . HH .
                                 "    }" . HH . HH;
        }
        $result["section_define"]  = $section_define;
        $result["section_content"] = $section_content;
        return $result;
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value = "", $title = "", $inputArr = null, $more_content = "")
    {
        $inputArr = array(
            "2" => "生成标准方法的Service",
            "1" => "继承具有标准方法的Service",
        );
        parent::UserInput("一键生成服务类定义层", $inputArr, $default_value);
    }

    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfos 表列信息列表
     */
    private static function tableToServiceDefine($tablename)
    {
        if (array_key_exists($tablename, self::$fieldInfos)) {
            $fieldInfo = self::$fieldInfos[$tablename];
        } else {
            $fieldInfo = self::$fieldInfos;
        }
        $result            = "<?php" . HH;
        $classname         = self::getClassname($tablename);
        $instance_name     = self::getInstancename($tablename);
        $service_classname = self::getServiceClassname($tablename);
        $object_desc       = "";
        $object_desc       = self::tableCommentKey($tablename);
        if (self::$tableInfoList != null && count(self::$tableInfoList) > 0 && array_key_exists("$tablename", self::$tableInfoList)) {
            $table_comment = "服务类: " . $object_desc;
        } else {
            $table_comment = "关于服务类{$classname}的描述";
        }
        $category = Gc::$appName;
        $author   = self::$author;
        $package  = self::$package;
        $result  .= "/**" . HH .
                    " * -----------| $table_comment |-----------" . HH .
                    " * @category $category" . HH .
                    " * @package $package" . HH;
        $result .= " * @author $author" . HH .
                   " */" . HH;
        switch (self::$type) {
            case 2:
                $result .= "class $service_classname extends Service implements IServiceBasic " . HH . "{" . HH;
                //save
                $result .= "    /**" . HH .
                           "     * 保存数据对象: {$object_desc}" . HH .
                           "     * " . HH .
                           "     * @param array|DataObject \$$instance_name" . HH .
                           "     * @return int 保存对象记录的ID标识号" . HH .
                           "     */" . HH;
                $result .= "    public function save(\$$instance_name)" . HH .
                           "    {" . HH .
                           "        if (is_array(\$$instance_name)) {" . HH .
                           "            \$$instance_name = new $classname(\$$instance_name);" . HH .
                           "        }" . HH .
                           "        if (\$$instance_name instanceof $classname) {" . HH .
                           "            return \$" . $instance_name . "->save();" . HH .
                           "        } else {" . HH .
                           "            return false;" . HH .
                           "        }" . HH .
                           "    }" . HH . HH;
                //update
                $result .= "    /**" . HH .
                           "     * 更新数据对象: {$object_desc}" . HH .
                           "     * " . HH .
                           "     * @param array|DataObject \$$instance_name" . HH .
                           "     * @return boolen 是否更新成功；true为操作正常" . HH .
                           "     */" . HH;
                $result .= "    public function update(\$$instance_name)" . HH .
                           "    {" . HH .
                           "        if (is_array(\$$instance_name)) {" . HH .
                           "            \$$instance_name = new $classname( \$$instance_name );" . HH .
                           "        }" . HH .
                           "        if (\$$instance_name instanceof $classname) {" . HH .
                           "            return \$" . $instance_name . "->update();" . HH .
                           "        } else {" . HH .
                           "            return false;" . HH .
                           "        }" . HH .
                           "    }" . HH . HH;
                //deleteByID
                $result .= "    /**" . HH .
                           "     * 由标识删除指定ID数据对象: {$object_desc}" . HH .
                           "     * " . HH .
                           "     * @param int \$id 数据对象: {$object_desc}标识" . HH .
                           "     * @return boolen 是否删除成功；true为操作正常" . HH .
                           "     */" . HH;
                $result .= "    public function deleteByID(\$id)" . HH .
                           "    {" . HH .
                           "        return $classname::deleteByID( \$id );" . HH .
                           "    }" . HH . HH;
                //deleteByIds
                $result .= "    /**" . HH .
                           "     * 根据主键删除数据对象: {$object_desc}的多条数据记录" . HH .
                           "     * " . HH .
                           "     * @param array|string \$ids 数据对象编号" . HH .
                           "     * 形式如下:" . HH .
                           "     * " . HH .
                           "     *     1. array:array(1, 2, 3, 4, 5)" . HH .
                           "     *     2. 字符串:1, 2, 3, 4 " . HH .
                           "     * @return boolen 是否删除成功；true为操作正常" . HH .
                           "     */" . HH;
                $result .= "    public function deleteByIds(\$ids)" . HH .
                           "    {" . HH .
                           "        return $classname::deleteByIds( \$ids );" . HH .
                           "    }" . HH . HH;
                //increment
                $result .= "    /**" . HH .
                           "     * 对数据对象: {$object_desc}的属性进行递增" . HH .
                           "     * " . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"" . HH .
                           "     * " . HH .
                           "     * @param string \$property_name 属性名称 " . HH .
                           "     * @param int \$incre_value 递增数 " . HH .
                           "     * @return boolen 是否操作成功；true为操作正常" . HH .
                           "     */" . HH;
                $result .= "    public function increment(\$filter = null, \$property_name, \$incre_value)" . HH .
                           "    {" . HH .
                           "        return $classname::increment( \$filter, \$property_name, \$incre_value );" . HH .
                           "    }" . HH . HH;
                //decrement
                $result .= "    /**" . HH .
                           "     * 对数据对象: {$object_desc}的属性进行递减" . HH .
                           "     * " . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"" . HH .
                           "     * " . HH .
                           "     * @param string \$property_name 属性名称 " . HH .
                           "     * @param int \$decre_value 递减数 " . HH .
                           "     * @return boolen 是否操作成功；true为操作正常" . HH .
                           "     */" . HH;
                $result .= "    public function decrement(\$filter = null, \$property_name, \$decre_value)" . HH .
                           "    {" . HH .
                           "        return $classname::decrement( \$filter, \$property_name, \$decre_value );" . HH .
                           "    }" . HH . HH;
                //select
                $result .= "    /**" . HH .
                           "     * 查询数据对象: {$object_desc}需显示属性的列表" . HH .
                           "     * " . HH .
                           "     * @param string \$columns 指定的显示属性，同SQL语句中的Select部分。 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     id, name, commitTime" . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\" " . HH .
                           "     * " . HH .
                           "     * @param string \$sort 排序条件" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     1. id asc;" . HH .
                           "     *     2. name desc;" . HH .
                           "     * " . HH .
                           "     * @param string \$limit 分页数目:同Mysql limit语法" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *       0, 10" . HH .
                           "     * " . HH .
                           "     * @return array 数据对象: {$object_desc}列表数组" . HH .
                           "     */" . HH;
                $result .= "    public function select(\$columns, \$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, \$limit = null)" . HH .
                           "    {" . HH .
                           "        return $classname::select( \$columns, \$filter, \$sort, \$limit );" . HH .
                           "    }" . HH . HH;
                //get
                $result .= "    /**" . HH .
                           "     * 查询数据对象: {$object_desc}的列表" . HH .
                           "     * " . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\" " . HH .
                           "     * " . HH .
                           "     * @param string \$sort 排序条件" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     1. id asc;" . HH .
                           "     *     2. name desc;" . HH .
                           "     * " . HH .
                           "     * @param string \$limit 分页数目:同Mysql limit语法" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *      0, 10" . HH .
                           "     * " . HH .
                           "     * @return array 数据对象:{object_desc}列表数组" . HH .
                           "     */" . HH;
                $result .= "    public function get(\$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, \$limit = null)" . HH .
                           "    {" . HH .
                           "        return $classname::get( \$filter, \$sort, \$limit );" . HH .
                           "    }" . HH . HH;
                //get_one
                $result .= "    /**" . HH .
                           "     * 查询得到单个数据对象: {$object_desc}实体" . HH .
                           "     * " . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\",\"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\" " . HH .
                           "     * " . HH .
                           "     * @param string \$sort 排序条件" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     1. id asc;" . HH .
                           "     *     2. name desc;" . HH .
                           "     * " . HH .
                           "     * @return object 单个数据对象: {$object_desc}实体" . HH .
                           "     */" . HH;
                $result .= "    public function get_one(\$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)" . HH .
                           "    {" . HH .
                           "        return $classname::get_one( \$filter, \$sort );" . HH .
                           "    }" . HH . HH;
                //get_by_id
                $result .= "    /**" . HH .
                           "     * 根据表ID主键获取指定的对象[ID对应的表列] " . HH .
                           "     * " . HH .
                           "     * @param string \$id  " . HH .
                           "     * @return object 单个数据对象: {$object_desc}实体" . HH .
                           "     */" . HH;
                $result .= "    public function get_by_id(\$id)" . HH .
                           "    {" . HH .
                           "        return $classname::get_by_id( \$id );" . HH .
                           "    }" . HH . HH;
                //count
                $result .= "    /**" . HH .
                           "     * 数据对象: {$object_desc}总计数" . HH .
                           "     * " . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\" " . HH .
                           "     * " . HH .
                           "     * @return int 数据对象: {$object_desc}总计数" . HH .
                           "     */" . HH;
                $result .= "    public function count(\$filter = null)" . HH .
                           "    {" . HH .
                           "        return $classname::count( \$filter );" . HH .
                           "    }" . HH . HH;
                //queryPage
                $result .= "    /**" . HH .
                           "     * 数据对象: {$object_desc}分页查询" . HH .
                           "     * " . HH .
                           "     * @param int \$startPoint  分页开始记录数" . HH .
                           "     * @param int \$endPoint    分页结束记录数" . HH .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件 " . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     0. \"id = 1, name = 'sky'\"" . HH .
                           "     *     1. array(\"id = 1\", \"name = 'sky'\") " . HH .
                           "     *     2. array(\"id\" => \"1\", \"name\" => \"sky\")" . HH .
                           "     *     3. 允许对象如new User(id = \"1\", name = \"green\");" . HH .
                           "     * " . HH .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\" " . HH .
                           "     * " . HH .
                           "     * @param string \$sort 排序条件" . HH .
                           "     * 默认为 id desc" . HH .
                           "     * 示例如下: " . HH .
                           "     * " . HH .
                           "     *     1. id asc;" . HH .
                           "     *     2. name desc;" . HH .
                           "     * " . HH .
                           "     * @return mixed 数据对象: {$object_desc}分页查询列表" . HH .
                           "     */" . HH . HH;
                $result .= "    public function queryPage(\$startPoint, \$endPoint, \$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)" . HH .
                           "    {" . HH .
                           "        return $classname::queryPage( \$startPoint, \$endPoint, \$filter, \$sort );" . HH .
                           "    }" . HH . HH;
                //sqlExecute
                $result .= "    /**" . HH .
                           "     * 直接执行SQL语句" . HH .
                           "     * " . HH .
                           "     * @return array" . HH .
                           "     * 返回" . HH .
                           "     *     1. 执行查询语句返回对象数组" . HH .
                           "     *     2. 执行更新和删除SQL语句返回执行成功与否的true|null" . HH .
                           "     */" . HH .
                           "    public function sqlExecute()" . HH .
                           "    {" . HH .
                           "        return self::dao()->sqlExecute( \"select * from \" . $classname::tablename(), $classname::cnames() );" . HH .
                           "    }" . HH . HH;
                //import
                $result .= "    /**" . HH .
                           "     * 批量上传{$object_desc}" . HH .
                           "     * " . HH .
                           "     * @param mixed \$upload_file <input name=\"upload_file\" type=\"file\">" . HH .
                           "     * @return void" . HH .
                           "     */" . HH .
                           "    public function import(\$files)" . HH .
                           "    {" . HH .
                           "        \$diffpart = date(\"YmdHis\");" . HH .
                           "        if (!empty(\$files[\"upload_file\"])) {" . HH .
                           "            \$tmptail    = explode('.', \$files[\"upload_file\"][\"name\"]);" . HH .
                           "            \$tmptail    = end(\$tmptail);" . HH .
                           "            \$uploadPath = GC::\$attachment_path . \"{$instance_name}\" . DS . \"import\" . DS . \"{$instance_name}\$diffpart . \$tmptail\";" . HH .
                           "            \$result     = UtilFileSystem::uploadFile( \$files, \$uploadPath );" . HH .
                           "            if (\$result && (\$result['success'] == true)) {" . HH .
                           "                if (array_key_exists('file_name', \$result)) {" . HH .
                           "                    \$arr_import_header = self::fieldsMean( {$classname}::tablename() );" . HH .
                           "                    \$data = UtilExcel::exceltoArray( \$uploadPath, \$arr_import_header );" . HH .
                           "                    \$result = false;" . HH .
                           "                    if (\$data) {" . HH .
                           "                        foreach (\$data as \${$instance_name}) {" . HH .
                           self::relationFieldImport($instance_name, $classname, $fieldInfo, "    ") .
                           "                            \${$instance_name} = new {$classname}(\${$instance_name});" . HH .
                           self::enumComment2KeyInExtService($instance_name, $fieldInfo, $tablename, "                    ") .
                           self::dataTimeConvert($instance_name, $fieldInfo, true, "    ") .
                           self::bitVal($instance_name, $fieldInfo, "    ") .
                           "                            \${$instance_name}_id = \${$instance_name}->getId();" . HH .
                           "                            if (!empty(\${$instance_name}_id)) {" . HH .
                           "                                \$had{$classname} = {$classname}::existByID( \${$instance_name}->getId() );" . HH .
                           "                                if (\$had{$classname}) {" . HH .
                           "                                    \$result = \${$instance_name}->update();" . HH .
                           "                                } else {" . HH .
                           "                                    \$result = \${$instance_name}->save();" . HH .
                           "                                }" . HH .
                           "                            } else {" . HH .
                           "                                \$result = \${$instance_name}->save();" . HH .
                           "                            }" . HH .
                           "                        }" . HH .
                           "                    }" . HH .
                           "                } else {" . HH .
                           "                    \$result = false;" . HH .
                           "                }" . HH .
                           "            } else {" . HH .
                           "                return \$result;" . HH .
                           "            }" . HH .
                           "        }" . HH .
                           "        return array(" . HH .
                           "            'success' => true," . HH .
                           "            'data'    => \$result" . HH .
                           "        );" . HH .
                           "    }" . HH . HH;
                 //export
                 $enumConvert   = self::enumKey2CommentInService($instance_name, $classname, $fieldInfo);
                 $datetimeShow  = self::datetimeShow($instance_name, $fieldInfo);
                 $bitShow       = self::bitShow($instance_name, $fieldInfo);
                 $specialResult = $enumConvert["normal"] .
                                  "        \$arr_output_header = self::fieldsMean( {$classname}::tablename() ); " . HH;
                 $relationFieldOutput = self::relationFieldOutput($instance_name, $classname, $fieldInfo);
                if (( !empty($relationFieldOutput) ) || ( !empty($enumConvert["normal"]) ) || ( !empty($datetimeShow) )) {
                    $specialResult .= "        if (\$data && count(\$data) > 0) {" . HH .
                                      "            foreach (\$data as \$$instance_name) {" . HH .
                                      $enumConvert["output"] .
                                      $relationFieldOutput .
                                      $datetimeShow .
                                      $bitShow .
                                      "            }" . HH .
                                      "        }" . HH;
                }
                 $result .= "    /**" . HH .
                            "     * 导出{$object_desc}" . HH .
                            "     * " . HH .
                            "     * @param mixed \$filter" . HH .
                            "     * @return void " . HH .
                            "     */" . HH .
                            "    public function export{$classname}(\$filter = null)" . HH .
                            "    {" . HH .
                            "        if (\$filter) {" . HH .
                            "            if (is_array(\$filter) || is_object(\$filter)) {" . HH .
                            "                \$filter = \$this->filtertoCondition( \$filter );" . HH .
                            "            }" . HH .
                            "        }" . HH .
                            "        \$data = $classname::get( \$filter );" . HH .
                            $specialResult .
                            "        unset(\$arr_output_header['updateTime'], \$arr_output_header['commitTime']);" . HH .
                            "        \$diffpart       = date(\"YmdHis\");" . HH .
                            "        \$outputFileName = Gc::\$attachment_path . \"export\" . DS . \"{$instance_name}\" . DS . \"\$diffpart.xls\"; " . HH .
                            "        UtilExcel::arraytoExcel( \$arr_output_header, \$data, \$outputFileName ); " . HH .
                            "        \$downloadPath   = Gc::\$attachment_url . \"export/{$instance_name}/\$diffpart.xls\"; " . HH .
                            "        return array(" . HH .
                            "            'success' => true," . HH .
                            "            'data'    => \$downloadPath" . HH .
                            "        ); " . HH .
                            "    }" . HH . HH;
                break;
            default:
                $result .= "class $service_classname extends ServiceBasic" . HH . "{" . HH;
                $result .= HH;
                break;
        }
        $result .= "}" . HH . HH;
        return $result;
    }


    /**
     * 数据对象冗余字段在服务端通过关联查询获取，界面不再提供输入
     * @param string $classname 数据对象类名
     * @param string $instance_name 实体变量
     * @param array $fieldInfo 表列信息列表
     */
    private static function redundancy_table_fields($classname, $instance_name, $fieldInfo)
    {
        $result = "";
        $redundancy_table_fields = self::$redundancy_table_fields[$classname];
        if (( is_array($redundancy_table_fields) ) && (count($redundancy_table_fields) > 0 )) {
            foreach ($redundancy_table_fields as $relation_classname => $redundancy_table_field) {
                $relation_instance_name = $relation_classname;
                $relation_instance_name = lcfirst($relation_instance_name);
                $realId  = DataObjectSpec::getRealIDColumnName($relation_classname);
                $result .= "        if (\${$instance_name}[\"{$realId}\"]) {" . HH .
                           "            \${$relation_instance_name} = $relation_classname::get_by_id( \${$instance_name}[\"{$realId}\"] );" . HH .
                           "            if (\${$relation_instance_name}) {" . HH;
                foreach ($redundancy_table_field as $relation_fieldname => $come) {
                    $result .= "                \${$instance_name}[\"$relation_fieldname\"] = \${$relation_instance_name}->{$come};" . HH;
                }
                $result .= "            }" . HH .
                           "        }" . HH;
            }
        }
        return $result;
    }

    /**
     * 导出关系列，将标识转换成易读的文字
     * @param mixed $instance_name 实体变量
     * @param mixed $classname 数据对象列名
     * @param mixed $fieldInfo 表列信息列表
     */
    private static function relationFieldOutput($instance_name, $classname, $fieldInfo)
    {
        $result = "";
        if (is_array(self::$relation_viewfield) && (count(self::$relation_viewfield) > 0)) {
            if (array_key_exists($classname, self::$relation_viewfield)) {
                $relationSpecs  = self::$relation_viewfield[$classname];
                $isTreeLevelHad = false;
                foreach ($fieldInfo as $fieldname => $field) {
                    if (array_key_exists($fieldname, $relationSpecs)) {
                        $relationShow = $relationSpecs[$fieldname];
                        foreach ($relationShow as $key => $value) {
                            $realId         = DataObjectSpec::getRealIDColumnName($key);
                            $show_fieldname = $value;
                            if ($realId != $fieldname) {
                                $show_fieldname .= "_" . $fieldname;
                                if (contain($show_fieldname, "_id")) {
                                    $show_fieldname = str_replace("_id", "", $show_fieldname);
                                }
                            }
                            // if ($show_fieldname == "name" ) $show_fieldname = strtolower($key) . "_" . $value;
                            $i_name = $key;
                            $i_name = lcfirst($i_name);
                            if (!array_key_exists("$show_fieldname", $fieldInfo)) {
                                $result .= "                \${$i_name}_instance = null;" . HH;
                                $result .= "                if (\${$instance_name}->$fieldname) {" . HH;
                                $result .= "                    \${$i_name}_instance = $key::get_by_id( \${$instance_name}->$fieldname );" . HH;
                                $result .= "                    \${$instance_name}['$fieldname'] = \${$i_name}_instance->$show_fieldname;" . HH;
                                $result .= "                }" . HH;
                            } else {
                                $result .= "                unset(\$arr_output_header[\"$fieldname\"]);" . HH;
                            }
                            $fieldInfos = self::$fieldInfos[self::getTablename($key)];
                            if (!$isTreeLevelHad) {
                                if (array_key_exists("parent_id", $fieldInfos) && array_key_exists("level", $fieldInfos)) {
                                    // $classNameField = self::getShowFieldName( $key );
                                    $field_comment  = $field["Comment"];
                                    $field_comment  = self::columnCommentKey($field_comment, $fieldname);
                                    $result .= "                if (\${$i_name}_instance) {" . HH .
                                               "                    \$level = \${$i_name}_instance->level;" . HH .
                                               "                    \${$instance_name}[\"{$i_name}ShowAll\"] = $key::{$i_name}ShowAll( \${$instance_name}->parent_id, \$level );" . HH .
                                               "                    \$" . $instance_name . "['$fieldname'] = \${$i_name}_instance->$value;" . HH .
                                               "                    \$pos = UtilArray::keyPosition( \$arr_output_header, \"$fieldname\" );" . HH .
                                               "                    UtilArray::insert( \$arr_output_header, \$pos + 1, array('{$i_name}ShowAll' => \"{$field_comment}[全]\") );" . HH .
                                               "                }" . HH;
                                    $isTreeLevelHad = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 导入关系列，将标识转换成易读的文字
     * @param mixed $instance_name 实体变量
     * @param mixed $classname 数据对象列名
     * @param mixed $fieldInfo 表列信息列表
     */
    private static function relationFieldImport($instance_name, $classname, $fieldInfo, $blankPre = "")
    {
        $result = "";
        if (is_array(self::$relation_viewfield) && (count(self::$relation_viewfield) > 0)) {
            if (array_key_exists($classname, self::$relation_viewfield)) {
                $relationSpecs  = self::$relation_viewfield[$classname];
                $isTreeLevelHad = false;
                foreach ($fieldInfo as $fieldname => $field) {
                    if (array_key_exists($fieldname, $relationSpecs)) {
                        $relationShow = $relationSpecs[$fieldname];
                        foreach ($relationShow as $key => $value) {
                            $i_name         = $key;
                            $show_fieldname = self::getShowFieldName($key);
                            $i_name         = lcfirst($i_name);
                            $fieldInfo_relation = self::$fieldInfos[self::getTablename($key)];
                            if (array_key_exists("parent_id", $fieldInfo_relation) && array_key_exists("level", $fieldInfo_relation)) {
                                if (!$isTreeLevelHad) {
                                    $classNameField = self::getShowFieldName($key);
                                    $field_comment  = $field["Comment"];
                                    $field_comment  = self::columnCommentKey($field_comment, $fieldname);
                                    $result .= $blankPre . "                        if (!is_numeric(\${$instance_name}[\"$fieldname\"])) {" . HH .
                                               $blankPre . "                            \${$i_name}_all = \${$instance_name}[\"{$field_comment}[全]\"];" . HH .
                                               $blankPre . "                            if (\${$i_name}_all) {" . HH .
                                               $blankPre . "                                \${$i_name}_all_arr = explode(\"->\", \${$i_name}_all);" . HH .
                                               $blankPre . "                                if (\${$i_name}_all_arr) {" . HH .
                                               $blankPre . "                                    \$level = count(\${$i_name}_all_arr);" . HH .
                                               $blankPre . "                                    switch (\$level) {" . HH .
                                               $blankPre . "                                        case 1:" . HH .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );" . HH .
                                               $blankPre . "                                            if (\${$i_name}_p )\${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};" . HH .
                                               $blankPre . "                                            break;" . HH .
                                               $blankPre . "                                        case 2:" . HH .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );" . HH .
                                               $blankPre . "                                            if (\${$i_name}_p) {" . HH .
                                               $blankPre . "                                                \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[1], \"level\" => 2,\"parent_id\" => \${$i_name}_p->{$fieldname}) );" . HH .
                                               $blankPre . "                                                if (\${$i_name}_p )\${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};" . HH .
                                               $blankPre . "                                            }" . HH .
                                               $blankPre . "                                            break;" . HH .
                                               $blankPre . "                                        case 3:" . HH .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );" . HH .
                                               $blankPre . "                                            if (\${$i_name}_p) {" . HH .
                                               $blankPre . "                                                \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[1], \"level\" => 2, \"parent_id\" => \${$i_name}_p->{$fieldname}) );" . HH .
                                               $blankPre . "                                                if (\${$i_name}_p) {" . HH .
                                               $blankPre . "                                                    \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[2], \"level\" => 3, \"parent_id\" => \${$i_name}_p->{$fieldname}) );" . HH .
                                               $blankPre . "                                                    if (\${$i_name}_p ) \${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};" . HH .
                                               $blankPre . "                                                }" . HH .
                                               $blankPre . "                                            }" . HH .
                                               $blankPre . "                                            break;" . HH .
                                               $blankPre . "                                       }" . HH .
                                               $blankPre . "                                  }" . HH .
                                               $blankPre . "                            }" . HH .
                                               $blankPre . "                        }" . HH;
                                    $isTreeLevelHad = true;
                                }
                            } else {
                                $result .= $blankPre . "                        if (!is_numeric(\${$instance_name}[\"$fieldname\"])) {" . HH;
                                $result .= $blankPre . "                            \${$i_name}_r = $key::get_one( \"{$show_fieldname} = '\" . \${$instance_name}[\"$fieldname\"] . \"'\" );" . HH;
                                $result .= $blankPre . "                            if (\${$i_name}_r ) \${$instance_name}[\"$fieldname\"] = \${$i_name}_r->$fieldname;" . HH;
                                $result .= $blankPre . "                        }" . HH;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 将表列为枚举类型的列用户能阅读的注释文字内容转换成需要存储在数据库里的值
     * @param string $instance_name 实体变量
     * @param array $fieldInfos 表列信息列表
     * @param string $tablename 表名称
     * @param string $blankPre 空白字符
     */
    private static function enumComment2KeyInExtService($instance_name, $fieldInfo, $tablename, $blankPre = "")
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            $datatype = self::comment_type($field["Type"]);
            if ($datatype == 'enum') {
                $enumclassname     = self::enumClassName($fieldname, $tablename);
                $enum_columnDefine = self::enumDefines($field["Comment"]);
                $result .= $blankPre . "        if (!{$enumclassname}::isEnumValue( \$" . $instance_name . "->" . $fieldname . " )) {" . HH .
                           $blankPre . "            \$" . $instance_name . "->" . $fieldname . " = " . $enumclassname . "::" . $fieldname . "ByShow( \$" . $instance_name . "->" . $fieldname . " );" . HH .
                           $blankPre . "        }" . HH;
            }
        }
        return $result;
    }

    /**
     * 将表列为枚举类型的列转换成用户能阅读的注释文字内容
     * @param string $enumclassname 枚举类名称
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */
    private static function enumKey2CommentInService($instance_name, $classname, $fieldInfo, $blankPre = "")
    {
        $result      = array("normal" => "","output" => "");
        $enumColumns = array();
        foreach ($fieldInfo as $fieldname => $field) {
            $datatype = self::comment_type($field["Type"]);
            if ($datatype == 'enum') {
                $enumColumns[]     = "'" . $fieldname . "'";
                $result["output"] .= "                if (\${$instance_name}->{$fieldname}Show) {" . HH .
                                     "                    \${$instance_name}['{$fieldname}'] = \${$instance_name}->{$fieldname}Show;" . HH .
                                     "                }" . HH;
            }
        }
        if (count($enumColumns) > 0) {
            $enumColumns       = implode(",", $enumColumns);
            $result["normal"] .= $blankPre . "        if (( !empty(\$data) ) && (count(\$data) > 0))" . HH .
                                 $blankPre . "        {" . HH .
                                 $blankPre . "            $classname::propertyShow( \$data, array($enumColumns) );" . HH .
                                 $blankPre . "        }" . HH;
        }
        return $result;
    }

    /**
     * 如果是日期时间存储成int的timestamp值，需要进行类型转换
     * @param string $instance_name 实体变量
     * @param array $fieldInfo 表列信息列表
     * @param bool $isImport 是否导入
     */
    private static function dataTimeConvert($instance_name, $fieldInfo, $isImport = false)
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            if (self::isNotColumnKeywork($fieldname)) {
                $datatype = self::column_type($field["Type"]);
                $field_comment = $field["Comment"];
                if (( $datatype == 'int' ) && (contains($field_comment, array("日期", "时间")) || contains($field_comment, array("date", "time")))) {
                    if ($isImport) {
                        $result .= "                            if (isset(\${$instance_name}->$fieldname) ) \${$instance_name}->$fieldname = UtilDateTime::dateToTimestamp( UtilExcel::exceltimtetophp( \${$instance_name}->$fieldname));" . HH;
                    } else {
                        $result .= "            if (isset(\${$instance_name}[\"$fieldname\"]) ) \${$instance_name}[\"$fieldname\"] = UtilDateTime::dateToTimestamp( \${$instance_name}[\"$fieldname\"] );" . HH;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * bit boolean值转换
     * @param string $instance_name 实体变量
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */
    private static function bitVal($instance_name, $fieldInfo, $blankPre = "")
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            if (self::isNotColumnKeywork($fieldname)) {
                $datatype = self::column_type($field["Type"]);
                if ($datatype == 'bit') {
                    $result .= $blankPre . "                        if (\${$instance_name}->$fieldname == \"是\" ) \$$instance_name->$fieldname = 1; else \$$instance_name->$fieldname = 0;" . HH;
                }
            }
        }
        return $result;
    }

    /**
     * bit boolean输出显示
     * @param string $instance_name 实体变量
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */
    private static function bitShow($instance_name, $fieldInfo, $blankPre = "")
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            if (self::isNotColumnKeywork($fieldname)) {
                $datatype = self::column_type($field["Type"]);
                if ($datatype == 'bit') {
                    $result .= $blankPre . "                if (\${$instance_name}->$fieldname == 1 ) \$$instance_name->$fieldname = \"是\"; else \$$instance_name->$fieldname = \"否\";" . HH;
                }
            }
        }
        return $result;
    }

    /**
     * 时间日期输出显示
     * @param string $instance_name 实体变量
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */
    private static function datetimeShow($instance_name, $fieldInfo, $blankPre = "")
    {
        $result = "";
        foreach ($fieldInfo as $fieldname => $field) {
            if (self::isNotColumnKeywork($fieldname)) {
                $datatype = self::column_type($field["Type"]);
                $field_comment = $field["Comment"];
                if (( $datatype == 'int' ) && (contains($field_comment, array("日期", "时间")) || contains($field_comment, array("date", "time")))) {
                    $result .= $blankPre . "                if (\${$instance_name}->{$fieldname} ) \${$instance_name}[\"$fieldname\"] = UtilDateTime::timestampToDateTime( \${$instance_name}->{$fieldname} );" . HH;
                }
            }
        }
        return $result;
    }

    /**
     * 从表名称获取服务的类名。
     * @param string $tablename 表名称
     * @return string 返回对象的类名
     */
    private static function getServiceClassname($tablename)
    {
        $classnameSplit = explode("_", $tablename);
        $classname      = 'Service' . ucfirst($classnameSplit[count($classnameSplit) - 1]);
        return $classname;
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码
     */
    private static function saveServiceDefineToDir($tablename, $definePhpFileContent)
    {
        $filename         = self::getServiceClassname($tablename) . ".php";
        $service_dir_full = self::$service_dir_full;
        $relative_path    = str_replace(self::$save_dir, "", $service_dir_full . $filename);
        $classname        = self::getClassname($tablename);
        AutoCodePreviewReport::$service_files[$classname] = $relative_path;
        return self::saveDefineToDir($service_dir_full, $filename, $definePhpFileContent);
    }
}
