<?php

/**
 * 优化控制图片大小
 *
 * - nginx config:
 *
 *     location /img/ {
 *         rewrite ^/img/(.*)$ /api/common/optimzeImgShow.php?src=$1;
 *     }
 *
 * - web html image src rule:
 *
 *     https://www.bb.com/img/upload/images/blog/cover_img/20171128100823.jpg&w=600&h=400
 *
 * - 页面中定义格式如:
 *
 *     <img src="https://www.bb.com/img/upload/images/blog/cover_img/20171128100823.jpg&w=600&h=400" alt="" />
 *
 *   其实质调用路径如:
 *
 *     https://www.bb.com/api/common/optimzeImgShow.php?src=upload/images/blog/cover_img/20171128100823.jpg&w=600&h=400
 */
require_once("../../init.php");
ob_end_clean(); ob_end_clean();
$params  = $_GET;
$img_src = $params['src'];
$width   = $params['w'];
$hight   = $params['h'];

// 以下为测试数据
// $img_src = "https://www.bb.com/upload/images/blog/cover_img/20171128100823.jpg";
// $width = "600";
// $hight = "400";

if ($width || $hight) {

    // 方案一: file_get_contents
    // echo file_get_contents($img_src);
    $img_src     = Gc::$url_base . $img_src;
    $file_name   = basename($img_src);
    $suffix_name = explode(".", $file_name);
    $first_name  = current($suffix_name);
    $suffix_name = end($suffix_name);
    $file_name   = $first_name . "_" . $width . "_" . $hight . "." . $suffix_name;
    header("Content-Type:image/" . $suffix_name);
    if ($suffix_name == "jpeg" ) $suffix_name = "jpg";
    $thumb_icon_path = Gc::$upload_path . "images" . DS . "cache" . DS . $file_name;
    if (file_exists($thumb_icon_path)) {
        echo file_get_contents($thumb_icon_path);
    } else {
        UtilImage::thumb( $img_src, $thumb_icon_path, $suffix_name, $width, $hight );
    }
} else {

    // 方案二: curl
    header("Content-Type: image/jpeg;");
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
}
