<?php

/**
 * -----------| 控制器:Ajax请求服务 |-----------
 * @category Betterlife
 * @package  web.front
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Ajax extends Action
{
    /**
     * 仅供测试:Ajax请求返回字符串
     */
    public function test()
    {
        return "Hello world!";
    }
    /**
     * 仅供测试:Ajax请求返回json格式字符串
     */
    public function index()
    {
        $result = array(
            "id" => 1,
            "ti" => "标题缩写",
            "hi" => "Welcome to ajax!",
            "ha" => "^_^"
        );
        return $result;
    }
}
