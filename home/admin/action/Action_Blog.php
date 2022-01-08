<?php

/**
 * -----------| 控制器:博客 |-----------
 * @category betterlife
 * @package web.back.admin
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Blog extends ActionAdmin
{
    /**
     * 博客列表
     */
    public function lists()
    {
        
    }
    /**
     * 查看博客
     */
    public function view()
    {
        $blogId = $this->data["id"];
        $blog   = Blog::get_by_id( $blogId );
        if (!empty($blog->icon_url)) {
            $blog->icon_url = Gc::$upload_url . "images/" . $blog->icon_url;
        }
        $this->view->set( "blog", $blog );
    }
    /**
     * 编辑博客
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $blog = $this->model->Blog;
            $id = $blog->getId();
            $isRedirect = true;
            if (!empty($_FILES) && !empty($_FILES["icon_url"]["name"])) {
                $result = $this->uploadImg($_FILES, "icon_url", "icon_url", "blog");
                if ($result && ($result['success'] == true )) {
                    if (array_key_exists('file_name', $result) ) $blog->icon_url = $result['file_name'];
                } else {
                    $isRedirect = false;
                    $this->view->set( "message", $result["msg"] );
                }
            }
            if ($blog->isPublic == 'on' ) $blog->isPublic = 1; else $blog->isPublic = 0;
            if (!empty($id)) {
                $blog->update();
            } else {
                $id = $blog->save();
            }
            $blogTags = $this->data["tags_id"];
            Blogtags::saveDeleteRelateions( "blog_id", $id, "tags_id", $blogTags );
            if ($isRedirect) {
                $this->redirect( "blog", "view", "id=$id" );
                exit;
            }
        }
        $blogId = $this->data["id"];
        $blog   = Blog::get_by_id( $blogId );
        $this->view->set( "blog", $blog );
        $users = User::get( "", "user_id asc" );
        $this->view->set( "users", $users );
        $categorys = Category::get( "", "category_id asc" );
        $this->view->set( "categorys", $categorys );
        //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
        $this->load_onlineditor( 'blog_content' );
    }
    /**
     * 删除博客
     */
    public function delete()
    {
        $blogId = $this->data["id"];
        $isDelete = Blog::deleteByID( $blogId );
        return array("info" => 200, "data" => $blogId);
    }

    /**
     * 批量上传博客
     * @param mixed $upload_file <input name="upload_file" type="file">
     */
    public function import()
    {
        if (!empty($_FILES)) {
            return Manager_Service::blogService()->import( $_FILES );
        }
        return array("error" => 500,"info" => "No Data");
    }

    /**
     * 导出博客
     */
    public function export()
    {
        $filter_name = "blog_name";
        $filter      = null;
        if (!empty($filter_name) && !empty($this->data["query"])) {
            $filter = array($filter_name => $this->data["query"]);
        }
        // $filter = " blog_name like '%" . $this->data["query"] . "%' or blog_content like '%" . $this->data["query"] . "%'" ;
        // LogMe::log("filter:" . print_pre($filter));
        return Manager_Service::blogService()->exportBlog( $filter );
    }
}

