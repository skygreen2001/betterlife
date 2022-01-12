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
    /**
     * 是否加载调试板
     *
     * @var boolean
     */
    public static $isLoadDebugbar   = false;
    public static $debugbar         = null;
    public static $debugbarRenderer = null;

    /**
     * 记录需调试显示的信息
     * @param $message 需调试显示的信息
     * @param int $level 调试信息级别, 1: info, 2: warning, 3: error
     * @return void
     */
    public static function info($message, $level = 1)
    {
        if (Gc::$dev_debug_on) {
            if (self::$isShowDebug && self::$isLoadDebugbar) {
                $label = "info";
                switch ($level) {
                    case 2:
                        $label = "warning";
                        break;
                    case 3:
                        $label = "error";
                        break;
                    default:
                        break;
                }
                // self::$debugbar["messages"]->addMessage($message, $label);
                self::$debugbar["betterlife"]->addMessage($message, $label);
            } else {
                if ($level == 1) {
                    echo $message . BR;
                } elseif ($level == 2) {
                    echo "<font color='#3c753c'>" . $message . "</font>" . BR;
                } else {
                    die($message);
                }
            }
        }
    }

    public static function start()
    {
        if (Gc::$dev_debug_on && self::$isShowDebug) {
            // self::$debugbar = new DebugBar\StandardDebugBar();
            self::$debugbar = new DebugBar\DebugBar();
            $debug_collector = new DebugBar\DataCollector\MessagesCollector('betterlife');
            self::$debugbar->addCollector($debug_collector);
            self::$debugbar->addCollector(new DebugBar\DataCollector\PhpInfoCollector());
            // self::$debugbar->addCollector(new DebugBar\DataCollector\MessagesCollector());
            self::$debugbar->addCollector(new DebugBar\DataCollector\RequestDataCollector());
            self::$debugbar->addCollector(new DebugBar\DataCollector\TimeDataCollector());
            self::$debugbar->addCollector(new DebugBar\DataCollector\MemoryCollector());
            self::$debugbar->addCollector(new DebugBar\DataCollector\ExceptionsCollector());

            // self::$debugbar['messages']->aggregate(self::$debugbar['betterlife']);
            // print_pre(self::$debugbar->getCollectors(), true);
            $baseUrl = 'install/vendor/maximebf/debugbar/src/DebugBar/Resources';
            self::$debugbarRenderer = self::$debugbar->getJavascriptRenderer()
                                                     ->setBaseUrl($baseUrl)
                                                     ->setEnableJqueryNoConflict(true);
            // print_pre(self::$debugbarRenderer->getAssets(), true);
            // self::$debugbarRenderer->disableControl("messages");
            echo self::$debugbarRenderer->renderHead();
            self::$isLoadDebugbar = true;
            self::info("您好, 欢迎来 Betterlife 玩!");
        }
    }

    public static function end()
    {
        if (Gc::$dev_debug_on && self::$isShowDebug) {
            echo self::$debugbarRenderer->render();
        }
    }
}
