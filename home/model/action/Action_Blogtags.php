<?php
/**
 +---------------------------------------<br/>
 * 控制器:博客标签<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Blogtags extends ActionModel
{
    /**
     * 博客标签列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Blogtags::count();
        $this->view->countBlogtagss = $count;
        $this->view->set("blogtagss", NULL);
        if ( $count > 0 ) {
            $bb_page = TagPageService::init($nowpage,$count);
            $blogtagss = Blogtags::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
            foreach ($blogtagss as $blogtags) {
                $blog_instance = null;
                if ($blogtags->blog_id) {
                    $blog_instance = Blog::get_by_id($blogtags->blog_id);
                    $blogtags['blog_name'] = $blog_instance->blog_name;
                }
                $tags_instance = null;
                if ($blogtags->tags_id) {
                    $tags_instance = Tags::get_by_id($blogtags->tags_id);
                    $blogtags['title'] = $tags_instance->title;
                }
            }
            $this->view->set("blogtagss", $blogtagss);
        }
    }
    /**
     * 查看博客标签
     */
    public function view()
    {
        $blogtagsId = $this->data["id"];
        $blogtags = Blogtags::get_by_id($blogtagsId);
        $blog_instance = null;
        if ($blogtags->blog_id) {
            $blog_instance = Blog::get_by_id($blogtags->blog_id);
            $blogtags['blog_name'] = $blog_instance->blog_name;
        }
        $tags_instance = null;
        if ($blogtags->tags_id) {
            $tags_instance = Tags::get_by_id($blogtags->tags_id);
            $blogtags['title'] = $tags_instance->title;
        }
        $this->view->set("blogtags", $blogtags);
    }
    /**
     * 编辑博客标签
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $blogtags = $this->model->Blogtags;
            $id = $blogtags->getId();
            $isRedirect=true;
            if (!empty($id)){
                $blogtags->update();
            }else{
                $id = $blogtags->save();
            }
            if ($isRedirect){
                $this->redirect("blogtags", "view", "id=$id");
                exit;
            }
        }
        $blogtagsId = $this->data["id"];
        $blogtags = Blogtags::get_by_id($blogtagsId);
        $this->view->set("blogtags", $blogtags);
    }
    /**
     * 删除博客标签
     */
    public function delete()
    {
        $blogtagsId = $this->data["id"];
        $isDelete = Blogtags::deleteByID($blogtagsId);
        $this->redirect("blogtags", "lists", $this->data);
    }
}

