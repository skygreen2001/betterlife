<?php
/**
 +----------------------------------------------<br/>
 * 后台管理所有控制器的父类<br/>
 +----------------------------------------------
 * @category betterlife
 * @package core.admin
 * @author skygreen
 */
class ActionAdmin extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();

        if(($this->data["go"]!="admin.auth.register")&&($this->data["go"]!="admin.auth.login")&&!HttpSession::isHave('user_id')) {
            $this->redirect("auth","login");
        }
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }
}
