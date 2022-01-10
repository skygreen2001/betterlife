<?php

/**
 * -----------| 所有管理类的父类 |-----------
 * @category Betterlife
 * @package core
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class Manager extends BBObject
{
    public function __clone()
    {
        trigger_error('不允许Clone本管理类.', E_USER_ERROR);
    }
}
