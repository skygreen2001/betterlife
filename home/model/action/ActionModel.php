<?php
/**
 * -----------| 所有Model应用控制器的父类 |-----------
 * @category betterlife
 * @package web.model
 * @author skygreen skygreen2001@gmail.com
 */
class ActionModel extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }
    
}
