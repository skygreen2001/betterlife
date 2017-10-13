<?php
/**
 +---------------------------------------<br/>
 * 控制器:博客分类<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Category extends ActionModel
{
    /**
     * 博客分类列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Category::count();
        $this->view->countCategorys = $count;
        $categorys = null;
        if ( $count > 0 ) {
            $bb_page = TagPageService::init($nowpage,$count);
            $categorys = Category::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
        }
        $this->view->set("categorys", $categorys);
    }
    /**
     * 查看博客分类
     */
    public function view()
    {
        $categoryId = $this->data["id"];
        $category = Category::get_by_id($categoryId);
        $this->view->set("category", $category);
    }
    /**
     * 编辑博客分类
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $category = $this->model->Category;
            $id = $category->getId();
            $isRedirect=true;
            if (!empty($_FILES)&&!empty($_FILES["icon_url"]["name"])){
                $result=$this->uploadImg($_FILES, "icon_url", "icon_url", "category");
                if ($result&&($result['success']==true)){
                    if (array_key_exists('file_name',$result))$category->icon_url = $result['file_name'];
                }else{
                    $isRedirect=false;
                    $this->view->set("message",$result["msg"]);
                }
            }
            if ( !empty($id) ) {
                $category->update();
            } else {
                $id = $category->save();
            }
            if ($isRedirect){
                $this->redirect("category", "view", "id=$id");
                exit;
            }
        }
        $categoryId = $this->data["id"];
        $category = Category::get_by_id($categoryId);
        $this->view->set("category", $category);
        //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
        $this->load_onlineditor('intro');
    }
    /**
     * 删除博客分类
     */
    public function delete()
    {
        $categoryId = $this->data["id"];
        $isDelete = Category::deleteByID($categoryId);
        $this->redirect("category", "lists", $this->data);
    }
}

