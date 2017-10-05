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
                if ( $result && ( $result['success'] == true ) ){
                    if ( array_key_exists('file_name', $result) ) $blog->icon_url = $result['file_name'];
                } else {
                    $isRedirect = false;
                    $this->view->set("message",$result["msg"]);
                }
            }
            if ( !empty($id) ) {
                if ( $blog->isPublic == 'on' ) $blog->isPublic = true; else $blog->isPublic = false;
                $blog->update();
            } else {
                $id = $blog->save();
            }

            $blogTags = $this->data["tagsId"];
            Blogtags::saveDeleteRelateions( "blog_id", $id, "tags_id", $blogTags );

            if ($isRedirect){
                $this->redirect("blog", "view", "id=$id");
                exit;
            }
        }
        $blogId = $this->data["id"];
        $blog   = Blog::get_by_id($blogId);
        $this->view->set("blog", $blog);
        $categorys = Category::get();
        $tags      = Tags::get();
        $this->view->set("categorys", $categorys);
        $this->view->set("tags", $tags);
        if ($blog) {
            $blogTags = Blogtags::select( "tags_id", "blog_id = " . $blogId );
            $this->view->set("blogTags", $blogTags);
        }
        //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
        $this->load_onlineditor('blog_content');
    }
    /**
     * 删除博客
     */
    public function delete()
    {
        $blogId   = $this->data["id"];
        $isDelete = Blog::deleteByID($blogId);
    }
}
