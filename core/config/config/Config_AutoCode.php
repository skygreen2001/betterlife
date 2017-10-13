<?php
/**
 +---------------------------------------<br/>
 * 批量生成代码的配置<br/>
 +---------------------------------------
 * @category ele
 * @package core.config.common
 * @author skygreen
 */
class Config_AutoCode extends ConfigBB
{
    /**
     * 完整生成模式，需要时间较长，需调整php.ini中的执行时间参数
     * 主要是因为中间表的has_many生成后台显示js花费时间较长。
     */
    const AUTOCONFIG_CREATE_FULL  = true;
    /**
     * 每次都生成代码生成配置文件
     */
    const ALWAYS_AUTOCODE_XML_NEW = false;
    /**
     * 显示前期报告
     */
    const SHOW_PREVIEW_REPORT   = true;
    /**
     * 显示前台生成报告
     */
    const SHOW_REPORT_FRONT     = true;
    /**
     * 显示后台生成报告
     */
    const SHOW_REPORT_ADMIN     = true;
    /**
     * 工程重用为MINI后,只需要生成实体类
     */
    const ONLY_DOMAIN           = false;
    /**
     * 生成后台管理忽略编辑的字段
     */
    const IS_NOT_EDIT_COLUMN    = array(
        "status"
    );
    /**
     * 生成后台管理菜单表分组对应菜单组名称
     */
    const GROUP_ADMIN_MENU_TEXT = array(
        // "core" => "核心业务",
        "user" => "用户管理",
        "dic"  => "字典管理",
        "log"  => "日志管理",
        "msg"  => "消息管理"
    );
    /**
     * 工程核心表分组
     */
    const GROUP_ADMIN_MENU_CORE = "core";
}
?>
