<?php
require_once ("../../init.php");
$action    = @$_GET["ca"];  // 设置操作
if ( $action ) {
    $configs = server_configs();
    $result  = $configs["data"];
    switch ($action) {
      // 查询Redis服务器设置列表
      case 100:
        echo json_encode($result);
        return ;
        break;
      // 新增Redis服务器设置
      case 1:
        $id       = @$_GET["id"];
        $name     = @$_GET["name"];
        $server   = @$_GET["server"];
        $port     = @$_GET["port"];
        $password = @$_GET["password"];
        $record   = array(
            "id"       => $id,
            "name"     => $name,
            "server"   => $server,
            "port"     => $port,
            "password" => $password,
        );
        if ( $result ) {
            $result[] = $record;
            $configs["data"] = $result;
            save_server_configs($configs);
        }
        echo true;
        return ;
        break;
      // 修改Redis服务器设置
      case 2:
        $id       = @$_GET["id"];
        $oid      = @$_GET["oid"];
        $name     = @$_GET["name"];
        $server   = @$_GET["server"];
        $port     = @$_GET["port"];
        $password = @$_GET["password"];
        if ( !empty($oid) ) {
            $qid = $oid;
        } else {
            $qid = $id;
        }
        $id_key    = array_search($qid, array_column($result, 'id'));
        $updateOne = $result[$id_key];
        $updateOne["id"]       = $id;
        $updateOne["name"]     = $name;
        $updateOne["server"]   = $server;
        $updateOne["port"]     = $port;
        $updateOne["password"] = $password;
        $result[$id_key]       = $updateOne;

        if ( $result ) {
            $configs["data"] = $result;
            save_server_configs($configs);
        }
        echo true;
        return ;
        break;
      // 删除Redis服务器设置
      case 3:
        $id     = @$_GET["id"];
        $id_key = array_search($id, array_column($result, 'id'));
        array_splice($result, $id_key, 1);
        if ( $result ) {
            $configs["data"] = $result;
            save_server_configs($configs);
        }
        echo true;
        return ;
        break;
    }
}

$step      = @$_GET["step"];     // 菜单操作
$server_id = @$_GET["server_id"];
$db        = @$_GET["db"];
$key       = @$_GET["key"];
$valType   = @$_GET["valType"];
$val       = @$_GET["result"];

// Redis服务器数据信息查询
$config_need = server_config($server_id);
if ( $config_need ) {
    extract($config_need);
} else {
    return;
}
$serverCache = BBCache::singleton()->redisServer($server, $port, $password);
$result = '';
switch ($step) {
  // 查询Redis服务器设置列表
  case 100:
    $configs = server_configs();
    $result  = $configs["data"];
    $result  = array_column($result, 'name', 'id');
    $data    = array();
    foreach ($result as $key => $value) {
        $text = $value . "(" . $key . ")";
        $m = array(
            "id" => $key,
            "text" => $text
        );
        array_push($data, $m);
    }
    echo json_encode($data);
    return ;
    break;
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
    $result         = array();
    $result["type"] = $serverCache->getKeyType($key);
    if ( $result["type"] == Redis::REDIS_HASH || $result["type"] == Redis::REDIS_ZSET ) {
        $result["data"] = $key;
    } else {
        $result["data"] = $serverCache->get($key);
    }
    break;
  case 4:
    $dbIndex = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $serverCache->set($key, $val);
    $result         = array();
    $result["data"] = $serverCache->get($key);
    $result["type"] = $serverCache->getKeyType($key);
    break;
  case 5:
    $queryKey = @$_GET["queryKey"];
    $dbIndex  = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $result   = $serverCache->keys("*" . $queryKey . "*");
    sort($result, SORT_STRING);
    break;
  case 6:
    $addNewType  = @$_GET["addNewType"];
    $addNewKey   = @$_GET["addNewKey"];
    $addNewValue = @$_GET["addNewValue"];
    $dbIndex     = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $serverCache->save($addNewKey, $addNewValue, $addNewType);
    $result      = $serverCache->keys();
    sort($result, SORT_STRING);
    break;
  case 7:
    $dbIndex = (int) str_replace("db", "", $db);
    $serverCache->select($dbIndex);
    $serverCache->delete($key);
    $result  = true;
    break;
  default:
    break;
}

echo json_encode($result);


/**
 * 获取配置文件路径
 * 首先检查upload目录下是否有该配置文件，如果没有，就从api默认文件复制到upload路径下
 */
function config_path() {
    $config_redis_file = Gc::$nav_root_path . "api" . DS ."web" . DS . "data" . DS . "redis.json";
    $config_dest       = Gc::$upload_path . "config" . DS . "redis.json";
    UtilFileSystem::createDir(dirname($config_dest));
    if ( !file_exists($config_dest) ) {
        @copy($config_redis_file, $config_dest);

        if ( !file_exists($config_dest) ) {
            return $config_redis_file;
        }
    }
    return $config_dest;
}


/**
 * 保存服务器配置
 */
function save_server_configs($configs) {
    if ( $configs ) {
        $config_redis_file = config_path();
        $contents = json_encode($configs, JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT);
        if ( !empty($contents) ) {
            file_put_contents($config_redis_file, $contents);
        }
    }
}

/**
 * 获取服务器配置
 */
function server_configs() {
    $config_redis_file = config_path();
    $config_content    = file_get_contents($config_redis_file);
    $result            = json_decode($config_content, true);
    return $result;
}

/**
 * 根据指定id获取指定的服务器配置
 */
function server_config($server_id) {
    $result      = array();
    $configs     = server_configs();
    $config_need = $configs["data"];
    $id_key      = array_search($server_id, array_column($config_need, 'id'));
    if ( $id_key >= 0 ) {
      $result = $config_need[$id_key];
    }
    return $result;
}
?>
