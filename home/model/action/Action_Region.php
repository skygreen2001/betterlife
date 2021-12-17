<?php
/**
 * -----------| 控制器:地区 |-----------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Region extends ActionModel
{
    /**
     * 地区列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)) {
            $nowpage = $this->data[TagPageService::$linkUrl_pageFlag];
        } else {
            $nowpage = 1;
        }
        $count = Region::count();
        $this->view->countRegions = $count;
        $regions = null;
        if ( $count > 0 ) {
            $bb_page = TagPageService::init($nowpage,$count);
            $regions = Region::queryPage($bb_page->getStartPoint(), $bb_page->getEndPoint());
        }
        $this->view->set("regions", $regions);
    }
    /**
     * 查看地区
     */
    public function view()
    {
        $regionId = $this->data["id"];
        $region = Region::get_by_id($regionId);
        $this->view->set("region", $region);
    }
    /**
     * 编辑地区
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $region = $this->model->Region;
            $id = $region->getId();
            $isRedirect=true;
            if ( !empty($id) ) {
                $region->update();
            } else {
                $id = $region->save();
            }
            if ($isRedirect){
                $this->redirect("region", "view", "id=$id");
                exit;
            }
        }
        $regionId = $this->data["id"];
        $region = Region::get_by_id($regionId);
        $this->view->set("region", $region);
        $region_ps = Region::get("", "region_id asc");
        $this->view->set("region_ps", $region_ps);
    }
    /**
     * 删除地区
     */
    public function delete()
    {
        $regionId = $this->data["id"];
        $isDelete = Region::deleteByID($regionId);
        $this->redirect("region", "lists", $this->data);
    }
}

