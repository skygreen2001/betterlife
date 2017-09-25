<?php
/**
 +---------------------------------------<br/>
 * 控制器:博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Blog extends ActionAdmin
{
    /**
     * 博客列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Blog::count();
        $this->view->countBlogs = $count;
        $this->view->set("blogs", NULL);
        if ($count>0) {
            $bb_page = TagPageService::init($nowpage,$count);
            $blogs = Blog::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
            foreach ($blogs as $blog) {
                $user_instance = null;
                if ($blog->user_id) {
                    $user_instance = User::get_by_id($blog->user_id);
                    $blog['username'] = $user_instance->username;
                }
            }
            $this->view->set("blogs", $blogs);
        }
    }
    /**
     * 查看博客
     */
    public function view()
    {
        $blogId = $this->data["id"];
        $blog = Blog::get_by_id($blogId);
        $user_instance = null;
        if ($blog->user_id) {
            $user_instance = User::get_by_id($blog->user_id);
            $blog['username'] = $user_instance->username;
        }
        if (!empty($blog->icon_url)){
          $blog->icon_url = Gc::$upload_url . "images/" . $blog->icon_url;
        }
        $this->view->set("blog", $blog);
    }
    /**
     * 编辑博客
     */
    public function edit()
    {
        if ( !empty($_POST) ) {
            $blog = $this->model->Blog;
            $id   = $blog->getId();
            $isRedirect = true;
            if ( !empty($_FILES) && !empty($_FILES["icon_url"]["name"]) ){
                $result = $this->uploadImg($_FILES, "icon_url", "icon_url", "blog");
                if ( $result&&($result['success'] == true) ){
                    if ( array_key_exists('file_name',$result) )$blog->icon_url = $result['file_name'];
                } else {
                    $isRedirect = false;
                    $this->view->set("message",$result["msg"]);
                }
            }
            if (!empty($id)){
                if ($blog->isPublic == 'on') $blog->isPublic = true; else $blog->isPublic = false;
                $blog->update();
            }else{
                $id = $blog->save();
            }

            $blogcategorys = $this->data["categoryId"];
            $blogcategorys_db = Blogcategory::select("category_id", "blog_id = " . $id);
            if ($blogcategorys_db) {
                //添加数据库里没有的
                foreach ($blogcategorys as $category_id) {
                    if (!in_array($category_id, $blogcategorys_db)){
                        $blogcategory = new Blogcategory(
                            array(
                              "blog_id" => $id,
                              "category_id" => $category_id
                            )
                        );
                        $blogcategory->save();
                    }
                }
                //删除用户取消的选择
                foreach ($blogcategorys_db as $category_id) {
                    if (!in_array($category_id, $blogcategorys)){
                        Blogcategory::deleteBy( "category_id =" . $category_id );
                    }
                }
            } else {
                foreach ($blogcategorys as $blogcategory) {
                    $blogcategory = new Blogcategory(
                        array(
                          "blog_id" => $id,
                          "category_id" => $blogcategory
                        )
                    );
                    $blogcategory->save();
                }
            }

            if ($isRedirect){
                $this->redirect("blog", "view", "id=$id");
                exit;
            }
        }
        $blogId = $this->data["id"];
        $blog   = Blog::get_by_id($blogId);
        $categorys = Category::get();
        $this->view->set("blog", $blog);
        $this->view->set("categorys", $categorys);
        if ($blog) {
            $blogCategorys = Blogcategory::select("category_id", "blog_id = " . $blogId);
            $this->view->set("blogCategorys", $blogCategorys);
        }
        //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
        $this->load_onlineditor('blog_content');
    }
    /**
     * 删除博客
     */
    public function delete()
    {
        $blogId = $this->data["id"];
        $isDelete = Blog::deleteByID($blogId);
    }
}
