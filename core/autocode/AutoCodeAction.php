<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-控制器<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeAction extends AutoCode
{
    /**
     * 控制器生成定义的方式<br/>
     * 0.前端Action，继承基本Action。<br/>
     * 1.生成标准的增删改查模板Action，继承基本Action。<br/>
     */
    public static $type;
    /**
     * Action文件所在的路径
     */
    public static $action_dir="action";
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
    public static $echo_result="";
    /**
     * 前端Action所在的namespace
     */
    private static $package_front="web.front.action";
    /**
     * 后端Action所在的namespace
     */
    private static $package_back="web.back.admin";
    /**
     * 模板Action所在的namespace
     */
    private static $package_model="web.model.action";

    /**
     * 自动生成代码-控制器
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names="")
    {
        switch (self::$type) {
            case 0:
                self::$app_dir = Gc::$appName;
                break;
            case 1:
                self::$app_dir = "model";
                break;
        }
        self::$action_dir_full=self::$save_dir.self::$app_dir.DS.self::$action_dir.DS;
        $view_dir_full=self::$save_dir.self::$app_dir.DS.Config_F::VIEW_VIEW.DS.Gc::$self_theme_dir.DS;

        if (!UtilString::is_utf8(self::$action_dir_full)){
            self::$action_dir_full=UtilString::gbk2utf8(self::$action_dir_full);
        }
        self::init();
        if (self::$isOutputCss) self::$showReport.= UtilCss::form_css()."\r\n";
        self::$echo_result="";

        if(self::$type==0) {
            self::$showReport.=AutoCodeFoldHelper::foldbeforeaction0();
            $link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full);
            self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_action_dir_href."'>".self::$action_dir_full."</a></font><br/><br/>";
        }else if(self::$type==1) {
            self::$showReport.=AutoCodeFoldHelper::foldbeforeaction1();
            $link_action_dir_href="file:///".str_replace("\\", "/", self::$action_dir_full);
            self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_action_dir_href."'>".self::$action_dir_full."</a></font><br/><br/>";
        }

        $fieldInfos=self::fieldInfosByTable_names($table_names);
        foreach ($fieldInfos as $tablename=>$fieldInfo){
            if(self::$type==0) {
                $classname=self::getClassname($tablename);
                if ($classname=="Admin")continue;
            }
            $definePhpFileContent=self::tableToActionDefine($tablename,$fieldInfo);
            if (!empty($definePhpFileContent)){
                if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
                    $classname=self::saveActionDefineToDir($tablename,$definePhpFileContent);
                    self::$showReport.= "生成导出完成:$tablename=>$classname!<br/>";
                }else{
                    self::$showReport.= $definePhpFileContent."<br/>";
                }
            }
        }

        self::$showReport.= '</div><br/>';

        $category_cap=Gc::$appName;
        $category_cap{0}=ucfirst($category_cap{0});
        /**
         * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
         */
        self::createModelIndexFile();
        self::createActionParent();
    }

    /**
    * 生成Action的父类。
    * 1.前台生成Action;3.模板生成ActionModel
    */
    public static function createActionParent()
    {
        $dir_home_app=self::$save_dir.DS.self::$app_dir.DS."action".DS;
        $author=self::$author;
        $category=Gc::$appName;
        switch (self::$type) {
            case 1:
                $actionModel = <<<ACTIONMODEL
<?php
/**
 +----------------------------------------------<br/>
 * 所有Model应用控制器的父类<br/>
 +----------------------------------------------
 * @category $category
 * @package web.model
 * @author $author
 */
class ActionModel extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }

    /**
     * 上传图片文件
     * @param array \$files 上传的文件对象
     * @param array \$uploadFlag 上传标识,上传文件的input组件的名称
     * @param array \$upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
     * @param array \$categoryId 上传文件所在的目录标识，一般为类实例名称
     * @return array 是否创建成功。
     */
    public function uploadImg(\$files,\$uploadFlag,\$upload_dir,\$categoryId="default")
    {
        \$diffpart=date("YmdHis");
        \$result="";
        if (!empty(\$files[\$uploadFlag])&&!empty(\$files[\$uploadFlag]["name"])){
            \$tmptail = end(explode('.', \$files[\$uploadFlag]["name"]));
            \$uploadPath =GC::\$upload_path."images".DS.\$categoryId.DS.\$upload_dir.DS.\$diffpart.".".\$tmptail;
            \$result     =UtilFileSystem::uploadFile(\$files,\$uploadPath,\$uploadFlag);
            if (\$result&&(\$result['success']==true)){
                \$result['file_name']="\$categoryId/\$upload_dir/\$diffpart.\$tmptail";
            }else{
                return \$result;
            }
        }
        return \$result;
    }
}

ACTIONMODEL;
                self::saveDefineToDir($dir_home_app,"ActionModel.php",$actionModel);
                break;

            default:
                $action =<<<ACTION
<?php
/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
 +----------------------------------------------
 * @category $category
 * @package  web.front
 * @author $author
 */
class Action extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();
        if (contain(\$this->data["go"],Gc::\$appName)){
            if((\$this->data["go"]!=Gc::\$appName.".auth.register")&&(\$this->data["go"]!=Gc::\$appName.".auth.login")&&!HttpSession::isHave('user_id')) {
                \$this->redirect("auth","login");
            }
        }
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }
}

ACTION;
                self::saveDefineToDir($dir_home_app,"Action.php",$action);
                break;
        }
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($default_value="", $title="", $inputArr=null, $more_content="")
    {
        $inputArr=array(
            "0"=>"前端Action,继承基本Action",
            "1"=>"生成标准的增删改查模板Action,继承ActionModel",
        );
        parent::UserInput("一键生成控制器Action类定义层",$inputArr,$default_value);
    }

    /**
     * 将表列定义转换成控制器Php文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToActionDefine($tablename,$fieldInfo)
    {
        $result="<?php\r\n";
        $table_comment=self::tableCommentKey($tablename);
        $category  = Gc::$appName;
        $package   = self::$package_front;
        $classname = self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);
        $appname_alias=strtolower(Gc::$appName_alias);
        $author    = self::$author;
        switch (self::$type) {
            case 1:
                $package=self::$package_model;
                $relationField=self::relationFieldShow($instancename,$classname,$fieldInfo,"        ");
                $specialResult="";
                if ((!empty($relationField))){
                    $specialResult.="            foreach (\${$instancename}s as \$$instancename) {\r\n".
                                    $relationField.
                                    "            }\r\n";
                }
                $result.="/**\r\n".
                         " +---------------------------------------<br/>\r\n".
                         " * 控制器:$table_comment<br/>\r\n".
                         " +---------------------------------------\r\n".
                         " * @category $category\r\n".
                         " * @package $package\r\n".
                         " * @author $author\r\n".
                         " */\r\n".
                         "class Action_$classname extends ActionModel\r\n".
                         "{\r\n".
                         "    /**\r\n".
                         "     * {$table_comment}列表\r\n".
                         "     */\r\n".
                         "    public function lists()\r\n".
                         "    {\r\n".
                         "        if (\$this->isDataHave(TagPageService::\$linkUrl_pageFlag)) {\r\n".
                         "            \$nowpage = \$this->data[TagPageService::\$linkUrl_pageFlag];\r\n".
                         "        } else {\r\n".
                         "            \$nowpage = 1;\r\n".
                         "        }\r\n".
                         "        \$count = {$classname}::count();\r\n".
                         "        \$this->view->count{$classname}s = \$count;\r\n".
                         "        \$this->view->set(\"{$instancename}s\", NULL);\r\n".
                         "        if (\$count>0) {\r\n".
                         "            \${$appname_alias}_page = TagPageService::init(\$nowpage,\$count);\r\n".
                         "            \${$instancename}s = {$classname}::queryPage(\${$appname_alias}_page->getStartPoint(), \${$appname_alias}_page->getEndPoint());\r\n".
                         $specialResult.
                         "            \$this->view->set(\"{$instancename}s\", \${$instancename}s);\r\n".
                         "        }\r\n".
                         "    }\r\n";

                //如果是目录树【parent_id】,需要附加一个递归函数显示父目录[全]
                $relationFieldTreeRecursive = self::relationFieldTreeRecursive($instancename, $classname, $fieldInfo);
                if($relationFieldTreeRecursive) $relationFieldTreeRecursive = "\r\n".$relationFieldTreeRecursive;
                $result .= $relationFieldTreeRecursive;
                $relationField = self::relationFieldShow($instancename,$classname,$fieldInfo);
                $result .= "    /**\r\n".
                           "     * 查看{$table_comment}\r\n".
                           "     */\r\n".
                           "    public function view()\r\n".
                           "    {\r\n".
                           "        \${$instancename}Id = \$this->data[\"id\"];\r\n".
                           "        \${$instancename} = {$classname}::get_by_id(\${$instancename}Id);\r\n".
                           $relationField.
                           "        \$this->view->set(\"{$instancename}\", \${$instancename});\r\n".
                           "    }\r\n".
                           "    /**\r\n".
                           "     * 编辑{$table_comment}\r\n".
                           "     */\r\n".
                           "    public function edit()\r\n".
                           "    {\r\n".
                           "        if (!empty(\$_POST)) {\r\n".
                           "            \${$instancename} = \$this->model->{$classname};\r\n".
                           "            \$id = \${$instancename}->getId();\r\n".
                           "            \$isRedirect=true;\r\n".
                           self::uploadImgInEdit($instancename,$fieldInfo).
                           "            if (!empty(\$id)){\r\n".
                           "                \${$instancename}->update();\r\n".
                           "            }else{\r\n".
                           "                \$id = \${$instancename}->save();\r\n".
                           "            }\r\n".
                           "            if (\$isRedirect){\r\n".
                           "                \$this->redirect(\"{$instancename}\", \"view\", \"id=\$id\");\r\n".
                           "                exit;\r\n".
                           "            }\r\n".
                           "        }\r\n".
                           "        \${$instancename}Id = \$this->data[\"id\"];\r\n".
                           "        \${$instancename} = {$classname}::get_by_id(\${$instancename}Id);\r\n".
                           "        \$this->view->set(\"{$instancename}\", \${$instancename});\r\n";
                $text_area_fieldname=array();
                foreach ($fieldInfo as $fieldname => $field)
                {
                    if (self::columnIsTextArea($fieldname,$field["Type"]))
                    {
                        $text_area_fieldname[] = "'".$fieldname."'";
                    }
                }
                if (count($text_area_fieldname)==1) {
                    $result.="        //加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。\r\n".
                             "        \$this->load_onlineditor({$text_area_fieldname[0]});\r\n";
                }else if (count($text_area_fieldname)>1){
                    $fieldnames=implode(",", $text_area_fieldname);
                    $result.="        //加载在线编辑器的语句要放在:\$this->view->viewObject[如果有这一句]之后。\r\n".
                             "        \$this->load_onlineditor(array({$fieldnames}));\r\n";
                }
                $result.="    }\r\n".
                         "    /**\r\n".
                         "     * 删除{$table_comment}\r\n".
                         "     */\r\n".
                         "    public function delete()\r\n".
                         "    {\r\n".
                         "        \${$instancename}Id = \$this->data[\"id\"];\r\n".
                         "        \$isDelete = {$classname}::deleteByID(\${$instancename}Id);\r\n".
                         "        \$this->redirect(\"{$instancename}\", \"lists\", \$this->data);\r\n".
                         "    }\r\n".
                         "}\r\n\r\n";
                break;
            default:
                $result.="/**\r\n".
                         " +---------------------------------------<br/>\r\n".
                         " * 控制器:$table_comment<br/>\r\n".
                         " +---------------------------------------\r\n".
                         " * @category $category\r\n".
                         " * @package $package\r\n".
                         " * @author $author\r\n".
                         " */\r\n".
                         "class Action_$classname extends Action\r\n".
                         "{\r\n".
                         "    /**\r\n".
                         "     * {$table_comment}列表\r\n".
                         "     */\r\n".
                         "    public function lists()\r\n".
                         "    {\r\n".
                         "        \r\n".
                         "    }\r\n".
                         "    /**\r\n".
                         "     * 查看{$table_comment}\r\n".
                         "     */\r\n".
                         "    public function view()\r\n".
                         "    {\r\n".
                         "        \r\n".
                         "    }\r\n".
                         "    /**\r\n".
                         "     * 编辑{$table_comment}\r\n".
                         "     */\r\n".
                         "    public function edit()\r\n".
                         "    {\r\n".
                         "        \r\n".
                         "    }\r\n".
                         "    /**\r\n".
                         "     * 删除{$table_comment}\r\n".
                         "     */\r\n".
                         "    public function delete()\r\n".
                         "    {\r\n".
                         "        \r\n".
                         "    }\r\n".
                         "}\r\n\r\n";
                break;
        }
        return $result;
    }

    /**
     * 目录树递归函数:显示父目录[全]
     * 如果是目录树【parent_id】,需要附加一个递归函数显示父目录[全]
     * @param mixed $instance_name 实体变量
     * @param mixed $classname 数据对象列名
     * @param mixed $fieldInfo 表列信息列表
     */
    public static function relationFieldTreeRecursive($instance_name,$classname,$fieldInfo)
    {
        $result="";
        if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
        {
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];
                $isTreeLevelHad=false;
                foreach ($fieldInfo as $fieldname=>$field){
                    if (array_key_exists($fieldname,$relationSpecs)){
                        $relationShow=$relationSpecs[$fieldname];
                        foreach ($relationShow as $key=>$value) {
                            $i_name=$key;
                            $i_name{0}=strtolower($i_name{0});
                            $fieldInfo=self::$fieldInfos[self::getTablename($key)];
                            if (!$isTreeLevelHad){
                                if (array_key_exists("parent_id",$fieldInfo)&&array_key_exists("level",$fieldInfo)){
                                    $classNameField=self::getShowFieldNameByClassname($key);                                    $classNameField=self::getShowFieldNameByClassname($key);
                                    $field_comment=$field["Comment"];
                                    $field_comment=self::columnCommentKey($field_comment,$fieldname);
                                    $result.="    /**\r\n".
                                             "     * 显示{$field_comment}[全]\r\n".
                                             "     * 注:采用了递归写法\r\n".
                                             "     * @param 对象 \$parent_id 父地区标识\r\n".
                                             "     * @param mixed \$level 目录层级\r\n".
                                             "     */\r\n".
                                             "    private function {$i_name}ShowAll(\$parent_id,\$level)\r\n".
                                             "    {\r\n".
                                             "        \${$i_name}_p=$key::get_by_id(\$parent_id);\r\n".
                                             "        if (\$level==1){\r\n".
                                             "            \${$i_name}ShowAll=\${$i_name}_p->$classNameField;\r\n".
                                             "        }else{\r\n".
                                             "            \$parent_id=\${$i_name}_p->parent_id;\r\n".
                                             "            \${$i_name}ShowAll=\$this->{$i_name}ShowAll(\$parent_id,\$level-1).\"->\".\${$i_name}_p->$classNameField;\r\n".
                                             "        }\r\n".
                                             "        return \${$i_name}ShowAll;\r\n".
                                             "    }\r\n\r\n";
                                    $isTreeLevelHad=true;
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
     * 显示关系列
     * @param mixed $instance_name 实体变量
     * @param mixed $classname 数据对象列名
     * @param mixed $fieldInfo 表列信息列表
     * @param string $blank_pre 空格字符串
     */
    protected static function relationFieldShow($instance_name,$classname,$fieldInfo,$blank_pre="")
    {
        $result="";
        if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
        {
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];
                $isTreeLevelHad=false;
                foreach ($fieldInfo as $fieldname=>$field){
                    if (array_key_exists($fieldname,$relationSpecs)){
                        $relationShow=$relationSpecs[$fieldname];
                        foreach ($relationShow as $key=>$value) {
                            $realId=DataObjectSpec::getRealIDColumnName($key);
                            $show_fieldname=$value;
                            if ($realId!=$fieldname){
                                $show_fieldname.="_".$fieldname;
                                if (contain($show_fieldname,"_id")){
                                    $show_fieldname=str_replace("_id","",$show_fieldname);
                                }
                            }
                            if ($show_fieldname=="name")$show_fieldname=strtolower($key)."_".$value;
                            $i_name=$key;
                            $i_name{0}=strtolower($i_name{0});
                            if (!array_key_exists("$show_fieldname",$fieldInfo)){
                                $result.=$blank_pre."        \${$i_name}_instance = null;\r\n";
                                $result.=$blank_pre."        if (\${$instance_name}->$fieldname) {\r\n";
                                $result.=$blank_pre."            \${$i_name}_instance = $key::get_by_id(\${$instance_name}->$fieldname);\r\n";
                                $result.=$blank_pre."            \$".$instance_name."['$show_fieldname'] = \${$i_name}_instance->$value;\r\n";
                                $result.=$blank_pre."        }\r\n";
                            }
                            $fieldInfo=self::$fieldInfos[self::getTablename($key)];
                            if (!$isTreeLevelHad){
                                if (array_key_exists("parent_id",$fieldInfo)&&array_key_exists("level",$fieldInfo)){
                                    $classNameField=self::getShowFieldNameByClassname($key);
                                    $result.=$blank_pre."        if (\${$i_name}_instance) {\r\n".
                                             $blank_pre."            \$level = \${$i_name}_instance->level;\r\n".
                                             $blank_pre."            \${$instance_name}[\"{$i_name}ShowAll\"] = \$this->{$i_name}ShowAll(\${$instance_name}->parent_id,\$level);\r\n".
                                             $blank_pre."        }\r\n";
                                    $isTreeLevelHad=true;
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
     * 是否需要在编辑页面上传图片
     * @param string $instancename 实体变量
     * @param array $fieldInfo 表列信息列表
     */
    private static function uploadImgInEdit($instancename,$fieldInfo)
    {
        $result="";
        $fieldNameAndComments=array();
        foreach ($fieldInfo as $fieldname=>$field)
        {
            $field_comment=$field["Comment"];
            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
            {
                $field_comment=preg_split("/[\s,]+/", $field_comment);
                $field_comment=$field_comment[0];
            }
            $fieldNameAndComments[$fieldname]=$field_comment;
        }
        $img_fieldname=array();
        foreach ($fieldNameAndComments as $key=>$value) {
            $isImage =self::columnIsImage($key,$value);
            if ($isImage)
            {
                $img_fieldname[]=$key;
            }
        }

        if (count($img_fieldname>0)){
            foreach ($img_fieldname as $fieldname) {
                $result.="            if (!empty(\$_FILES)&&!empty(\$_FILES[\"{$fieldname}Upload\"][\"name\"])){\r\n".
                         "                \$result=\$this->uploadImg(\$_FILES,\"{$fieldname}Upload\",\"{$fieldname}\",\"$instancename\");\r\n".
                         "                if (\$result&&(\$result['success']==true)){\r\n".
                         "                    if (array_key_exists('file_name',\$result))\${$instancename}->$fieldname = \$result['file_name'];\r\n".
                         "                }else{\r\n".
                         "                    \$isRedirect=false;\r\n".
                         "                    \$this->view->set(\"message\",\$result[\"msg\"]);\r\n".
                         "                }\r\n".
                         "            }\r\n";
            }
        }
        return $result;
    }

    /**
     * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
     */
    private static function createModelIndexFile()
    {
        $category  = Gc::$appName;
        $package   = self::$package_front;
        if (self::$type==1)$package=self::$package_model;
        $author    = self::$author;
        $action_parent="Action";
        if (self::$type==1)$action_parent="ActionModel";
        $result="<?php\r\n".
                 "/**\r\n".
                 " +---------------------------------------<br/>\r\n".
                 " * 控制器:首页导航<br/>\r\n".
                 " +---------------------------------------\r\n".
                 " * @category $category\r\n".
                 " * @package $package\r\n".
                 " * @author $author\r\n".
                 " */\r\n".
                 "class Action_Index extends $action_parent\r\n".
                 "{\r\n".
                 "    /**\r\n".
                 "     * 首页:网站所有页面列表\r\n".
                 "     */\r\n".
                 "    public function index()\r\n".
                 "    {\r\n".
                 "        \r\n".
                 "    }\r\n".
                 "}\r\n\r\n";
        self::saveDefineToDir(self::$action_dir_full,"Action_Index.php",$result);
    }

    /**
     * 保存生成的代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码
     */
    private static function saveActionDefineToDir($tablename,$definePhpFileContent)
    {
        $classname=self::getClassname($tablename);
        $filename="Action_".$classname.".php";

        $relative_path=str_replace(self::$save_dir, "",self::$action_dir_full.$filename);

        switch (self::$type) {
            case 0:
                AutoCodePreviewReport::$action_front_files[$classname]=$relative_path;
                break;
            case 1:
                AutoCodePreviewReport::$action_model_files[$classname]=$relative_path;
                break;
        }
        return self::saveDefineToDir(self::$action_dir_full,$filename,$definePhpFileContent);
    }
}
