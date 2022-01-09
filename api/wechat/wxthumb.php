<?php

require_once("../../init.php");
/**
 * 用于微信小头像
 */
$params = $_GET;
$blogId = $params['blogId'];
if (!empty($blogId) && ($blogId > 0 )) {
    $blog = Blog::getById($blogId);
    if ($blog) {
        $icon_url  = $blog->icon_url;
        $file_name = basename($icon_url);
        $suffix_name = explode(".", $file_name);
        $suffix_name = end($suffix_name);
        header("Content-Type:image/" . $suffix_name);
        if ($suffix_name == "jpeg") {
            $suffix_name = "jpg";
        }
        $icon_path       = GC::$upload_url . "images" . DS . $icon_url;
        $thumb_icon_path = GC::$upload_path . "images" . DS . "blog" . DS . "thumb" . DS . $file_name;
        UtilImage::thumb($icon_path, $thumb_icon_url, $suffix_name, 200, 200);

        // $icon_path = "http://localhost/bb/upload/images/blog/icon_url/20170712082119.png";
        // $file_name = "20170712082119.png";
        // $suffix_name = "png";
        // $icon_url  = GC::$upload_url . "images" . DS . $icon_url;
        // echo $icon_path;
        // echo $thumb_icon_path;
        // echo $suffix_name;
    }
}
