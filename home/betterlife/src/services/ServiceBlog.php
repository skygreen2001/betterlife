<?php
/**
 +---------------------------------------<br/>
 * 服务类:博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class ServiceBlog extends Service implements IServiceBasic 
{
    /**
     * 保存数据对象:博客
     * @param array|DataObject $blog
     * @return int 保存对象记录的ID标识号
     */
    public function save($blog)
    {
        if (is_array($blog)) {
            $blog = new Blog($blog);
        }
        if ($blog instanceof Blog) {
            return $blog->save();
        } else {
            return false;
        }
    }

    /**
     * 更新数据对象 :博客
     * @param array|DataObject $blog
     * @return boolen 是否更新成功；true为操作正常
     */
    public function update($blog)
    {
        if (is_array($blog)) {
            $blog = new Blog($blog);
        }
        if ($blog instanceof Blog) {
            return $blog->update();
        } else {
            return false;
        }
    }

    /**
     * 由标识删除指定ID数据对象 :博客
     * @param int $id 数据对象:博客标识
     * @return boolen 是否删除成功；true为操作正常
     */
    public function deleteByID($id)
    {
        return Blog::deleteByID($id);
    }

    /**
     * 根据主键删除数据对象:博客的多条数据记录
     * @param array|string $ids 数据对象编号
     * 形式如下:
     * 1.array:array(1,2,3,4,5)
     * 2.字符串:1,2,3,4 
     * @return boolen 是否删除成功；true为操作正常
     */
    public function deleteByIds($ids)
    {
        return Blog::deleteByIds($ids);
    }

    /**
     * 对数据对象:博客的属性进行递增
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
     * @param string $property_name 属性名称 
     * @param int $incre_value 递增数 
     * @return boolen 是否操作成功；true为操作正常
     */
    public function increment($filter=null, $property_name, $incre_value)
    {
        return Blog::increment($filter, $property_name, $incre_value);
    }

    /**
     * 对数据对象:博客的属性进行递减
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
     * @param string $property_name 属性名称 
     * @param int $decre_value 递减数 
     * @return boolen 是否操作成功；true为操作正常
     */
    public function decrement($filter=null, $property_name, $decre_value)
    {
        return Blog::decrement($filter, $property_name, $decre_value);
    }

    /**
     * 查询数据对象:博客需显示属性的列表
     * @param string $columns 指定的显示属性，同SQL语句中的Select部分。 
     * 示例如下：<br/>
     *    id,name,commitTime
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/> 
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下：<br/>
     *      0,10<br/>
     * @return 数据对象:博客列表数组
     */
    public function select($columns, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {
        return Blog::select($columns, $filter, $sort, $limit);
    }

    /**
     * 查询数据对象:博客的列表
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/> 
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下：<br/>
     *      0,10<br/>
     * @return 数据对象:{object_desc}列表数组
     */
    public function get($filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {
        return Blog::get($filter, $sort, $limit);
    }

    /**
     * 查询得到单个数据对象:博客实体
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/> 
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @return 单个数据对象:博客实体
     */
    public function get_one($filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        return Blog::get_one($filter, $sort);
    }

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列] 
     * @param string $id  
     * @return 单个数据对象:博客实体
     */
    public function get_by_id($id)
    {
        return Blog::get_by_id($id);
    }

    /**
     * 数据对象:博客总计数
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/> 
     * @return 数据对象:博客总计数
     */
    public function count($filter=null)
    {
        return Blog::count($filter);
    }

    /**
     * 数据对象:博客分页查询
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件，在where后的条件<br/> 
     * 示例如下：<br/>
     * 0."id=1,name='sky'"<br/>
     * 1.array("id=1","name='sky'")<br/> 
     * 2.array("id"=>"1","name"=>"sky")<br/>
     * 3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/> 
     * @param string $sort 排序条件<br/>
     * 默认为 id desc<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @return 数据对象:博客分页查询列表
     */

    public function queryPage($startPoint, $endPoint, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        return Blog::queryPage($startPoint, $endPoint, $filter, $sort);
    }

    /**
     * 直接执行SQL语句
     * @return array
     *  1.执行查询语句返回对象数组
     *  2.执行更新和删除SQL语句返回执行成功与否的true|null
     */
    public function sqlExecute()
    {
        return self::dao()->sqlExecute("select * from " . Blog::tablename(), Blog::classname_static());
    }

    /**
     * 批量上传博客
     * @param mixed $upload_file <input name="upload_file" type="file">
     */
    public function import($files)
    {
        $diffpart = date("YmdHis");
        if (!empty($files["upload_file"])) {
            $tmptail = end(explode('.', $files["upload_file"]["name"]));
            $uploadPath = GC::$attachment_path . "blog" . DS . "import" . DS . "blog$diffpart . $tmptail";
            $result = UtilFileSystem::uploadFile($files, $uploadPath);
            if ($result && ($result['success'] == true)) {
                if (array_key_exists('file_name', $result)) {
                    $arr_import_header = self::fieldsMean(Blog::tablename());
                    $data = UtilExcel::exceltoArray($uploadPath, $arr_import_header);
                    $result = false;
                    foreach ($data as $blog) {
                        if (!is_numeric($blog["user_id"])){
                            $user_r=User::get_one("username='".$blog["user_id"]."'");
                            if ($user_r) $blog["user_id"]=$user_r->user_id;
                        }
                        if (!is_numeric($blog["category_id"])){
                            $category_r=Category::get_one("name='".$blog["category_id"]."'");
                            if ($category_r) $blog["category_id"]=$category_r->category_id;
                        }
                        $blog = new Blog($blog);
                if (!EnumBlogStatus::isEnumValue($blog->status)){
                    $blog->status=EnumBlogStatus::statusByShow($blog->status);
                }
                        $blog_id = $blog->getId();
                        if (!empty($blog_id)) {
                            $hadBlog = Blog::existByID($blog->getId());
                            if ($hadBlog) {
                                $result = $blog->update();
                            } else {
                                $result=$blog->save();
                            }
                        } else {
                            $result = $blog->save();
                        }
                    }
                } else {
                    $result=false;
                }
            }else{
                return $result;
            }
        }
        return array(
            'success' => true,
            'data'  => $result
        );
    }

    /**
     * 导出博客
     * @param mixed $filter
     */
    public function exportBlog($filter=null)
    {
        if ($filter) $filter = $this->filtertoCondition($filter);
        $data = Blog::get($filter);
        if ((!empty($data)) && (count($data) > 0))
        {
            Blog::propertyShow($data, array('status'));
        }
        $arr_output_header= self::fieldsMean(Blog::tablename()); 
        foreach ($data as $blog) {
            if ($blog->statusShow) {
                $blog['status'] = $blog->statusShow;
            }
            $user_instance=null;
            if ($blog->user_id){
                $user_instance=User::get_by_id($blog->user_id);
                $blog['user_id']=$user_instance->username;
            }
            $category_instance=null;
            if ($blog->category_id){
                $category_instance=Category::get_by_id($blog->category_id);
                $blog['category_id']=$category_instance->category_name;
            }
        }
        unset($arr_output_header['updateTime'], $arr_output_header['commitTime']);
        $diffpart = date("YmdHis");
        $outputFileName = Gc::$attachment_path . "export" . DS . "blog" . DS . "$diffpart.xls"; 
        UtilExcel::arraytoExcel($arr_output_header, $data, $outputFileName, false); 
        $downloadPath = Gc::$attachment_url . "export/blog/$diffpart.xls"; 
        return array(
            'success' => true,
            'data'  => $downloadPath
        ); 
    }

}

