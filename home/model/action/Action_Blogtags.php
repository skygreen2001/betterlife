<?php

/**
 * -----------| 控制器:博客标签 |-----------
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
        if ($this->isDataHave( TagPageService::$linkUrl_pageFlag )) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Blogtags::count();
        $this->view->countBlogtagss = $count;
        $blogtagss = null;
        if ($count > 0) {
            $bb_page = TagPageService::init($nowpage,$count);
            $blogtagss = Blogtags::queryPage( $bb_page->getStartPoint(), $bb_page->getEndPoint() );
        }
        $this->view->set( "blogtagss", $blogtagss );
    }
    /**
     * 查看博客标签
     */
    public function view()
    {
        $blogtagsId = $this->data["id"];
        $blogtags   = Blogtags::getById( $blogtagsId );
        $this->view->set( "blogtags", $blogtags );
    }
    /**
     * 编辑博客标签
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $blogtags = $this->model->Blogtags;
            $id         = $blogtags->getId();
            $isRedirect = true;
            if (!empty($id)) {
                $blogtags->update();
            } else {
                $id = $blogtags->save();
            }
            if ($isRedirect) {
                $this->redirect( "blogtags", "view", "id=$id" );
                exit;
            }
        }
        $blogtagsId = $this->data["id"];
        $blogtags   = Blogtags::getById( $blogtagsId );
        $this->view->set( "blogtags", $blogtags );
        $blogs = Blog::get( "", "blog_id asc" );
        $this->view->set( "blogs", $blogs );
        $tagss = Tags::get( "", "tags_id asc" );
        $this->view->set( "tagss", $tagss );
    }
    /**
     * 删除博客标签
     */
    public function delete()
    {
        $blogtagsId = $this->data["id"];
        $isDelete = Blogtags::deleteByID( $blogtagsId );
        $this->redirect( "blogtags", "lists", $this->data );
    }
}

