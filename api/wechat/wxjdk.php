<?php
$url = $_GET['url'];
require_once("../../init.php");
/**
 * 公众号微信JDK授权接口
 * 
 * 微信JS-SDK文档: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#1
 * 
 * 附录6-DEMO页面和示例代码: http://demo.open.weixin.qq.com/jssdk 
 * 
 * 示例代码: http://demo.open.weixin.qq.com/jssdk/sample.zip
 * 
 * 备注:链接中包含php、java、nodejs以及python的示例代码供第三方参考，第三方切记要对获取的accesstoken以及jsapi_ticket进行缓存以确保不会触发频率限制。
 * 
 * 本地示例: api/common/wechat/wxjdk/
 */ 
class WxJsSDK {
    const APPID     = "公众号第三方用户唯一凭证 appid";
    const APPSECRET = "第三方用户唯一凭证密钥，即appsecret";
    private $appId;
    private $appSecret;
    private $url;
    public function __construct($appId, $appSecret, $url) {
        $this->appId     = $appId;
        $this->appSecret = $appSecret;
        $this->url       = $url;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        // $protocol    = ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";
        // $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url         = $this->url;
        $timestamp   = time();
        $nonceStr    = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string      = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature   = sha1($string);

        $signPackage = array(
          "appId"     => $this->appId,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $is_valid = true;
        $data     = null;

        if ( file_exists(Gc::$upload_path . "jsapi_ticket.json") ) {
            $data = json_decode(file_get_contents(Gc::$upload_url . "jsapi_ticket.json"));
            if ( $data->expire_time < time() ) {
                $is_valid = false;
            }
        } else {
            $is_valid = false;
        }
        if ( $is_valid ) {
            if ( $data == null ) $data = json_decode(file_get_contents(Gc::$upload_url . "jsapi_ticket.json"));
            $ticket = $data->jsapi_ticket;
        } else {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url    = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res    = json_decode($this->httpGet( $url ));
            $ticket = $res->ticket;
            if ( $ticket ) {
                $data->expire_time  = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen(Gc::$upload_url . "jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        }
        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $is_valid = true;
        $data     = null;

        if ( (file_exists(Gc::$upload_path . "access_token.json")) ) {
            $data = json_decode(file_get_contents(Gc::$upload_url . "access_token.json"));
            if ( $data->expire_time < time() ) {
                $is_valid = false;
            }
        } else {
            $is_valid = false;
        }
        if ( $is_valid ) {
            if ( $data == null ) $data = json_decode(file_get_contents(Gc::$upload_url . "access_token.json"));
            $access_token = $data->access_token;
        } else {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res          = json_decode($this->httpGet( $url ));
            $access_token = $res->access_token;
            if ( $access_token ) {
                $data->expire_time  = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen(Gc::$upload_url."access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        }
        return $access_token;
    }

    private function httpGet($url) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT, 500);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_URL, $url);
      $res = curl_exec($curl);
      curl_close($curl);
      return $res;
    }
}

header('Access-Control-Allow-Origin: *');
header('Content-Type:application/json;charset=UTF-8');

$jssdk = new WxJsSDK( WxJsSDK::APPID, WxJsSDK::APPSECRET, $url );
// wx.config 所有必需需要的参数
// $signPackage = $jssdk->GetSignPackage();
// $tmp=json_encode(array ('appId'=>$signPackage["appId"],'timestamp'=>$signPackage["timestamp"],'nonceStr'=>$signPackage["nonceStr"],'signature'=>$signPackage["signature"],'url'=>$signPackage["url"]));
// $callback = $_GET['callback'];
// echo $callback.'('.$tmp.')';

// 只提供ticket，具体的加密获得signature放在js里执行，减轻服务器的压力
$ticket = $jssdk->getJsApiTicket();
$tmp    = json_encode(array('ticket' => $ticket));
echo $tmp;

exit;
?>
