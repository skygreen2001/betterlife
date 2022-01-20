<?php

require_once("../../init.php");

$params  = $_GET;
$img_src = $params['src'];

if (endWith($img_src, ".mp4")) {
    header("Content-Type: video/mpeg4;");
} elseif (endWith($img_src, ".gif")) {
    header("Content-Type: image/gif;");
} elseif (endWith($img_src, ".png")) {
    header("Content-Type: image/png;");
} elseif (endWith($img_src, ".pdf")) {
    header("Content-Type: application/pdf;");
} elseif (endWith($img_src, ".pptx")) {
    header("Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation");
} else {
    header("Content-Type: image/jpeg;");
}

// 方案一: file_get_contents
// echo file_get_contents($img_src);

// 方案二: curl
// $img_src = 'https://images.nga.gov/en/web_images/constable.jpg'; //仅供测试
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $img_src);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
$res = curl_exec($ch);

//Check for errors.
if (curl_errno($ch)) {
    // [PHP - curl localhost connection refused](https://pretagteam.com/question/php-curl-localhost-connection-refused)
    if (contains($img_src, LS)) {
        curl_setopt($ch, CURLOPT_PROXY, $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']);
        $res = curl_exec($ch);
    }
    if (curl_errno($ch)) {
        echo curl_error($ch);
        die();
    }
}

$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch) ;
echo $res;
