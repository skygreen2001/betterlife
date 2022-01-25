<?php

/**
 * -----------| 系统管理人员 |-----------
 * @category Betterlife
 * @package domain.user
 * @author skygreen skygreen2001@gmail.com
 */
class Admin extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 管理员标识
     * @var int
     * @access public
     */
    public $admin_id;
    /**
     * 部门标识
     * @var int
     * @access public
     */
    public $department_id;
    /**
     * 用户名
     * @var string
     * @access public
     */
    public $username;
    /**
     * 真实姓名
     * @var string
     * @access public
     */
    public $realname;
    /**
     * 密码
     * @var string
     * @access public
     */
    public $password;
    /**
     * 扮演角色
     * - 系统管理员扮演角色。
     * - 0:超级管理员-superadmin
     * - 1:管理人员-manager
     * - 2:运维人员-normal
     * - 3:合作伙伴-partner
     * @var enum
     * @access public
     */
    public $roletype;
    /**
     * 视野
     * - 0:只能查看自己的信息-self
     * - 1:查看所有的信息-all
     * @var enum
     * @access public
     */
    public $seescope;
    /**
     * 登录次数
     * @var int
     * @access public
     */
    public $loginTimes;
    //</editor-fold>

    /**
     * 从属一对一关系
     * @var array
     */
    public static $belong_has_one = array(
        "department" => "Department"
    );

    /**
     * 显示扮演角色
     * - 系统管理员扮演角色。
     * - 0:超级管理员-superadmin
     * - 1:管理人员-manager
     * - 2:运维人员-normal
     * - 3:合作伙伴-partner
     * @return string
     */
    public function getRoletypeShow()
    {
        return self::roletypeShow($this->roletype);
    }

    /**
     * 显示视野
     * - 0:只能查看自己的信息-self
     * - 1:查看所有的信息-all
     * @return string
     */
    public function getSeescopeShow()
    {
        return self::seescopeShow($this->seescope);
    }

    /**
     * 显示扮演角色
     * - 系统管理员扮演角色。
     * - 0:超级管理员-superadmin
     * - 1:管理人员-manager
     * - 2:运维人员-normal
     * - 3:合作伙伴-partner
     * @return string
     */
    public static function roletypeShow($roletype)
    {
        return EnumRoletype::roletypeShow($roletype);
    }

    /**
     * 显示视野
     * - 0:只能查看自己的信息-self
     * - 1:查看所有的信息-all
     * @return string
     */
    public static function seescopeShow($seescope)
    {
        return EnumSeescope::seescopeShow($seescope);
    }
}
