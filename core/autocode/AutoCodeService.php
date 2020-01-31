<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-服务类<br/>
 +---------------------------------<br/>
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
     * 服务类生成定义的方式<br/>
     * 1.继承具有标准方法的Service。<br/>
     * 2.生成标准方法的Service。<br/>
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
        if ( !UtilString::is_utf8( self::$service_dir_full ) ) {
            self::$service_dir_full = UtilString::gbk2utf8( self::$service_dir_full );
        }
        // self::$service_dir_full = self::$save_dir . Gc::$module_root . DS  . self::$app_dir . DS . self::$dir_src . DS . self::$service_dir . DS;
        self::$service_dir_full = self::$save_dir . Gc::$module_root . DS  . "admin" . DS . self::$dir_src . DS . self::$service_dir . DS;

        self::init();

        if ( self::$isOutputCss ) self::$showReport .= UtilCss::form_css() . "\r\n";

        switch (self::$type) {
           case 1:
             self::$showReport .= AutoCodeFoldHelper::foldEffectCommon( "Content_22" );
             self::$showReport .= "<font color='#237319'>生成继承具有标准方法的Service文件导出↓</font></a>";
             self::$showReport .= '<div id="Content_22" style="display:none;">';
             break;
           case 2:
             self::$showReport .= AutoCodeFoldHelper::foldEffectCommon( "Content_21" );
             self::$showReport .= "<font color='#237319'>标准方法的Service文件导出↓</font></a><br/>";
             self::$showReport .= '<div id="Content_21" style="display:none;">';
             break;
        }
        $link_service_dir_href = "file:///" .str_replace("\\", "/", self::$service_dir_full);
        self::$showReport     .= "<font color='#AAA'>存储路径:<a target='_blank' href='" . $link_service_dir_href . "'>" . self::$service_dir_full."</a></font><br/><br/>";

        $tableList = self::tableListByTable_names( $table_names );
        foreach ($tableList as $tablename) {
            $definePhpFileContent = self::tableToServiceDefine( $tablename );
            if ( isset($definePhpFileContent) && ( !empty($definePhpFileContent) ) ) {
               $classname = self::saveServiceDefineToDir( $tablename, $definePhpFileContent );
               self::$showReport .= "生成导出完成:$tablename => $classname!<br/>";
            } else {
               self::$showReport .= $definePhpFileContent . "<br/>";
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
        $result = self::createManageService( $table_names );
        $section_define  = $result["section_define"];
        $section_content = $result["section_content"];
        $e_result = "<?php\r\n" .
                    "/**\r\n" .
                    " +---------------------------------------<br/>\r\n" .
                    " * 服务类:所有Service的管理类<br/>\r\n" .
                    " +---------------------------------------\r\n" .
                    " * @category $category\r\n" .
                    " * @package $package\r\n" .
                    " * @author $author\r\n" .
                    " */\r\n" .
                    "class Manager_Service extends Manager\r\n" .
                    "{\r\n" . $section_define . "\r\n" . $section_content . "}\r\n";
        self::saveDefineToDir( self::$service_dir_full, "Manager_Service.php", $e_result );
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

        $tableList = self::tableListByTable_names( $table_names );
        foreach ($tableList as $tablename) {
            $table_comment = self::tableCommentKey( $tablename );
            $classname     = self::getClassname( $tablename );
            $classname{0}  = strtolower($classname{0});
            $service_classname = self::getServiceClassname( $tablename );
            $section_define   .= "    private static \$" . $classname . "Service;\r\n";
            $section_content  .= "    /**\r\n" .
                                 "     * 提供服务: " . $table_comment . "\r\n" .
                                 "     */\r\n";
            $section_content  .= "    public static function " . $classname . "Service()\r\n" .
                                 "    {\r\n" .
                                 "        if ( self::\$" . $classname . "Service == null ) {\r\n" .
                                 "            self::\$" . $classname . "Service = new $service_classname();\r\n" .
                                 "        }\r\n" .
                                 "        return self::\$" . $classname . "Service;\r\n" .
                                 "    }\r\n\r\n";
        }
        $result["section_define"]  = $section_define;
        $result["section_content"] = $section_content;
        return $result;
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value = "", $title = "", $inputArr = null, $more_content = "" )
    {
        $inputArr = array(
            "2" => "生成标准方法的Service",
            "1" => "继承具有标准方法的Service",
        );
        parent::UserInput( "一键生成服务类定义层", $inputArr, $default_value);
    }

    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfos 表列信息列表
     */
    private static function tableToServiceDefine($tablename)
    {
        if ( array_key_exists($tablename, self::$fieldInfos) ) {
            $fieldInfo = self::$fieldInfos[$tablename];
        } else {
            $fieldInfo = self::$fieldInfos;
        }
        $result            = "<?php\r\n";
        $classname         = self::getClassname( $tablename );
        $instance_name     = self::getInstancename( $tablename );
        $service_classname = self::getServiceClassname( $tablename );
        $object_desc       = "";
        $object_desc       = self::tableCommentKey( $tablename );
        if ( self::$tableInfoList != null && count(self::$tableInfoList) > 0 && array_key_exists("$tablename", self::$tableInfoList) )
        {
            $table_comment = "服务类: " . $object_desc;
        } else {
            $table_comment = "关于服务类$classname的描述";
        }
        $category = Gc::$appName;
        $author   = self::$author;
        $package  = self::$package;
        $result  .= "/**\r\n" .
                    " +---------------------------------------<br/>\r\n" .
                    " * $table_comment<br/>\r\n" .
                    " +---------------------------------------\r\n" .
                    " * @category $category\r\n" .
                    " * @package $package\r\n";
        $result .= " * @author $author\r\n" .
                   " */\r\n";
        switch (self::$type) {
            case 2:
                $result .= "class $service_classname extends Service implements IServiceBasic \r\n{\r\n";
                //save
                $result .= "    /**\r\n" .
                           "     * 保存数据对象: {$object_desc}\r\n" .
                           "     * @param array|DataObject \$$instance_name\r\n" .
                           "     * @return int 保存对象记录的ID标识号\r\n" .
                           "     */\r\n";
                $result .= "    public function save(\$$instance_name)\r\n" .
                           "    {\r\n" .
                           "        if ( is_array(\$$instance_name) ) {\r\n" .
                           "            \$$instance_name = new $classname(\$$instance_name);\r\n" .
                           "        }\r\n" .
                           "        if ( \$$instance_name instanceof $classname ) {\r\n" .
                           "            return \$" . $instance_name . "->save();\r\n" .
                           "        } else {\r\n" .
                           "            return false;\r\n" .
                           "        }\r\n" .
                           "    }\r\n\r\n";
                //update
                $result .= "    /**\r\n" .
                           "     * 更新数据对象 :{$object_desc}\r\n" .
                           "     * @param array|DataObject \$$instance_name\r\n" .
                           "     * @return boolen 是否更新成功；true为操作正常\r\n" .
                           "     */\r\n";
                $result .= "    public function update(\$$instance_name)\r\n" .
                           "    {\r\n" .
                           "        if ( is_array(\$$instance_name) ) {\r\n" .
                           "            \$$instance_name = new $classname(\$$instance_name);\r\n" .
                           "        }\r\n" .
                           "        if ( \$$instance_name instanceof $classname ) {\r\n" .
                           "            return \$" . $instance_name . "->update();\r\n" .
                           "        } else {\r\n" .
                           "            return false;\r\n" .
                           "        }\r\n" .
                           "    }\r\n\r\n";
                //deleteByID
                $result .= "    /**\r\n" .
                           "     * 由标识删除指定ID数据对象 :{$object_desc}\r\n" .
                           "     * @param int \$id 数据对象: {$object_desc}标识\r\n" .
                           "     * @return boolen 是否删除成功；true为操作正常\r\n" .
                           "     */\r\n";
                $result .= "    public function deleteByID(\$id)\r\n" .
                           "    {\r\n" .
                           "        return $classname::deleteByID( \$id );\r\n" .
                           "    }\r\n\r\n";
                //deleteByIds
                $result .= "    /**\r\n" .
                           "     * 根据主键删除数据对象: {$object_desc}的多条数据记录\r\n" .
                           "     * @param array|string \$ids 数据对象编号\r\n" .
                           "     * 形式如下:\r\n" .
                           "     * 1. array:array(1, 2, 3, 4, 5)\r\n" .
                           "     * 2. 字符串:1, 2, 3, 4 \r\n" .
                           "     * @return boolen 是否删除成功；true为操作正常\r\n" .
                           "     */\r\n";
                $result .= "    public function deleteByIds(\$ids)\r\n" .
                           "    {\r\n" .
                           "        return $classname::deleteByIds( \$ids );\r\n" .
                           "    }\r\n\r\n";
                //increment
                $result .= "    /**\r\n" .
                           "     * 对数据对象: {$object_desc}的属性进行递增\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/>\r\n" .
                           "     * @param string \$property_name 属性名称 \r\n" .
                           "     * @param int \$incre_value 递增数 \r\n" .
                           "     * @return boolen 是否操作成功；true为操作正常\r\n" .
                           "     */\r\n";
                $result .= "    public function increment(\$filter = null, \$property_name, \$incre_value)\r\n" .
                           "    {\r\n" .
                           "        return $classname::increment( \$filter, \$property_name, \$incre_value );\r\n" .
                           "    }\r\n\r\n";
                //decrement
                $result .= "    /**\r\n" .
                           "     * 对数据对象: {$object_desc}的属性进行递减\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/>\r\n" .
                           "     * @param string \$property_name 属性名称 \r\n" .
                           "     * @param int \$decre_value 递减数 \r\n" .
                           "     * @return boolen 是否操作成功；true为操作正常\r\n" .
                           "     */\r\n";
                $result .= "    public function decrement(\$filter = null, \$property_name, \$decre_value)\r\n" .
                           "    {\r\n" .
                           "        return $classname::decrement( \$filter, \$property_name, \$decre_value );\r\n" .
                           "    }\r\n\r\n";
                //select
                $result .= "    /**\r\n" .
                           "     * 查询数据对象: {$object_desc}需显示属性的列表\r\n" .
                           "     * @param string \$columns 指定的显示属性，同SQL语句中的Select部分。 \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *    id, name, commitTime\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/> \r\n" .
                           "     * @param string \$sort 排序条件<br/>\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      1. id asc;<br/>\r\n" .
                           "     *      2. name desc;<br/>\r\n" .
                           "     * @param string \$limit 分页数目:同Mysql limit语法\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      0, 10<br/>\r\n" .
                           "     * @return 数据对象: {$object_desc}列表数组\r\n" .
                           "     */\r\n";
                $result .= "    public function select(\$columns, \$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, \$limit = null)\r\n" .
                           "    {\r\n" .
                           "        return $classname::select( \$columns, \$filter, \$sort, \$limit );\r\n" .
                           "    }\r\n\r\n";
                //get
                $result .= "    /**\r\n" .
                           "     * 查询数据对象: {$object_desc}的列表\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/> \r\n" .
                           "     * @param string \$sort 排序条件<br/>\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      1. id asc;<br/>\r\n" .
                           "     *      2. name desc;<br/>\r\n" .
                           "     * @param string \$limit 分页数目:同Mysql limit语法\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      0, 10<br/>\r\n" .
                           "     * @return 数据对象:{object_desc}列表数组\r\n" .
                           "     */\r\n";
                $result .= "    public function get(\$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID, \$limit = null)\r\n" .
                           "    {\r\n" .
                           "        return $classname::get( \$filter, \$sort, \$limit );\r\n" .
                           "    }\r\n\r\n";
                //get_one
                $result .= "    /**\r\n" .
                           "     * 查询得到单个数据对象: {$object_desc}实体\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\",\"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/> \r\n" .
                           "     * @param string \$sort 排序条件<br/>\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      1. id asc;<br/>\r\n" .
                           "     *      2. name desc;<br/>\r\n" .
                           "     * @return 单个数据对象: {$object_desc}实体\r\n" .
                           "     */\r\n";
                $result .= "    public function get_one(\$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)\r\n" .
                           "    {\r\n" .
                           "        return $classname::get_one( \$filter, \$sort );\r\n" .
                           "    }\r\n\r\n";
                //get_by_id
                $result .= "    /**\r\n" .
                           "     * 根据表ID主键获取指定的对象[ID对应的表列] \r\n" .
                           "     * @param string \$id  \r\n" .
                           "     * @return 单个数据对象: {$object_desc}实体\r\n" .
                           "     */\r\n";
                $result .= "    public function get_by_id(\$id)\r\n" .
                           "    {\r\n" .
                           "        return $classname::get_by_id( \$id );\r\n" .
                           "    }\r\n\r\n";
                //count
                $result .= "    /**\r\n" .
                           "     * 数据对象: {$object_desc}总计数\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/> \r\n" .
                           "     * @return 数据对象: {$object_desc}总计数\r\n" .
                           "     */\r\n";
                $result .= "    public function count(\$filter = null)\r\n" .
                           "    {\r\n" .
                           "        return $classname::count( \$filter );\r\n" .
                           "    }\r\n\r\n";
                //queryPage
                $result .= "    /**\r\n" .
                           "     * 数据对象: {$object_desc}分页查询\r\n" .
                           "     * @param int \$startPoint  分页开始记录数\r\n" .
                           "     * @param int \$endPoint    分页结束记录数\r\n" .
                           "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     * 0. \"id = 1, name = 'sky'\"<br/>\r\n" .
                           "     * 1. array(\"id = 1\", \"name = 'sky'\")<br/> \r\n" .
                           "     * 2. array(\"id\" => \"1\", \"name\" => \"sky\")<br/>\r\n" .
                           "     * 3. 允许对象如new User(id = \"1\", name = \"green\");<br/>\r\n" .
                           "     * 默认:SQL Where条件子语句。如: \"( id = 1 and name = 'sky' ) or ( name like '%sky%' )\"<br/> \r\n" .
                           "     * @param string \$sort 排序条件<br/>\r\n" .
                           "     * 默认为 id desc<br/>\r\n" .
                           "     * 示例如下: <br/>\r\n" .
                           "     *      1. id asc;<br/>\r\n" .
                           "     *      2. name desc;<br/>\r\n" .
                           "     * @return 数据对象: {$object_desc}分页查询列表\r\n" .
                           "     */\r\n\r\n";
                $result .= "    public function queryPage(\$startPoint, \$endPoint, \$filter = null, \$sort = Crud_SQL::SQL_ORDER_DEFAULT_ID)\r\n" .
                           "    {\r\n" .
                           "        return $classname::queryPage( \$startPoint, \$endPoint, \$filter, \$sort );\r\n" .
                           "    }\r\n\r\n";
                //sqlExecute
                $result .= "    /**\r\n" .
                           "     * 直接执行SQL语句\r\n" .
                           "     * @return array\r\n" .
                           "     *  1. 执行查询语句返回对象数组\r\n" .
                           "     *  2. 执行更新和删除SQL语句返回执行成功与否的true|null\r\n" .
                           "     */\r\n" .
                           "    public function sqlExecute()\r\n" .
                           "    {\r\n" .
                           "        return self::dao()->sqlExecute( \"select * from \" . $classname::tablename(), $classname::classname_static() );\r\n" .
                           "    }\r\n\r\n";
                //import
                $result .= "    /**\r\n" .
                           "     * 批量上传{$object_desc}\r\n" .
                           "     * @param mixed \$upload_file <input name=\"upload_file\" type=\"file\">\r\n" .
                           "     */\r\n" .
                           "    public function import(\$files)\r\n" .
                           "    {\r\n" .
                           "        \$diffpart = date(\"YmdHis\");\r\n" .
                           "        if ( !empty(\$files[\"upload_file\"]) ) {\r\n" .
                           "            \$tmptail = explode('.', \$files[\"upload_file\"][\"name\"]);\r\n" .
                           "            \$tmptail = end(\$tmptail);\r\n" .
                           "            \$uploadPath = GC::\$attachment_path . \"{$instance_name}\" . DS . \"import\" . DS . \"{$instance_name}\$diffpart . \$tmptail\";\r\n" .
                           "            \$result = UtilFileSystem::uploadFile( \$files, \$uploadPath );\r\n" .
                           "            if ( \$result && (\$result['success'] == true) ) {\r\n" .
                           "                if ( array_key_exists('file_name', \$result) ) {\r\n" .
                           "                    \$arr_import_header = self::fieldsMean( {$classname}::tablename() );\r\n" .
                           "                    \$data = UtilExcel::exceltoArray( \$uploadPath, \$arr_import_header );\r\n" .
                           "                    \$result = false;\r\n" .
                           "                    if ( \$data ) {\r\n" .
                           "                        foreach (\$data as \${$instance_name}) {\r\n" .
                           self::relationFieldImport( $instance_name, $classname, $fieldInfo, "    " ).
                           "                            \${$instance_name} = new {$classname}(\${$instance_name});\r\n" .
                           self::enumComment2KeyInExtService( $instance_name, $fieldInfo, $tablename, "                    ").
                           self::dataTimeConvert( $instance_name, $fieldInfo, true, "    " ).
                           self::bitVal( $instance_name, $fieldInfo, "    " ).
                           "                            \${$instance_name}_id = \${$instance_name}->getId();\r\n" .
                           "                            if ( !empty(\${$instance_name}_id) ) {\r\n" .
                           "                                \$had{$classname} = {$classname}::existByID(\${$instance_name}->getId());\r\n" .
                           "                                if ( \$had{$classname} ) {\r\n" .
                           "                                    \$result = \${$instance_name}->update();\r\n" .
                           "                                } else {\r\n" .
                           "                                    \$result=\${$instance_name}->save();\r\n" .
                           "                                }\r\n" .
                           "                            } else {\r\n" .
                           "                                \$result = \${$instance_name}->save();\r\n" .
                           "                            }\r\n" .
                           "                        }\r\n" .
                           "                    }\r\n" .
                           "                } else {\r\n" .
                           "                    \$result=false;\r\n" .
                           "                }\r\n" .
                           "            }else{\r\n" .
                           "                return \$result;\r\n" .
                           "            }\r\n" .
                           "        }\r\n" .
                           "        return array(\r\n" .
                           "            'success' => true,\r\n" .
                           "            'data'  => \$result\r\n" .
                           "        );\r\n" .
                           "    }\r\n\r\n";
                 //export
                 $enumConvert   = self::enumKey2CommentInService( $instance_name, $classname, $fieldInfo );
                 $datetimeShow  = self::datetimeShow( $instance_name, $fieldInfo );
                 $bitShow       = self::bitShow( $instance_name, $fieldInfo );
                 $specialResult = $enumConvert["normal"] .
                                  "        \$arr_output_header = self::fieldsMean( {$classname}::tablename() ); \r\n";
                 $relationFieldOutput = self::relationFieldOutput( $instance_name, $classname, $fieldInfo );
                 if ( ( !empty($relationFieldOutput) ) || ( !empty($enumConvert["normal"]) ) || ( !empty($datetimeShow) ) ) {
                     $specialResult .= "        foreach (\$data as \$$instance_name) {\r\n" .
                                       $enumConvert["output"] .
                                       $relationFieldOutput .
                                       $datetimeShow .
                                       $bitShow .
                                       "        }\r\n";
                 }
                 $result .= "    /**\r\n" .
                            "     * 导出{$object_desc}\r\n" .
                            "     * @param mixed \$filter\r\n" .
                            "     */\r\n" .
                            "    public function export{$classname}(\$filter = null)\r\n" .
                            "    {\r\n" .
                            "        if ( \$filter ) \$filter = \$this->filtertoCondition( \$filter );\r\n" .
                            "        \$data = $classname::get( \$filter );\r\n" .
                            $specialResult .
                            "        unset(\$arr_output_header['updateTime'], \$arr_output_header['commitTime']);\r\n" .
                            "        \$diffpart = date(\"YmdHis\");\r\n" .
                            "        \$outputFileName = Gc::\$attachment_path . \"export\" . DS . \"{$instance_name}\" . DS . \"\$diffpart.xls\"; \r\n" .
                            "        UtilExcel::arraytoExcel( \$arr_output_header, \$data, \$outputFileName, false ); \r\n" .
                            "        \$downloadPath   = Gc::\$attachment_url . \"export/{$instance_name}/\$diffpart.xls\"; \r\n" .
                            "        return array(\r\n" .
                            "            'success' => true,\r\n" .
                            "            'data'    => \$downloadPath\r\n" .
                            "        ); \r\n" .
                            "    }\r\n\r\n";
                break;
            default:
                $result .= "class $service_classname extends ServiceBasic\r\n{\r\n";
                $result.= "\r\n";
                break;
        }
        $result .= "}\r\n\r\n";
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
        if ( ( is_array($redundancy_table_fields) ) && ( count($redundancy_table_fields) > 0 ) ) {
            foreach ($redundancy_table_fields as $relation_classname => $redundancy_table_field) {
                $relation_instance_name    = $relation_classname;
                $relation_instance_name{0} = strtolower($relation_instance_name{0});
                $realId  = DataObjectSpec::getRealIDColumnName( $relation_classname );
                $result .= "        if ( \${$instance_name}[\"{$realId}\"] ) {\r\n" .
                           "            \${$relation_instance_name} = $relation_classname::get_by_id( \${$instance_name}[\"{$realId}\"] );\r\n" .
                           "            if ( \${$relation_instance_name} ) {\r\n";
                foreach ($redundancy_table_field as $relation_fieldname => $come) {
                    $result .= "                \${$instance_name}[\"$relation_fieldname\"] = \${$relation_instance_name}->{$come};\r\n";
                }
                $result .= "            }\r\n" .
                           "        }\r\n";
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
        if ( is_array(self::$relation_viewfield) && ( count(self::$relation_viewfield) > 0 ) )
        {
            if ( array_key_exists($classname, self::$relation_viewfield) ) {
                $relationSpecs  = self::$relation_viewfield[$classname];
                $isTreeLevelHad = false;
                foreach ($fieldInfo as $fieldname => $field) {
                    if ( array_key_exists($fieldname, $relationSpecs) ) {
                        $relationShow = $relationSpecs[$fieldname];
                        foreach ($relationShow as $key => $value) {
                            $realId         = DataObjectSpec::getRealIDColumnName( $key );
                            $show_fieldname = $value;
                            if ( $realId != $fieldname ) {
                                $show_fieldname .= "_" . $fieldname;
                                if ( contain($show_fieldname, "_id") ) {
                                    $show_fieldname = str_replace("_id", "", $show_fieldname);
                                }
                            }
                            if ( $show_fieldname == "name" ) $show_fieldname = strtolower($key) . "_" . $value;
                            $i_name    = $key;
                            $i_name{0} = strtolower($i_name{0});
                            if ( !array_key_exists("$show_fieldname", $fieldInfo) ) {
                                $result .= "            \${$i_name}_instance = null;\r\n";
                                $result .= "            if ( \${$instance_name}->$fieldname ) {\r\n";
                                $result .= "                \${$i_name}_instance = $key::get_by_id( \${$instance_name}->$fieldname );\r\n";
                                $result .= "                \${$instance_name}['$fieldname'] = \${$i_name}_instance->$show_fieldname;\r\n";
                                $result .= "            }\r\n";
                            } else {
                                $result .= "            unset(\$arr_output_header[\"$fieldname\"]);\r\n";
                            }
                            $fieldInfos = self::$fieldInfos[self::getTablename( $key )];
                            if ( !$isTreeLevelHad ) {
                                if ( array_key_exists("parent_id", $fieldInfos) && array_key_exists("level", $fieldInfos) ) {
                                    $classNameField = self::getShowFieldName( $key );
                                    $field_comment  = $field["Comment"];
                                    $field_comment  = self::columnCommentKey($field_comment,$fieldname);
                                    $result .= "            if ( \${$i_name}_instance ) {\r\n" .
                                               "                \$level = \${$i_name}_instance->level;\r\n" .
                                               "                \${$instance_name}[\"{$i_name}ShowAll\"] = \$this->{$i_name}ShowAll( \${$instance_name}->parent_id, \$level );\r\n" .
                                               "                \$" .$instance_name."['$fieldname'] = \${$i_name}_instance->$value;\r\n" .
                                               "                \$pos = UtilArray::keyPosition( \$arr_output_header, \"$fieldname\" );\r\n" .
                                               "                UtilArray::insert( \$arr_output_header, \$pos + 1, array('{$i_name}ShowAll' => \"{$field_comment}[全]\") );\r\n" .
                                               "            }\r\n";
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
        if ( is_array(self::$relation_viewfield) && ( count(self::$relation_viewfield) > 0 ) )
        {
            if ( array_key_exists($classname, self::$relation_viewfield) ) {
                $relationSpecs  = self::$relation_viewfield[$classname];
                $isTreeLevelHad = false;
                foreach ($fieldInfo as $fieldname => $field){
                    if ( array_key_exists($fieldname, $relationSpecs) ) {
                        $relationShow = $relationSpecs[$fieldname];
                        foreach ($relationShow as $key => $value) {
                            $i_name         = $key;
                            $show_fieldname = self::getShowFieldName( $key );
                            $i_name{0}      = strtolower($i_name{0});
                            $fieldInfo_relation = self::$fieldInfos[self::getTablename($key)];
                            if ( array_key_exists("parent_id", $fieldInfo_relation) && array_key_exists("level", $fieldInfo_relation) ) {
                                if ( !$isTreeLevelHad ) {
                                    $classNameField = self::getShowFieldName( $key );
                                    $field_comment  = $field["Comment"];
                                    $field_comment  = self::columnCommentKey( $field_comment, $fieldname );
                                    $result .= $blankPre . "                        if ( !is_numeric(\${$instance_name}[\"$fieldname\"]) ) {\r\n" .
                                               $blankPre . "                            \${$i_name}_all = \${$instance_name}[\"{$field_comment}[全]\"];\r\n" .
                                               $blankPre . "                            if ( \${$i_name}_all ) {\r\n" .
                                               $blankPre . "                                \${$i_name}_all_arr = explode(\"->\", \${$i_name}_all);\r\n" .
                                               $blankPre . "                                if ( \${$i_name}_all_arr ) {\r\n" .
                                               $blankPre . "                                    \$level = count(\${$i_name}_all_arr);\r\n" .
                                               $blankPre . "                                    switch (\$level) {\r\n" .
                                               $blankPre . "                                        case 1:\r\n" .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );\r\n" .
                                               $blankPre . "                                            if ( \${$i_name}_p )\${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};\r\n" .
                                               $blankPre . "                                            break;\r\n" .
                                               $blankPre . "                                        case 2:\r\n" .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );\r\n" .
                                               $blankPre . "                                            if ( \${$i_name}_p ) {\r\n" .
                                               $blankPre . "                                                \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[1], \"level\" => 2,\"parent_id\" => \${$i_name}_p->{$fieldname}) );\r\n" .
                                               $blankPre . "                                                if ( \${$i_name}_p )\${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};\r\n" .
                                               $blankPre . "                                            }\r\n" .
                                               $blankPre . "                                            break;\r\n" .
                                               $blankPre . "                                        case 3:\r\n" .
                                               $blankPre . "                                            \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[0], \"level\" => 1) );\r\n" .
                                               $blankPre . "                                            if ( \${$i_name}_p ) {\r\n" .
                                               $blankPre . "                                                \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[1], \"level\" => 2, \"parent_id\" => \${$i_name}_p->{$fieldname}) );\r\n" .
                                               $blankPre . "                                                if ( \${$i_name}_p ) {\r\n" .
                                               $blankPre . "                                                    \${$i_name}_p = {$key}::get_one( array(\"{$show_fieldname}\" => \${$i_name}_all_arr[2], \"level\" => 3, \"parent_id\" => \${$i_name}_p->{$fieldname}) );\r\n" .
                                               $blankPre . "                                                    if ( \${$i_name}_p ) \${$instance_name}[\"{$fieldname}\"] = \${$i_name}_p->{$fieldname};\r\n" .
                                               $blankPre . "                                                }\r\n" .
                                               $blankPre . "                                            }\r\n" .
                                               $blankPre . "                                            break;\r\n" .
                                               $blankPre . "                                       }\r\n" .
                                               $blankPre . "                                  }\r\n" .
                                               $blankPre . "                            }\r\n" .
                                               $blankPre . "                        }\r\n";
                                    $isTreeLevelHad = true;
                                }
                            } else {
                                $result .= $blankPre . "                        if ( !is_numeric(\${$instance_name}[\"$fieldname\"]) ) {\r\n";
                                $result .= $blankPre . "                            \${$i_name}_r = $key::get_one( \"{$show_fieldname} = '\" .\${$instance_name}[\"$fieldname\"].\"'\" );\r\n";
                                $result .= $blankPre . "                            if ( \${$i_name}_r ) \${$instance_name}[\"$fieldname\"] = \${$i_name}_r->$fieldname;\r\n";
                                $result .= $blankPre . "                        }\r\n";
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
            if ( $datatype == 'enum' ) {
                $enumclassname     = self::enumClassName($fieldname,$tablename);
                $enum_columnDefine = self::enumDefines($field["Comment"]);
                $result .= $blankPre . "        if ( !{$enumclassname}::isEnumValue( \$" . $instance_name . "->" . $fieldname . " ) ) {\r\n" .
                           $blankPre . "            \$" . $instance_name . "->" . $fieldname . " = " . $enumclassname . "::" . $fieldname . "ByShow( \$" . $instance_name . "->" . $fieldname . " );\r\n" .
                           $blankPre . "        }\r\n";
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
            if ( $datatype == 'enum' ) {
                $enumColumns[]     = "'" . $fieldname . "'";
                $result["output"] .= "            if ( \${$instance_name}->{$fieldname}Show ) {\r\n" .
                                     "                \${$instance_name}['{$fieldname}'] = \${$instance_name}->{$fieldname}Show;\r\n" .
                                     "            }\r\n";
            }
        }
        if ( count($enumColumns) > 0) {
            $enumColumns       = implode(",", $enumColumns);
            $result["normal"] .= $blankPre . "        if ( ( !empty(\$data) ) && ( count(\$data) > 0 ) )\r\n" .
                                 $blankPre . "        {\r\n" .
                                 $blankPre . "            $classname::propertyShow( \$data, array($enumColumns) );\r\n" .
                                 $blankPre . "        }\r\n";
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
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::column_type($field["Type"]);
                $field_comment = $field["Comment"];
                if ( ($datatype == 'int') && (contains($field_comment, array("日期", "时间")) || contains($field_comment, array("date", "time"))) )
                {
                    if ( $isImport ) {
                        $result .= "                            if ( isset(\${$instance_name}->$fieldname) ) \${$instance_name}->$fieldname = UtilDateTime::dateToTimestamp( UtilExcel::exceltimtetophp( \${$instance_name}->$fieldname ) );\r\n";
                    } else {
                        $result .= "            if ( isset(\${$instance_name}[\"$fieldname\"]) ) \${$instance_name}[\"$fieldname\"] = UtilDateTime::dateToTimestamp( \${$instance_name}[\"$fieldname\"] );\r\n";
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
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::column_type($field["Type"]);
                if ( $datatype == 'bit' )
                    $result .= $blankPre . "                        if ( \${$instance_name}->$fieldname == \"是\" ) \$$instance_name->$fieldname = true; else \$$instance_name->$fieldname = false;\r\n";
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
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::column_type( $field["Type"] );
                if ( $datatype == 'bit' )
                    $result .= $blankPre . "            if ( \${$instance_name}->$fieldname == 1 ) \$$instance_name->$fieldname = \"是\"; else \$$instance_name->$fieldname = \"否\";\r\n";
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
            if ( self::isNotColumnKeywork( $fieldname ) ) {
                $datatype = self::column_type($field["Type"]);
                $field_comment = $field["Comment"];
                if ( ($datatype == 'int' ) && ( contains($field_comment, array("日期", "时间") ) || contains($field_comment, array("date", "time"))) )
                {
                    $result .= $blankPre . "            if ( \${$instance_name}->{$fieldname} ) \${$instance_name}[\"$fieldname\"] = UtilDateTime::timestampToDateTime( \${$instance_name}->{$fieldname} );\r\n";
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
        $classname        = self::getClassname( $tablename );
        AutoCodePreviewReport::$service_files[$classname] = $relative_path;
        return self::saveDefineToDir( $service_dir_full, $filename, $definePhpFileContent );
    }
}
