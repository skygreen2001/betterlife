<?php

/**
 * -----------| 常用函数 |-----------
 * @category Betterlife
 * @package include
 * @author skygreen2001 <skygreen2001@gmail.com>
 */

/**
 * 直接执行SQL语句
 * @param mixed $sql SQL查询语句
 * @param string|class|bool $object 需要生成注入的对象实体|类名称
 * @return mixed 默认返回数组,如果$object指定数据对象，返回指定数据对象列表，$object=true，返回stdClass列表。
 */
function sqlExecute($sqlstring, $object = null)
{
    if (empty($sqlstring)) {
        return null;
    }
    if ($object) {
        if (is_bool($object)) {
            $object = null;
        }
        return ManagerDb::newInstance()->currentdao()->sqlExecute($sqlstring, $object);
    } else {
        $lists = ManagerDb::newInstance()->currentdao()->sqlExecute($sqlstring, $object);
        if ($lists) {
            if (is_array($lists)) {
                if (count($lists) > 0) {
                    foreach ($lists as $key => $data) {
                        if (is_object($data)) {
                            $lists[$key] = (array) $data;
                        }
                    }
                }
            }
        }
        return $lists;
    }
}

/**
 * 查看字符串里是否包含指定字符串
 * @param string $subject
 * @param string $needle
 * @param boolean $is_strict 是否区分大小写，默认不区分
 * @return boolean 是否字符串里包含指定字符串。
 */
function contain($subject, $needle, $is_strict = false)
{
    if (empty($subject)) {
        return false;
    }
    if ($is_strict) {
        if (strpos($subject, $needle) !== false) {
            return true;
        } else {
            return false;
        }
    } else {
        if (strpos(strtolower($subject), strtolower($needle)) !== false) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * 查看字符串里是否包含数组中任意一个指定字符串
 * @param string $subject
 * @param array $array
 * @return boolean
 */
function contains($subject, $array)
{
    $result = false;
    if (!empty($array) && is_array($array)) {
        foreach ($array as $element) {
            if (contain($subject, $element)) {
                return true;
            }
        }
    }
    return $result;
}

/**
 * 需要的字符是否在目标字符串的开始
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function startWith($haystack, $needle, $strict = true)
{
    if (!$strict) {
        $haystack = strtoupper($haystack);
        $needle   = strtoupper($needle);
    }
    return strpos($haystack, $needle) === 0;
}

/**
 * 需要的字符是否在目标字符串的结尾
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function endWith($haystack, $needle, $strict = true)
{
    if (empty($needle)) {
        return false;
    }
    if (!empty(strrev($needle))) {
        if (!$strict) {
            $haystack = strtoupper($haystack);
            $needle   = strtoupper($needle);
        }
        return (strpos(strrev($haystack), strrev($needle)) === 0);
    }
    return false;
}

/**
 * 是否直接显示出来
 * @param @mixed $s 复合类型
 * @param boolean $isEcho 是否直接显示打印出来
 * @param string $title 标题, 当 $isEcho = true 才会有效
 * @return string 默认返回打印数据，当 $isEcho = true, 不返回数据直接打印到输出
 */
function print_pre($s, $isEcho = false, $title = "")
{
    if (!empty($title) && $isEcho) {
        echo $title . BR;
    }
    if ($isEcho) {
        print "<pre>";
        print_r($s);
        print "</pre>";
    } else {
        return "<pre>" . var_export($s, true) . "</pre>";
    }
}

/**
 * 设置处理所有未捕获异常的用户定义函数
 * @return void
 */
function e_me($exception)
{
    ExceptionMe::recordUncatchedException($exception);
    e_view();
}

/**
 * 显示异常处理缩写表示
 * @return void
 */
function e_view()
{
    if (Gc::$dev_debug_on) {
        echo ExceptionMe::showMessage(ExceptionMe::VIEW_TYPE_HTML_TABLE);
    }
}

/**
 * 导航至网站首页页面
 * @return void
 */
function header_index()
{
    $base_url = Gc::$url_base . "index.php?go=" . Gc::$appName . ".index.index";
    // header("location:" . $base_url); //修改成了复杂的url，这里不推荐

    $index_page = file_get_contents($base_url);
    print($index_page);
    die();
}

/**
 * 将字符串从unicode转为utf-8
 * @param string $str 原内容
 * @return string 新内容
 */
function unicode2utf8($str)
{
    if (!$str) {
        return $str;
    }
    $decode = json_decode($str);
    if ($decode) {
        return $decode;
    }
    $str    = '["' . $str . '"]';
    $decode = json_decode($str);
    if (count($decode) == 1) {
        return $decode[0];
    }
    return $str;
}

/**
 * js escape php 实现
 * 参考: PHP实现javascript的escape和unescape函数
 * @param $string the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 * @return string
 */
function escape($string, $in_encoding = 'UTF-8', $out_encoding = 'UCS-2')
{
    $return = '';
    if (function_exists('mb_get_info')) {
        for ($x = 0; $x < mb_strlen($string, $in_encoding); $x++) {
            $str = mb_substr($string, $x, 1, $in_encoding);
            if (strlen($str) > 1) { // 多字节字符
                $return .= '%u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
            } else {
                $return .= '%' . strtoupper(bin2hex($str));
            }
        }
    }
    return $return;
}

/**
 * js unescape php 实现
 * 参考: PHP实现javascript的escape和unescape函数
 * @param $string the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 * @return string
 */
function unescape($str)
{
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        if ($str[$i] == '%' && $str[$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f) {
                $ret .= chr($val);
            } elseif ($val < 0x800) {
                $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
            } else {
                $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
            }
            $i += 5;
        } else {
            if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }
        }
    }
    return $ret;
}

/**
 * 将Url param字符串转换成Json字符串
 * @link http://php.net/manual/en/function.parse-str.php
 * @example
 *  示例如下:
 *  $url_parms = 'title=hello&custLength=200&custWidth=300'
 * @return Json字符串
 */
function urlparamToJsonString($url_parms)
{
    parse_str($url_parms, $parsed);
    $result = json_encode($parsed);
    return $result;
}

/**
 * 专供Flex调试使用的Debug工具
 * @link http://www.adobe.com/cn/devnet/flex/articles/flex_php_05.html
 * @param mixed $var
 * @deprecated
 * @return void
 */
function flex_logme($var)
{
    $filename = dirname(__FILE__) . '/__log.txt';
    if (!$handle = fopen($filename, 'a')) {
        echo "Cannot open file ($filename)";
        return;
    }

    $toSave = var_export($var, true);
    fwrite($handle, "[" . date("y-m-d H:i:s") . "]");
    fwrite($handle, "\n");
    fwrite($handle, $toSave);
    fwrite($handle, "\n");
    fclose($handle);
}

/**
 * Simple conversion of HTML to plaintext.
 *
 * @see https://github.com/silverstripe/silverstripe-framework/blob/4/src/Core/Convert.php
 * @param string $data Input data
 * @param bool $preserveLinks
 * @param int $wordWrap
 * @param array $config
 * @return string
 */
function html2raw($data, $preserveLinks = false, $wordWrap = 0, $config = null)
{
    $defaultConfig = array('PreserveLinks' => false, 'ReplaceBoldAsterisk' => true, 'CompressWhitespace' => true, 'ReplaceImagesWithAlt' => true);
    if (isset($config)) {
        $config = array_merge($defaultConfig, $config);
    } else {
        $config = $defaultConfig;
    }
    $data = preg_replace("/<style([^A-Za-z0-9>][^>]*)?>.*?<\\/style[^>]*>/is", "", $data);
    $data = preg_replace("/<script([^A-Za-z0-9>][^>]*)?>.*?<\\/script[^>]*>/is", "", $data);
    if ($config['ReplaceBoldAsterisk']) {
        $data = preg_replace('%<(strong|b)( [^>]*)?>|</(strong|b)>%i', '*', $data);
    }
    // Expand hyperlinks
    if (!$preserveLinks && !$config['PreserveLinks']) {
        $data = preg_replace_callback('/<a[^>]*href\\s*=\\s*"([^"]*)">(.*?)<\\/a>/i', function ($matches) {
            return html2raw($matches[2]) . "[{$matches['1']}]";
        }, $data);
        $data = preg_replace_callback('/<a[^>]*href\\s*=\\s*([^ ]*)>(.*?)<\\/a>/i', function ($matches) {
            return html2raw($matches[2]) . "[{$matches['1']}]";
        }, $data);
    }
    // Replace images with their alt tags
    if ($config['ReplaceImagesWithAlt']) {
        $data = preg_replace('/<img[^>]*alt *= *"([^"]*)"[^>]*>/i', ' \\1 ', $data);
        $data = preg_replace('/<img[^>]*alt *= *([^ ]*)[^>]*>/i', ' \\1 ', $data);
    }
    // Compress whitespace
    if ($config['CompressWhitespace']) {
        $data = preg_replace("/\\s+/", " ", $data);
    }
    // Parse newline tags
    $data = preg_replace("/\\s*<[Hh][1-6]([^A-Za-z0-9>][^>]*)?> */", "\n\n", $data);
    $data = preg_replace("/\\s*<[Pp]([^A-Za-z0-9>][^>]*)?> */", "\n\n", $data);
    $data = preg_replace("/\\s*<[Dd][Ii][Vv]([^A-Za-z0-9>][^>]*)?> */", "\n\n", $data);
    $data = preg_replace("/\n\n\n+/", "\n\n", $data);
    $data = preg_replace("/<[Bb][Rr]([^A-Za-z0-9>][^>]*)?> */", "\n", $data);
    $data = preg_replace("/<[Tt][Rr]([^A-Za-z0-9>][^>]*)?> */", "\n", $data);
    $data = preg_replace("/<\\/[Tt][Dd]([^A-Za-z0-9>][^>]*)?> */", "    ", $data);
    $data = preg_replace('/<\\/p>/i', "\n\n", $data);
    // Replace HTML entities
    $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
    // Remove all tags (but optionally keep links)
    // strip_tags seemed to be restricting the length of the output
    // arbitrarily. This essentially does the same thing.
    if (!$preserveLinks && !$config['PreserveLinks']) {
        $data = preg_replace('/<\\/?[^>]*>/', '', $data);
    } else {
        $data = strip_tags($data, '<a>');
    }
    // Wrap
    if ($wordWrap) {
        $data = wordwrap(trim($data), $wordWrap);
    }
    return trim($data);
}

// /**
//  * Tests {@link html2raw()}
//  *
//  * 仅供测试函数: html2raw
//  */
// function testHtml2raw()
// {
//     $val1 = 'This has a <strong>strong tag</strong>.';
//     echo html2raw($val1); //This has a *strong tag*.
//     echo "<br/>";

//     $val1 = 'This has a <b class="test" style="font-weight: bold">b tag with attributes</b>.';
//     echo html2raw($val1); //This has a *b tag with attributes*.
//     echo "<br/>";

//     $val2 = 'This has a <strong class="test" style="font-weight: bold">strong tag with attributes</STRONG>.';
//     echo html2raw($val2); //This has a *strong tag with attributes*.
//     echo "<br/>";

//     $val3 = '<script type="application/javascript">Some really nasty javascript here</script>';
//     echo html2raw($val3); //Script tags are completely removed

//     $val4 = '<style type="text/css">Some really nasty CSS here</style>';
//     echo html2raw($val4); //Style tags are completely removed

//     $val5 = '<script type="application/javascript">Some really nasty
//                                                     multiline javascript here</script>';
//     echo html2raw($val5); //Multiline script tags are completely removed

//     $val6 = '<style type="text/css">Some really nasty
//     multiline CSS here</style>';
//     echo html2raw($val6); //Multiline style tags are completely removed

//     $val7 = '<p>That&#39;s absolutely correct</p>';
//     echo html2raw($val7); //Single quotes are decoded correctly
//     echo "<br/>";

//     $val8 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ' . 'incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ' . 'exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ' . 'irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla ' . 'pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia ' . 'deserunt mollit anim id est laborum.';
//     echo html2raw($val8); //Test long text is unwrapped
//     echo "<br/>";

//      $var9 = <<<PHP
// Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
// do eiusmod tempor incididunt ut labore et dolore magna
// aliqua. Ut enim ad minim veniam, quis nostrud exercitation
// ullamco laboris nisi ut aliquip ex ea commodo consequat.
// Duis aute irure dolor in reprehenderit in voluptate velit
// esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
// occaecat cupidatat non proident, sunt in culpa qui officia
// deserunt mollit anim id est laborum.
// PHP;
//     echo html2raw($val8, false, 60); //Test long text is unwrapped
//     echo "<br/>";
// }
