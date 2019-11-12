<?php
/**
 +---------------------------------------<br/>
 * 服务类:所有Service的管理类<br/>
 +---------------------------------------
 * @category betterlife
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class Manager_Service extends Manager
{
    private static $userService;
    private static $blogService;
    /**
     * 提供服务:用户
     */
    public static function userService()
    {
        if ( self::$userService == null ) {
            self::$userService = new ServiceUser();
        }
        return self::$userService;
    }

    /**
     * 提供服务:博客
     */
    public static function blogService()
    {
        if ( self::$blogService == null ) {
            self::$blogService = new ServiceBlog();
        }
        return self::$blogService;
    }
}
