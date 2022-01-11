<?php

/**
 * -----------| 调试处理类 |-----------
 *
 * @see [maximebf/debugbar](https://github.com/maximebf/php-debugbar)
 * @category Betterlife
 * @package core.system
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class DebugMe extends BBObject
{
    /**
     * 是否显示调试板
     *
     * @var boolean
     */
    public static $isShowDebug      = false;
    public static $debugbar         = null;
    public static $debugbarRenderer = null;

    /**
     * 记录需调试显示的信息
     * @param $message 需调试显示的信息
     * @return void
     */
    public static function info($message)
    {
        if (Gc::$dev_debug_on && self::$isShowDebug) {
            self::$debugbar["messages"]->addMessage($message);
        }
    }

    public static function start()
    {
        if (Gc::$dev_debug_on && self::$isShowDebug) {
            // maximebf/debugbar
            self::$debugbar = new DebugBar\StandardDebugBar();
            $baseUrl = 'install/vendor/maximebf/debugbar/src/DebugBar/Resources';
            self::$debugbarRenderer = self::$debugbar->getJavascriptRenderer($baseUrl);
            self::info("Hello, Welcome to Betterlife!");
            echo self::$debugbarRenderer->renderHead();
        }
    }

    public static function end()
    {
        if (Gc::$dev_debug_on && self::$isShowDebug) {
            echo self::$debugbarRenderer->render();
        }
    }
}
