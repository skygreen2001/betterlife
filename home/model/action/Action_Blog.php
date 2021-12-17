<?php
/**
 * -----------| 控制器:博客 |-----------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Blog extends ActionModel
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
        $blogs = null;
        if ( $count > 0 ) {
            $bb_page = TagPageService::init($nowpage,$count);
            $blogs = Blog::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
        }
        $this->view->set("blogs", $blogs);
    }
    /**
     * 查看博客
     */
    public function view()
    {
        $blogId = $this->data["id"];
        $blog = Blog::get_by_id($blogId);
        $this->view->set("blog", $blog);
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
            if (!empty($_FILES)&&!empty($_FILES["icon_url"]["name"])){
                $result=$this->uploadImg($_FILES, "icon_url", "icon_url", "blog");
                if ($result&&($result['success']==true)){
                    if (array_key_exists('file_name',$result))$blog->icon_url = $result['file_name'];
                }else{
                    $isRedirect=false;
                    $this->view->set("message",$result["msg"]);
                }
            }
            if ( !empty($id) ) {
                $blog->update();
            } else {
                $id = $blog->save();
            }
            $blogTags = $this->data["tags_id"];
            Blogtags::saveDeleteRelateions( "blog_id", $id, "tags_id", $blogTags );
            if ( $isRedirect ){
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
        $isDelete = Blog::deleteByID($blogId);
        $this->redirect("blog", "lists", $this->data);
    }
}

