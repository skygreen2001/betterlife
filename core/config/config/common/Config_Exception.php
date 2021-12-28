<?php
/**
 * -----------| 异常常量 |-----------
 * @category betterlife
 * @package core.config.common
 * @author skygreen
 */
class Config_Exception extends ConfigBB
{
    /**
     * 异常处理方式
     *
     * 0. 自定义
     * 1. filp/whoops
     * 
     * @var int 
     */
    const EXCEPTION_WAY = 1;

    /**
     * filp/whoops配置: 异常文件打开编辑器
     * 
     * [Open Files In An Editor](https://github.com/filp/whoops/blob/master/docs/Open%20Files%20In%20An%20Editor.md)
     * 
     * 默认: vscode
     * 
     *     - vscode
     *     - atom
     *     - sublime
     *       - Mac OS需要添加: https://github.com/inopinatus/sublime_url
     *     - phpstorm
     *     - emacs
     *     - macvim
     *     - idea
     *     - netbeans
     *     - textmate
     *     - espresso
     *     - xdebug
     *     
     * @var string
     */
    const WHOOPS_EDITOR = "vscode";
}