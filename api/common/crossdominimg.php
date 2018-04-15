<?php
header("Content-Type: image/jpeg;");
$params  = $_GET;
$img_src = $params['src'];

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
$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch) ;
echo $res;
