<?php
require_once ("../../init.php");
$step   = $_GET["step"];
$server = $_GET["server"];
$db     = @$_GET["db"];
$key    = @$_GET["key"];
$val    = @$_GET["result"];

$password = "";
$port     = "8368";
switch ($server) {
  case 'v3.itasktour.com':
    $server   = 'v31.itasktour.com';
    $password = "7htVhQrFP2big7NiBde5fpEEdUaa";
    $port     = Config_Redis::$port;
    break;
  case 'dev.itasktour.com':
    $password = "7htVhQrFP2big7NiBde5fpEEdUaa";
    break;
  case '127.0.0.1':
    $server = '192.168.64.1';
    break;
  default:
    break;
}

$serverCache = BBCache::singleton()->redisServer($server, $port, $password);
$result = '';
switch ($step) {
  case 1:
    $dbs = $serverCache->dbInfos();
    if ( $dbs && is_array($dbs) && count($dbs) > 0 ) {
        $result = array_keys($dbs);
    }
    break;
  case 2:
    $dbIndex = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $result = $serverCache->keys();
    sort($result, SORT_STRING);
    break;
  case 3:
    $dbIndex = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $result = $serverCache->get($key);
    break;
  case 4:
    $dbIndex = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $serverCache->set($key, $val);
    $result = $val;
    break;
  default:
    break;
}

echo json_encode($result);
?>
