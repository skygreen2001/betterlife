<?php

/**
 * -----------| 报表系统所有控制器的父类 |-----------
 * @category betterlife
 * @package core.admin
 * @author skygreen
 */
class ActionReport extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();

//        $globalPage = array(
//            "report.auth.register",
//            "report.auth.login"
//        );
//
//        if (!in_array($this->data["go"], $globalPage) && !HttpSession::isHave( 'user_id' )) {
//            $this->redirect("auth","login");
//        }
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }
}
