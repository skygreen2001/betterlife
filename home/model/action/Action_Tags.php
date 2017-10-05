<?php
/**
 +---------------------------------------<br/>
 * 控制器:标签<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Tags extends ActionModel
{
    /**
     * 标签列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Tags::count();
        $this->view->countTagss = $count;
        $this->view->set("tagss", NULL);
        if ( $count > 0 ) {
            $bb_page = TagPageService::init($nowpage,$count);
            $tagss = Tags::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
            $this->view->set("tagss", $tagss);
        }
    }
    /**
     * 查看标签
     */
    public function view()
    {
        $tagsId = $this->data["id"];
        $tags = Tags::get_by_id($tagsId);
        $this->view->set("tags", $tags);
    }
    /**
     * 编辑标签
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $tags = $this->model->Tags;
            $id = $tags->getId();
            $isRedirect=true;
            if (!empty($id)){
                $tags->update();
            }else{
                $id = $tags->save();
            }
            if ($isRedirect){
                $this->redirect("tags", "view", "id=$id");
                exit;
            }
        }
        $tagsId = $this->data["id"];
        $tags = Tags::get_by_id($tagsId);
        $this->view->set("tags", $tags);
    }
    /**
     * 删除标签
     */
    public function delete()
    {
        $tagsId = $this->data["id"];
        $isDelete = Tags::deleteByID($tagsId);
        $this->redirect("tags", "lists", $this->data);
    }
}

