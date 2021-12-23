<?php
require_once("../../init.php");
//微信api接入验证
function index() {
    //获得几个参数
    $appId          = "公众号第三方用户唯一凭证 appid";
    $encodingAesKey = "消息加解密密钥";
    $token          = 'BB2019b';//此处填写之前开发者配置的token
    $nonce     = $_GET['nonce']; //随机数
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //参数字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //验证
    $str = sha1(implode($array));//sha1加密
    //对比验证处理好的str与signature,若确认此次GET请求来自微信服务器，请原样返回echostr参数内容，则接入生效，成为开发者成功，否则接入失败。
    if ( $str == $signature && $echostr ) {
        echo $echostr;
    } else {
        //接入成功后的其他处理
        // $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; // 虚拟机可能禁止register_globals导致无法获取body数据
        $postStr = file_get_contents("php://input");
        if ( !empty($postStr) ) {
            libxml_disable_entity_loader(true);//安全防护
            $postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername   = $postObj->ToUserName;
            $keyword      = trim($postObj->Content);
            $time         = time();
            $textTpl      = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
                             </xml>";
            if ( !empty($keyword) )
            {
                $msgType = "text";
                $keyword = trim($keyword);
                //用户给公众号发消息后，公众号被动(自动)回复的消息内容
                $resultStr  = "";
                $contentStr = "欢迎来到". Gc::$site_name . "! 请确认您输入的是: " . $keyword;
                $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                if ( empty($resultStr) ) $resultStr = "您输入的信息不正确，请确认再输入！";
                echo $resultStr;
            } else {
                echo "请输入...";
            }
        } else {
          echo "";
          exit;
        }
    }
}
index();
