<?php
header("Content-Type: image/jpeg;");
$params  = $_GET;
$img_src = $params['src'];

// $img_src = 'https://images.nga.gov/en/web_images/constable.jpg'; //仅供测试
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $img_src);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
$res = curl_exec($ch);

//Check for errors.
if ( curl_errno($ch) ) {
    // [PHP - curl localhost connection refused](https://pretagteam.com/question/php-curl-localhost-connection-refused)
    if ( strpos($img_src, "http://localhost/") !== false || strpos($img_src, "http://127.0.0.1/") !== false ) {
        curl_setopt($ch, CURLOPT_PROXY, $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']); 
        $res = curl_exec($ch);
    }
    if ( curl_errno($ch) ) {
        echo curl_error($ch);
        die();
    }
}

$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch) ;
echo $res;
