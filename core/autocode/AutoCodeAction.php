<?php

/**
 * -----------| 枚举类型: 表示层生成类型定义 |-----------
 * @category betterlife
 * @package core.config
 * @author skygreen
 */
class EnumAutoCodeViewType extends Enum
{
    /**
     * 前台
     */
    const FRONT = 0;
    /**
     * 通用模版
     */
    const MODEL = 1;
    /**
     * 后台
     */
    const ADMIN = 2;
}

/**
 * -----------| 工具类:自动生成代码-控制器 |-----------
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeAction extends AutoCode
{
    /**
     * 控制器生成定义的方式
     *
     *     - 0: 前端Action，继承基本Action。
     *     - 1: 生成标准的增删改查模板Action，继承基本Action。
     *     - 2: 生成后台Action，继承基本Action。
     */
    public static $type;
    /**
     * Action文件所在的路径
     */
    public static $action_dir     = "action";
    /**
     * Action完整的保存路径
     */
    public static $action_dir_full;
    /**
     * 表示层Js文件所在的目录
     */
    public static $view_js_package;
    /**
     * 需打印输出的文本
     * @var string
     */
    public static $echo_result    = "";
    /**
     * 前端Action所在的namespace
     */
    private static $package_front = "web.front.action";
    /**
     * 后台Action所在的namespace
     */
    private static $package_back  = "web.back.admin";
    /**
     * 模板Action所在的namespace
     */
    private static $package_model = "web.model.action";

    /**
     * 自动生成代码-控制器
     * @param array|string $table_names
     * 示例如下:
     *
     *     1. array:array('bb_user_admin','bb_core_blog')
     *     2. 字符串:'bb_user_admin,bb_core_blog'
     */
    public static function autoCode($table_names = "")
    {
        switch (self::$type) {
            case EnumAutoCodeViewType::FRONT:
                self::$app_dir = Gc::$appName;
                break;
            case EnumAutoCodeViewType::MODEL:
                self::$app_dir = "model";
                break;
            case EnumAutoCodeViewType::ADMIN:
                self::$app_dir = "admin";
                break;
        }
        self::$action_dir_full = self::$save_dir . Gc::$module_root . DS . self::$app_dir . DS . self::$action_dir . DS;
        $view_dir_full         = self::$save_dir . Gc::$module_root . DS . self::$app_dir . DS . ConfigF::VIEW_VIEW . DS . Gc::$self_theme_dir . DS;

        if (!UtilString::is_utf8(self::$action_dir_full)) {
            self::$action_dir_full = UtilString::gbk2utf8(self::$action_dir_full);
        }
        self::init();
        if (self::$isOutputCss) {
            self::$showReport .= UtilCss::form_css() . HH;
        }
        self::$echo_result = "";

        switch (self::$type) {
            case EnumAutoCodeViewType::FRONT:
                self::$showReport .= AutoCodeFoldHelper::foldbeforeaction0();
                break;
            case EnumAutoCodeViewType::MODEL:
                self::$showReport .= AutoCodeFoldHelper::foldbeforeaction1();
                break;
            case EnumAutoCodeViewType::ADMIN:
                self::$showReport .= AutoCodeFoldHelper::foldbeforeaction2();
                break;
        }
        $link_action_dir_href = "file:///" . str_replace("\\", "/", self::$action_dir_full);
        self::$showReport    .= "<font color='#AAA'>存储路径:<a target='_blank' href='" . $link_action_dir_href . "'>" . self::$action_dir_full . "</a></font>";

        $fieldInfos = self::fieldInfosByTable_names($table_names);
        foreach ($fieldInfos as $tablename => $fieldInfo) {
            if (self::$type == EnumAutoCodeViewType::FRONT) {
                $classname = self::getClassname($tablename);
                if ($classname == "Admin") {
                    continue;
                }
            }
            $definePhpFileContent      = self::tableToActionDefine($tablename, $fieldInfo);
            if (!empty($definePhpFileContent)) {
                if (isset(self::$save_dir) && !empty(self::$save_dir) && isset($definePhpFileContent)) {
                    $classname         = self::saveActionDefineToDir($tablename, $definePhpFileContent);
                    self::$showReport .= "生成导出完成:$tablename => $classname!";
                } else {
                    self::$showReport .= $definePhpFileContent . "";
                }
            }
        }

        self::$showReport .= '</div>';
        $category_cap = Gc::$appName;
        $category_cap = ucfirst($category_cap);
        /**
         * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
         */
        self::createModelIndexFile();
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value = "", $title = "", $inputArr = null, $more_content = "")
    {
        $inputArr = array(
            EnumAutoCodeViewType::FRONT => "前端Action,继承基本Action",
            EnumAutoCodeViewType::MODEL => "生成标准的增删改查模板Action,继承ActionModel",
            EnumAutoCodeViewType::ADMIN => "后台Action,继承基本Action",
        );
        parent::UserInput("一键生成控制器Action类定义层", $inputArr, $default_value);
    }

    /**
     * 将表列定义转换成控制器Php文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToActionDefine($tablename, $fieldInfo)
    {
        $result        = "<?php" . HH . HH;
        $table_comment = self::tableCommentKey($tablename);
        $classname     = self::getClassname($tablename);
        $instancename  = self::getInstancename($tablename);

        $category      = Gc::$appName;
        $package       = self::$package_front;
        $author        = self::$author;

        $action_parent = "Action";
        switch (self::$type) {
            case EnumAutoCodeViewType::FRONT:
                $action_parent = "Action";
                $package       = self::$package_front;
                break;
            case EnumAutoCodeViewType::MODEL:
                $package       = self::$package_model;
                $action_parent = "ActionModel";
                break;
            case EnumAutoCodeViewType::ADMIN:
                $package       = self::$package_back;
                $action_parent = "ActionAdmin";
                break;
        }

        $result .= "/**" . HH .
                   " * -----------| 控制器:$table_comment |-----------" . HH .
                   " * @category $category" . HH .
                   " * @package $package" . HH .
                   " * @author $author" . HH .
                   " */" . HH .
                   "class Action_$classname extends $action_parent" . HH .
                   "{" . HH;

        switch (self::$type) {
            case EnumAutoCodeViewType::MODEL:
                $result .= self::modelActionContent($fieldInfo, $classname, $instancename, $table_comment);
                break;
            case EnumAutoCodeViewType::ADMIN:
                $result .= self::adminActionContent($fieldInfo, $classname, $instancename, $table_comment);
                break;
            default:
                $result .= self::frontActionContent($table_comment);
                break;
        }

        $result .= "}" . HH;
        return $result;
    }

    /**
     * 将表列定义转换成后台控制器Php文件定义的内容
     * @param array $fieldInfo 表列信息列表
     * @param string $classname 类名
     * @param string $instancename 实例名
     * @param string $table_comment 表注释
     */
    private static function adminActionContent($fieldInfo, $classname, $instancename, $table_comment)
    {
        $viewImgContent = "";
        $editImgContent = "";
        $editBitContent = "";
        $rela_m2m_content    = "";
        $relation_content    = "";
        $editTextareaContent = "";
        $text_area_fieldname = array();

        $relation_data = self::relationData($fieldInfo, $classname);

        $relation_content = $relation_data["relation_content"];
        $rela_m2m_content = $relation_data["rela_m2m_content"];
        foreach ($fieldInfo as $fieldname => $field) {
            $field_comment = $field["Comment"];
            if (self::columnIsTextArea($fieldname, $field["Type"])) {
                $text_area_fieldname[] = "'" . $fieldname . "'";
            }

            $isImage       = self::columnIsImage($fieldname, $field_comment);
            if ($isImage) {
                $viewImgContent .= "        if (!empty(\$$instancename->$fieldname)) {" . HH .
                                   "            \$$instancename->$fieldname = Gc::\$upload_url . \"images/\" . \$$instancename->$fieldname;" . HH .
                                   "        }" . HH;
                $editImgContent .= "            if (!empty(\$_FILES) && !empty(\$_FILES[\"$fieldname\"][\"name\"])) {" . HH .
                                   "                \$result = \$this->uploadImg(\$_FILES, \"$fieldname\", \"$fieldname\", \"$instancename\");" . HH .
                                   "                if (\$result && (\$result['success'] == true )) {" . HH .
                                   "                    if (array_key_exists('file_name', \$result) ) \$$instancename->$fieldname = \$result['file_name'];" . HH .
                                   "                } else {" . HH .
                                   "                    \$isRedirect = false;" . HH .
                                   "                    \$this->view->set( \"message\", \$result[\"msg\"] );" . HH .
                                   "                }" . HH .
                                   "            }" . HH;
            }

            $datatype = self::comment_type($field["Type"]);
            switch ($datatype) {
                case 'bit':
                    $editBitContent .= "            if (\${$instancename}->$fieldname == 'on' ) \$$instancename->$fieldname = 1; else \$$instancename->$fieldname = 0;" . HH;
                    break;
            }
        }
        $editBitContent = $editBitContent .
                          "            if (!empty(\$id)) {" . HH .
                          "                \${$instancename}->update();" . HH .
                          "            } else {" . HH .
                          "                \$id = \${$instancename}->save();" . HH .
                          "            }" . HH;

        if (count($text_area_fieldname) == 1) {
            $editTextareaContent .= "        \$this->load_onlineditor( {$text_area_fieldname[0]} );" . HH;
        } elseif (count($text_area_fieldname) > 1) {
            $fieldnames           = implode(", ", $text_area_fieldname);
            $editTextareaContent .= "        \$this->load_onlineditor( array({$fieldnames}) );" . HH;
        }
        $result = "    /**" . HH .
                  "     * {$table_comment}列表" . HH .
                  "     */" . HH .
                  "    public function lists()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 查看{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function view()" . HH .
                  "    {" . HH .
                  "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                  "        \${$instancename}   = $classname::getById( \${$instancename}Id );" . HH .
                  $viewImgContent .
                  "        \$this->view->set( \"$instancename\", \$$instancename );" . HH .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 编辑{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function edit()" . HH .
                  "    {" . HH .
                  "        if (!empty(\$_POST)) {" . HH .
                  "            \$$instancename = \$this->model->$classname;" . HH .
                  "            \$id = \${$instancename}->getId();" . HH .
                  "            \$isRedirect = true;" . HH .
                  $editImgContent .
                  $editBitContent .
                  $rela_m2m_content .
                  "            if (\$isRedirect) {" . HH .
                  "                \$this->redirect( \"$instancename\", \"view\", \"id=\$id\" );" . HH .
                  "                exit;" . HH .
                  "            }" . HH .
                  "        }" . HH .
                  "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                  "        \${$instancename}   = $classname::getById( \${$instancename}Id );" . HH .
                  "        \$this->view->set( \"$instancename\", \$$instancename );" . HH .
                  $relation_content .
                  $editTextareaContent .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 删除{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function delete()" . HH .
                  "    {" . HH .
                  "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                  "        \$isDelete = $classname::deleteByID( \${$instancename}Id );" . HH .
                  "        return array(\"info\" => 200, \"data\" => \${$instancename}Id);" . HH .
                  "    }" . HH;
        $import = HH .
                  "    /**" . HH .
                  "     * 批量上传{$table_comment}" . HH .
                  "     * @param mixed \$upload_file <input name=\"upload_file\" type=\"file\">" . HH .
                  "     */" . HH .
                  "    public function import()" . HH .
                  "    {" . HH .
                  "        if (!empty(\$_FILES)) {" . HH .
                  "            return Manager_Service::{$instancename}Service()->import( \$_FILES );" . HH .
                  "        }" . HH .
                  "        return array(\"error\" => 500,\"info\" => \"No Data\");" . HH .
                  "    }" . HH;

        $classNameField   = self::getShowFieldName($classname);
        $export = HH .
                  "    /**" . HH .
                  "     * 导出{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function export()" . HH .
                  "    {" . HH .
                  "        \$filter_name = \"$classNameField\";" . HH .
                  "        \$filter      = null;" . HH .
                  "        if (!empty(\$filter_name) && !empty(\$this->data[\"query\"])) {" . HH .
                  "            \$filter = array(\$filter_name => \$this->data[\"query\"]);" . HH .
                  "        }" . HH .
                  "        return Manager_Service::{$instancename}Service()->export{$classname}( \$filter );" . HH .
                  "    }" . HH;
        $result .= $import . $export;
        return $result;
    }

    /**
     * 将表列定义转换成前台控制器Php文件定义的内容
     * @param array $fieldInfo 表列信息列表
     * @param string $classname 类名
     * @param string $instancename 实例名
     * @param string $table_comment 表注释
     */
    private static function frontActionContent($table_comment)
    {
        $result = "    /**" . HH .
                  "     * {$table_comment}列表" . HH .
                  "     */" . HH .
                  "    public function lists()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 查看{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function view()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 编辑{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function edit()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH .
                  "    /**" . HH .
                  "     * 删除{$table_comment}" . HH .
                  "     */" . HH .
                  "    public function delete()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH;
        return $result;
    }

    /**
     * 将表列定义转换成标准的增删改查模板控制器Php文件定义的内容
     * @param array $fieldInfo 表列信息列表
     * @param string $classname 类名
     * @param string $instancename 实例名
     * @param string $table_comment 表注释
     */
    private static function modelActionContent($fieldInfo, $classname, $instancename, $table_comment)
    {
        $result        = "";
        $appname_alias = strtolower(Gc::$appName_alias);
        $result .= "    /**" . HH .
                   "     * {$table_comment}列表" . HH .
                   "     */" . HH .
                   "    public function lists()" . HH .
                   "    {" . HH .
                   "        if (\$this->isDataHave( TagPageService::\$linkUrl_pageFlag )) {" . HH .
                   "            \$nowpage = \$this->data[TagPageService::\$linkUrl_pageFlag];" . HH .
                   "        } else {" . HH .
                   "            \$nowpage = 1;" . HH .
                   "        }" . HH .
                   "        \$count = {$classname}::count();" . HH .
                   "        \$this->view->count{$classname}s = \$count;" . HH .
                   "        \${$instancename}s = null;" . HH .
                   "        if (\$count > 0) {" . HH .
                   "            \${$appname_alias}_page = TagPageService::init(\$nowpage,\$count);" . HH .
                   "            \${$instancename}s = {$classname}::queryPage( \${$appname_alias}_page->getStartPoint(), \${$appname_alias}_page->getEndPoint() );" . HH .
                   "        }" . HH .
                   "        \$this->view->set( \"{$instancename}s\", \${$instancename}s );" . HH .
                   "    }" . HH;

        //如果是目录树【parent_id】,需要附加一个递归函数显示父目录[全]
        // $relationFieldTreeRecursive = self::relationFieldTreeRecursive( $instancename, $classname, $fieldInfo );
        // if ($relationFieldTreeRecursive ) $relationFieldTreeRecursive = HH . $relationFieldTreeRecursive;
        // $result .= $relationFieldTreeRecursive;

        $result .= "    /**" . HH .
                   "     * 查看{$table_comment}" . HH .
                   "     */" . HH .
                   "    public function view()" . HH .
                   "    {" . HH .
                   "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                   "        \${$instancename}   = {$classname}::getById( \${$instancename}Id );" . HH .
                  //  $relationField.
                   "        \$this->view->set( \"{$instancename}\", \${$instancename} );" . HH .
                   "    }" . HH;


        $editBitContent = "";

        $rela_m2m_content    = "";
        $relation_content    = "";
        $relation_data = self::relationData($fieldInfo, $classname);

        $relation_content = $relation_data["relation_content"];
        $rela_m2m_content = $relation_data["rela_m2m_content"];

        foreach ($fieldInfo as $fieldname => $field) {
            // $field_comment = $field["Comment"];
            $datatype = self::comment_type($field["Type"]);
            switch ($datatype) {
                case 'bit':
                    $editBitContent .= "            if (\${$instancename}->{$fieldname} == '1' ) \${$instancename}->{$fieldname} = 1; else \${$instancename}->isPublic = 0;" . HH;
                    break;
            }
        }
        $editContent = $editBitContent .
                       "            if (!empty(\$id)) {" . HH .
                       "                \${$instancename}->update();" . HH .
                       "            } else {" . HH .
                       "                \$id = \${$instancename}->save();" . HH .
                       "            }" . HH;

        $result .= "    /**" . HH .
                   "     * 编辑{$table_comment}" . HH .
                   "     */" . HH .
                   "    public function edit()" . HH .
                   "    {" . HH .
                   "        if (!empty(\$_POST)) {" . HH .
                   "            \${$instancename} = \$this->model->{$classname};" . HH .
                   "            \$id         = \${$instancename}->getId();" . HH .
                   "            \$isRedirect = true;" . HH .
                   self::uploadImgInEdit($instancename, $fieldInfo) .
                   $editContent .
                   $rela_m2m_content .
                   "            if (\$isRedirect) {" . HH .
                   "                \$this->redirect( \"{$instancename}\", \"view\", \"id=\$id\" );" . HH .
                   "                exit;" . HH .
                   "            }" . HH .
                   "        }" . HH .
                   "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                   "        \${$instancename}   = {$classname}::getById( \${$instancename}Id );" . HH .
                   "        \$this->view->set( \"{$instancename}\", \${$instancename} );" . HH .
                   $relation_content;
        $text_area_fieldname = array();
        foreach ($fieldInfo as $fieldname => $field) {
            if (self::columnIsTextArea($fieldname, $field["Type"])) {
                $text_area_fieldname[] = "'" . $fieldname . "'";
            }
        }
        if (count($text_area_fieldname) == 1) {
            $result .= "        //加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。" . HH .
                       "        \$this->load_onlineditor( {$text_area_fieldname[0]} );" . HH;
        } elseif (count($text_area_fieldname) > 1) {
            $fieldnames = implode(",", $text_area_fieldname);
            $result    .= "        //加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。" . HH .
                          "        \$this->load_onlineditor( array({$fieldnames}) );" . HH;
        }
        $result .= "    }" . HH .
                   "    /**" . HH .
                   "     * 删除{$table_comment}" . HH .
                   "     */" . HH .
                   "    public function delete()" . HH .
                   "    {" . HH .
                   "        \${$instancename}Id = \$this->data[\"id\"];" . HH .
                   "        \$isDelete = {$classname}::deleteByID( \${$instancename}Id );" . HH .
                   "        \$this->redirect( \"{$instancename}\", \"lists\", \$this->data );" . HH .
                   "    }" . HH;
        return $result;
    }

    private static function relationData($fieldInfo, $classname)
    {
        $relation_content = "";
        $rela_m2m_content = "";
        $relation_data    = array();
        if (array_key_exists($classname, self::$relation_all)) {
            $relationSpec = self::$relation_all[$classname];
        }
        if (isset($relationSpec) && is_array($relationSpec) && (count($relationSpec) > 0)) {
            //从属一对一关系规范定义(如果存在)
            if (array_key_exists("belong_has_one", $relationSpec)) {
                $belong_has_one        = $relationSpec["belong_has_one"];
                foreach ($belong_has_one as $key => $value) {
                    $realId            = DataObjectSpec::getRealIDColumnName($key);
                    $relation_content .= "        \${$value}s = {$key}::get( \"\", \"$realId asc\" );" . HH .
                                         "        \$this->view->set( \"{$value}s\", \${$value}s );" . HH;
                }
            }

            //多对多关系规范定义(如果存在)
            if (array_key_exists("many_many", $relationSpec)) {
                $many_many        = $relationSpec["many_many"];
                foreach ($many_many as $key => $value) {
                    $realId            = DataObjectSpec::getRealIDColumnName($classname);
                    $realId_m2m        = DataObjectSpec::getRealIDColumnName($key);
                    $tablename         = self::getTablename($classname);
                    $instancename      = self::getInstancename($tablename);
                    $talname_rela      = self::getTablename($key);
                    $instancename_rela = self::getInstancename($talname_rela);
                    $rela_m2m_content .= "            \${$instancename}{$key} = \$this->data[\"$realId_m2m\"];" . HH .
                                         "            {$classname}{$instancename_rela}::saveDeleteRelateions( \"$realId\", \$id, \"$realId_m2m\", \${$instancename}{$key} );" . HH;
                }
            }
        }
        $relation_data["relation_content"] = $relation_content;
        $relation_data["rela_m2m_content"] = $rela_m2m_content;
        return $relation_data;
    }
    // /**
    //  * 目录树递归函数:显示父目录[全]
    //  * 如果是目录树【parent_id】,需要附加一个递归函数显示父目录[全]
    //  * @param mixed $instance_name 实体变量
    //  * @param mixed $classname 数据对象列名
    //  * @param mixed $fieldInfo 表列信息列表
    //  */
    // public static function relationFieldTreeRecursive($instance_name, $classname, $fieldInfo)
    // {
    //     $result = "";
    //     if (is_array(self::$relation_viewfield) && (count( self::$relation_viewfield ) > 0))
    //     {
    //         if (array_key_exists($classname, self::$relation_viewfield)) {
    //             $relationSpecs  = self::$relation_viewfield[$classname];
    //             $isTreeLevelHad = false;
    //             foreach ($fieldInfo as $fieldname => $field) {
    //                 if (array_key_exists($fieldname, $relationSpecs)) {
    //                     $relationShow  = $relationSpecs[$fieldname];
    //                     foreach ($relationShow as $key => $value) {
    //                         $i_name    = $key;
    //                         $i_name    = lcfirst($i_name);
    //                         $fieldInfo = self::$fieldInfos[self::getTablename($key)];
    //                         if (!$isTreeLevelHad) {
    //                             if (array_key_exists("parent_id", $fieldInfo) && array_key_exists("level", $fieldInfo)) {
    //                                 // print_pre($relationSpecs, true);die();
    //                                 $classNameField = self::getShowFieldName( $key );
    //                                 $field_comment  = $field["Comment"];
    //                                 $field_comment  = self::columnCommentKey( $field_comment, $fieldname );
    //                                 $result .= "    /**" . HH .
    //                                            "     * 显示{$field_comment}[全]" . HH .
    //                                            "     * 注:采用了递归写法" . HH .
    //                                            "     * @param 对象 \$parent_id 父地区标识" . HH .
    //                                            "     * @param mixed \$level 目录层级" . HH .
    //                                            "     */" . HH .
    //                                            "    private function {$i_name}ShowAll(\$parent_id, \$level)" . HH .
    //                                            "    {" . HH .
    //                                            "        \${$i_name}_p = $key::getById( \$parent_id );" . HH .
    //                                            "        if (\$level == 1) {" . HH .
    //                                            "            \${$i_name}ShowAll = \${$i_name}_p->$classNameField;" . HH .
    //                                            "        } else {" . HH .
    //                                            "            \$parent_id = \${$i_name}_p->parent_id;" . HH .
    //                                            "            \${$i_name}ShowAll = \$this->{$i_name}ShowAll( \$parent_id, \$level - 1 ) . \" -> \" . \${$i_name}_p -> $classNameField;" . HH .
    //                                            "        }" . HH .
    //                                            "        return \${$i_name}ShowAll;" . HH .
    //                                            "    }" . HH . HH;
    //                                 $isTreeLevelHad = true;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $result;
    // }

    /**
     * 是否需要在编辑页面上传图片
     * @param string $instancename 实体变量
     * @param array $fieldInfo 表列信息列表
     */
    private static function uploadImgInEdit($instancename, $fieldInfo)
    {
        $result               = "";
        $fieldNameAndComments = array();
        foreach ($fieldInfo as $fieldname => $field) {
            $field_comment = $field["Comment"];
            if (contain($field_comment, "\r") || contain($field_comment, "\n")) {
                $field_comment = preg_split("/[\s,]+/", $field_comment);
                $field_comment = $field_comment[0];
            }
            $fieldNameAndComments[$fieldname] = $field_comment;
        }
        $img_fieldname = array();
        foreach ($fieldNameAndComments as $key => $value) {
            $isImage = self::columnIsImage($key, $value);
            if ($isImage) {
                $img_fieldname[] = $key;
            }
        }

        if ($img_fieldname && count($img_fieldname) > 0) {
            foreach ($img_fieldname as $fieldname) {
                $result .= "            if (!empty(\$_FILES)&&!empty(\$_FILES[\"{$fieldname}\"][\"name\"])) {" . HH .
                           "                \$result = \$this->uploadImg( \$_FILES, \"{$fieldname}\", \"{$fieldname}\", \"$instancename\" );" . HH .
                           "                if (\$result && (\$result['success'] == true )) {" . HH .
                           "                    if (array_key_exists('file_name', \$result) ) \${$instancename}->$fieldname = \$result['file_name'];" . HH .
                           "                } else {" . HH .
                           "                    \$isRedirect = false;" . HH .
                           "                    \$this->view->set( \"message\", \$result[\"msg\"] );" . HH .
                           "                }" . HH .
                           "            }" . HH;
            }
        }
        return $result;
    }

    /**
     * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
     */
    private static function createModelIndexFile()
    {
        $category      = Gc::$appName;
        $package       = self::$package_front;
        $author        = self::$author;
        $action_parent = "Action";
        if (self::$type == 1) {
            $package       = self::$package_model;
        }
        if (self::$type == 1) {
            $action_parent = "ActionModel";
        }
        $result = "<?php" . HH .
                  "/**" . HH .
                  " * -----------| 控制器:首页导航 |-----------" . HH .
                  " * @category $category" . HH .
                  " * @package $package" . HH .
                  " * @author $author" . HH .
                  " */" . HH .
                  "class Action_Index extends $action_parent" . HH .
                  "{" . HH .
                  "    /**" . HH .
                  "     * 首页:网站所有页面列表" . HH .
                  "     */" . HH .
                  "    public function index()" . HH .
                  "    {" . HH .
                  "        " . HH .
                  "    }" . HH .
                  "}" . HH . HH;
        self::saveDefineToDir(self::$action_dir_full, "Action_Index.php", $result);
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码
     */
    private static function saveActionDefineToDir($tablename, $definePhpFileContent)
    {
        $classname     = self::getClassname($tablename);
        $filename      = "Action_" . $classname . ".php";
        $relative_path = str_replace(self::$save_dir, "", self::$action_dir_full . $filename);
        switch (self::$type) {
            case EnumAutoCodeViewType::FRONT:
                AutoCodePreviewReport::$action_front_files[$classname] = $relative_path;
                break;
            case EnumAutoCodeViewType::MODEL:
                AutoCodePreviewReport::$action_model_files[$classname] = $relative_path;
                break;
            case EnumAutoCodeViewType::ADMIN:
                AutoCodePreviewReport::$action_admin_files[$classname] = $relative_path;
                break;
        }
        return self::saveDefineToDir(self::$action_dir_full, $filename, $definePhpFileContent);
    }
}
