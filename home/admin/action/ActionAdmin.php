<?php

/**
 * -----------| 后台管理所有控制器的父类 |-----------
 * @category Betterlife
 * @package core.admin
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class ActionAdmin extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();

        $globalPage = array(
            "admin.auth.register",
            "admin.auth.login"
        );

        if (!in_array($this->data["go"], $globalPage) && !HttpSession::isHave('user_id')) {
            $this->redirect("auth", "login");
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
