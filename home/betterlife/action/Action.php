<?php

/**
 * -----------| 所有前端控制器的父类 |-----------
 * @category Betterlife
 * @package core.model
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class Action extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();
        $globalPage = array(
            Gc::$appName . ".auth.register",
            Gc::$appName . ".auth.login",
            Gc::$appName . ".index.index"
        );
        if (!in_array($this->data["go"], $globalPage) && !HttpSession::isHave('user_id')) {
            // $this->redirect("auth","login");
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
