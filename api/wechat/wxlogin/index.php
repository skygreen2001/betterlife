<?php
require_once ("../../../init.php");
include_once "wxBizDataCrypt.php";

$appid         = "微信小程序APPID";
$code          = $_GET['code'];
$encryptedData = $_GET['encryptedData'];
$iv            = $_GET['iv'];
// $sessionKey    = $_GET['session_key'];

// 微信小程序APPID和APPSECRET
class WxLogin {
  const APPID = "微信小程序APPID";
  const APPSECRET = "微信小程序APPSECRET";

  public static function open_id($code){
    $appId = self::APPID;
    $appSecret = self::APPSECRET;

    $url  = "https://api.weixin.qq.com/sns/jscode2session?appid=$appId&secret=$appSecret&js_code=$code&grant_type=authorization_code";
    // echo $url;
    $result = self::httpGet($url);
    // print_r($result);
    return $result;
  }

  private static function httpGet($url) {
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

$wx_userinfo = WxLogin::open_id($code);
$wx_userinfo_obj = json_decode($wx_userinfo);
// print_r($wx_userinfo);
// echo gettype($wx_userinfo);
$sessionKey  = $wx_userinfo_obj->{'session_key'};
$unionid     = $wx_userinfo_obj->{'unionid'};
if ($unionid) {
  print ($wx_userinfo. "\n");
  die();
}

// echo $encryptedData."<br/>";
// echo $iv."<br/>";
// echo $sessionKey."<br/>";

$encryptedData = urldecode($encryptedData);
$iv            = urldecode($iv);

// echo $encryptedData."<br/>";
// echo $iv."<br/>";
// echo $sessionKey."<br/>";
// die();

// -------------------  测试数据  -------------------
// $appid = 'wx4f4bc4dec97d474b';
// $sessionKey = 'tiihtNczf5v6AKRyjwEUhQ==';
// $encryptedData="CiyLU1Aw2KjvrjMdj8YKliAjtP4gsMZM
//                 QmRzooG2xrDcvSnxIMXFufNstNGTyaGS
//                 9uT5geRa0W4oTOb1WT7fJlAC+oNPdbB+
//                 3hVbJSRgv+4lGOETKUQz6OYStslQ142d
//                 NCuabNPGBzlooOmB231qMM85d2/fV6Ch
//                 evvXvQP8Hkue1poOFtnEtpyxVLW1zAo6
//                 /1Xx1COxFvrc2d7UL/lmHInNlxuacJXw
//                 u0fjpXfz/YqYzBIBzD6WUfTIF9GRHpOn
//                 /Hz7saL8xz+W//FRAUid1OksQaQx4CMs
//                 8LOddcQhULW4ucetDf96JcR3g0gfRK4P
//                 C7E/r7Z6xNrXd2UIeorGj5Ef7b1pJAYB
//                 6Y5anaHqZ9J6nKEBvB4DnNLIVWSgARns
//                 /8wR2SiRS7MNACwTyrGvt9ts8p12PKFd
//                 lqYTopNHR1Vf7XjfhQlVsAJdNiKdYmYV
//                 oKlaRv85IfVunYzO0IKXsyl7JCUjCpoG
//                 20f0a04COwfneQAGGwd5oa+T8yO5hzuy
//                 Db/XcxxmK01EpqOyuxINew==";
// $iv = 'r7BXXKkLb8qrSNn05n0qiA==';

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
