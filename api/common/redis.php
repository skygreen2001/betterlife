<?php
require_once ("../../init.php");
$step   = $_GET["step"];
$server = $_GET["server"];
$db     = @$_GET["db"];
$key    = @$_GET["key"];
$val    = @$_GET["result"];

$password = "";
$port     = "8888";
switch ($server) {
  case 'www.bb.com':
    $server   = 'w.itasktour.com';
    $password = "123456";
    $port     = Config_Redis::$port;
    break;
  case 'dev.bb.com':
    $password = "123456";
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
