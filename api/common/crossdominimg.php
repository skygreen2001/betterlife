<?php
require_once ("../../init.php");
$params  = $_GET;
$img_src = $params['src'];
// header('content-type: image/jpg');
echo file_get_contents($img_src);
?>
