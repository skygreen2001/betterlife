<?php
require_once("../../../init.php");
require_once("../../../misc/sql/report.php");

$rtype  = $_GET["rtype"];
$result = $configReport[$rtype];
echo json_encode($result);
