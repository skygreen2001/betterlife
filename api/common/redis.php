<?php

require_once("../../init.php");

header("Referrer-Policy: no-referrer");
header('Content-Type:application/json;charset=UTF-8');

// 统一读取请求参数（GET 与 POST 合并），后续写操作会强制要求 POST 方法
$redis_request = array_merge($_GET, $_POST);

/****************************************  安全防护: 网络来源限制  ***********************************/
// 仅允许回环地址与内网地址访问，避免 Redis 管理接口暴露到公网
if (!redis_is_trusted_ip(redis_client_ip())) {
    redis_json_error("访问被拒绝：仅限内网访问", 403);
}

/****************************************  安全防护: 跨域来源收紧  ***********************************/
// 不再使用 Access-Control-Allow-Origin: *，仅允许与当前 Host 同源的跨域请求
if (!empty($_SERVER['HTTP_ORIGIN'])) {
    $redis_host        = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $redis_origin_host = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
    if (!empty($redis_host) && !empty($redis_origin_host) && $redis_origin_host === $redis_host) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: X-CSRF-Token, Content-Type');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Vary: Origin');
    }
    // 处理 CORS 预检请求
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        return;
    }
}

/****************************************  安全防护: 管理员身份校验  ***********************************/
// isPhpRedis 仅检查扩展是否安装，无需登录；其余操作均要求管理员已登录后台
$isPhpRedis = @$redis_request["isPhpRedis"];
if (!empty($isPhpRedis)) {
    echo class_exists("Redis") ? "true" : "false";
    return;
}
if (!HttpSession::isHave('user_id')) {
    redis_json_error("未登录或登录已过期，请先登录后台", 401);
}

/****************************************  安全防护: CSRF Token 获取  ***********************************/
// 前端在初始化时通过该入口获取 CSRF Token，所有写操作必须携带
if (isset($_GET['csrf'])) {
    echo json_encode(array('csrf_token' => redis_csrf_token()));
    return;
}

/****************************************  菜单操作: 核心逻辑  ***********************************/
$step = @$redis_request["step"];     // 菜单操作
if (!empty($step)) {
    // 写操作必须使用 POST 并携带 CSRF Token
    if (in_array((int)$step, array(101, 102, 4, 6, 7, 9), true)) {
        redis_require_post_csrf();
    }
    $isRemote  = @$redis_request["isConfigRemote"];
    $server_id = @$redis_request["server_id"];
    $db        = @$redis_request["db"];
    $key       = @$redis_request["key"];
    $val       = @$redis_request["result"];

    if (isset($isRemote) && $isRemote == true) {
        if (!empty($server_id)) {
            // Redis服务器数据信息查询
            $config_need = server_config($server_id);
            if ($config_need) {
                extract($config_need);
            } else {
                return;
            }
        }
    } else {
        $server   = @$redis_request["server"];
        $port     = @$redis_request["port"];
        $password = @$redis_request["password"];
    }

    if (!empty($server)) {
        $serverCache = BBCache::singleton()->redisServer($server, $port, $password);
    }

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
      // 添加DB
        case 101:
            $dbs = $serverCache->dbInfos();
            $dbs = array_keys($dbs);
            $dbIndex = 0;
            foreach ($dbs as $db) {
                $serverCache->select($dbIndex);
                if ($serverCache->size() <= 0) {
                    break;
                }
                $dbIndex += 1;
            }
            $serverCache->select($dbIndex);
            $serverCache->save("createTime", UtilDateTime::now());
            $dbs = $serverCache->dbInfos();
            $result = array_keys($dbs);
            break;
      // 删除DB
        case 102:
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $serverCache->clear();
            $dbs = $serverCache->dbInfos();
            if ($dbs && is_array($dbs) && count($dbs) > 0) {
                $result = array_keys($dbs);
            }
            break;
      // 查询指定服务器所有的DB
        case 1:
            $dbs = $serverCache->dbInfos();
            if (is_array($dbs) && count($dbs) == 0) {
                $dbIndex = 0;
                $serverCache->select($dbIndex);
                $serverCache->save("createTime", UtilDateTime::now());
                $dbs = $serverCache->dbInfos();
            }
            if ($dbs && is_array($dbs) && count($dbs) > 0) {
                $result = array_keys($dbs);
            }
            break;
      // 查询|分页查询指定DB所有的keys
        case 2:
            $dbIndex  = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $countAll = $serverCache->size();
            $data     = $serverCache->keys();
            sort($data, SORT_STRING);
            $pageSize = @$redis_request["pageSize"];
            if (!$pageSize) {
                $pageSize = 1000;
            }
            if ($countAll > $pageSize) {
                $page   = @$redis_request["page"];
                if (!$page) {
                    $page = 1;
                }
                $start  = ($page - 1) * $pageSize;
                if ($start < $countAll) {
                    if ($start + $pageSize > $countAll) {
                        $pageSize = $countAll - $start;
                    }
                    $data = array_slice($data, $start, $pageSize);
                } else {
                    $data = array();
                }
            }
            $result              = array();
            $result["data"]      = $data;
            $result["countKeys"] = $countAll;
            break;
      // 查询指定key的value
        case 3:
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $result         = array();
            $result["type"] = $serverCache->getKeyType($key);
            $result["data"] = $serverCache->get($key);
          // print_r($result);
            break;
      // 修改指定key的value
        case 4:
            $valType = @$redis_request["valType"];
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $serverCache->delete($key);
            $serverCache->set($key, $val, $valType);
            $result         = array();
            $result["data"] = $serverCache->get($key);
            $result["type"] = $serverCache->getKeyType($key);
            break;
      // 模糊查询指定关键词的所有key
        case 5:
            $queryKey = @$redis_request["queryKey"];
            $dbIndex  = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $data   = $serverCache->keys("*" . $queryKey . "*");
            $countAll = count($data);
            sort($data, SORT_STRING);
            $pageSize = @$redis_request["pageSize"];
            if (!$pageSize) {
                $pageSize = 1000;
            }
            if ($countAll > $pageSize) {
                $page   = @$redis_request["page"];
                if (!$page) {
                    $page = 1;
                }
                $start  = ($page - 1) * $pageSize;
                if ($start < $countAll) {
                    if ($start + $pageSize > $countAll) {
                        $pageSize = $countAll - $start;
                    }
                    $data = array_slice($data, $start, $pageSize);
                } else {
                    $data = array();
                }
            }
            $result              = array();
            $result["data"]      = $data;
            $result["countKeys"] = $countAll;
            break;
      // 新增key和value
        case 6:
            $addNewType  = @$redis_request["addNewType"];
            $addNewKey   = @$redis_request["addNewKey"];
            $addNewValue = @$redis_request["addNewValue"];
            $dbIndex     = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            if (!empty($addNewType)) {
                $addNewType = $serverCache->getKeyTypeByShow($addNewType);
            }
            $serverCache->save($addNewKey, $addNewValue, $addNewType);
            $result      = $serverCache->keys();
            sort($result, SORT_STRING);
            break;
      // 删除指定key
        case 7:
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            $serverCache->delete($key);
            $result  = true;
            break;
      // 导出
        case 8:
            $queryKey = @$redis_request["queryKey"];
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            if (!empty($queryKey)) {
                $keys = $serverCache->keys("*" . $queryKey . "*");
            } else {
                $keys = $serverCache->keys();
            }
            if ($keys && count($keys) > 0) {
                // $values = $serverCache->gets($keys);
                // $result = array_combine($keys, $values);
                $data = array();
                $index = 0;
                sort($keys, SORT_STRING);
                foreach ($keys as $key) {
                    $data[$index][0] = $key;
                    $data[$index][1] = $serverCache->get($key);
                    $data[$index][2] = $serverCache->getKeyType($key);
                    $data[$index][2] = $serverCache->getKeyTypeShow($data[$index][2]);
                    if ($data[$index][1] && is_array($data[$index][1])) {
                        $data[$index][1] = json_encode($data[$index][1], JSON_OBJECT_AS_ARRAY | JSON_UNESCAPED_UNICODE);
                    }
                    $index++;
                }
                $redisFile = "redis" . date("YmdHis") . ".xls";
                $redisPath = Gc::$upload_path . "redis" . DS . "export" . DS . $redisFile;
                $arr_output_header = array("key", "value", "type");
                UtilFileSystem::createDir(dirname($redisPath));
                UtilExcel::arraytoExcel($arr_output_header, $data, $redisPath, false);
                $result = Gc::$upload_url . "redis/export/" . $redisFile;
                echo $result;
                return;
            }
          // $result  = true;
            break;
      // 导入
        case 9:
            $ufile      = @$redis_request["ufile"];
            $uploadPath = GC::$upload_path . "redis" . DS . "import" . DS . $ufile;
            $arr_import_header = array("key" => "key", "value" => "value", "type" => "type");
            $data    = UtilExcel::exceltoArray($uploadPath, $arr_import_header);
            $dbIndex = (int) str_replace("db", "", $db);
            $serverCache->select($dbIndex);
            if ($data && is_array($data) && count($data) > 0) {
                foreach ($data as $row) {
                    $addNewKey   = "";
                    $addNewValue = "";
                    $addNewType  = "";
                    if (array_key_exists("key", $row)) {
                        $addNewKey = $row["key"];
                    }
                    if (array_key_exists("value", $row)) {
                        $addNewValue = $row["value"];
                    }
                    if (array_key_exists("type", $row)) {
                        $addNewType = $row["type"];
                    }
                    if (!empty($addNewKey) && !empty($addNewValue)) {
                        // 先根据类型显示名判断是否为集合/列表/有序集合/哈希，避免直接引用 Redis 扩展的类常量
                        $typeShow     = strtoupper(trim($addNewType));
                        $isComplexType = in_array($typeShow, array("SETS", "LISTS", "ZSETS", "SORTED SETS", "HASHES"));
                        if (!empty($addNewType)) {
                            $addNewType = $serverCache->getKeyTypeByShow($addNewType);
                            if ($isComplexType) {
                                $addNewValue = json_decode($addNewValue, JSON_OBJECT_AS_ARRAY);
                                $addNewValue = implode("<(||)>", $addNewValue);
                            }
                        }
                        $serverCache->delete($addNewKey);
                        $serverCache->set($addNewKey, $addNewValue, $addNewType);
                    }
                }
            }
            $result  = $serverCache->keys();
            sort($result, SORT_STRING);
            break;
        default:
            break;
    }

    $echo = json_encode($result);
    // 是经过序列化编码的Java对象
    if (json_last_error() == JSON_ERROR_UTF8) {
        $data = $result["data"];
        $rd   = array();
        foreach ($result["data"] as $key => $value) {
            $key      = utf8_encode($key);
            $value    = utf8_encode($value);
            $rd[$key] = $value;
        }
        $result["data"] = $rd;
        echo json_encode($result, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo $echo;
    }
    return ;
}

/****************************************  设置服务器  ***********************************/
$action = @$redis_request["ca"];  // 设置操作
if ($action) {
    // 配置的新增/修改/删除均为写操作，必须使用 POST 并携带 CSRF Token
    if (in_array((int)$action, array(1, 2, 3), true)) {
        redis_require_post_csrf();
    }
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
            $id       = @$redis_request["id"];
            $name     = @$redis_request["name"];
            $server   = @$redis_request["server"];
            $port     = @$redis_request["port"];
            $password = @$redis_request["password"];
            $record   = array(
            "id"       => $id,
            "name"     => $name,
            "server"   => $server,
            "port"     => $port,
            "password" => $password,
            );
            if ($result) {
                $result[] = $record;
                $configs["data"] = $result;
                save_server_configs($configs);
            }
            echo true;
            return ;
        break;
      // 修改Redis服务器设置
        case 2:
            $id       = @$redis_request["id"];
            $oid      = @$redis_request["oid"];
            $name     = @$redis_request["name"];
            $server   = @$redis_request["server"];
            $port     = @$redis_request["port"];
            $password = @$redis_request["password"];
            if (!empty($oid)) {
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

            if ($result) {
                $configs["data"] = $result;
                save_server_configs($configs);
            }
            echo true;
            return ;
        break;
      // 删除Redis服务器设置
        case 3:
            $id     = @$redis_request["id"];
            $id_key = array_search($id, array_column($result, 'id'));
            array_splice($result, $id_key, 1);
            if ($result) {
                $configs["data"] = $result;
                save_server_configs($configs);
            }
            echo true;
            return ;
        break;
    }
}

/****************************************  上传文件  ***********************************/
$action = @$redis_request["action"];  // 是否上传文件
if (!empty($action)) {
    // 文件上传属于写操作，必须使用 POST 并携带 CSRF Token
    redis_require_post_csrf();
    $result = '';
    $files = $_FILES;
    if (!empty($files["upload_file"])) {
        $diffpart   = date("YmdHis");
        $tmptail    = explode('.', $files["upload_file"]["name"]);
        $tmptail    = end($tmptail);
        $uploadFile = "redis$diffpart.$tmptail";
        $uploadPath = GC::$upload_path . "redis" . DS . "import" . DS . $uploadFile;
        $result     = UtilFileSystem::uploadFile($files, $uploadPath);
    }
    echo json_encode($result);
    return;
}

/****************************************  自定义函数  ***********************************/
/**
 * 获取客户端 IP（仅信任 REMOTE_ADDR，不读取可伪造的 X-Forwarded-For）
 */
function redis_client_ip()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
}

/**
 * 判断客户端 IP 是否为可信任的内网地址（回环、RFC1918 私有地址段）
 */
function redis_is_trusted_ip($ip)
{
    if (empty($ip)) {
        return false;
    }
    // 回环地址
    if ($ip === '127.0.0.1' || $ip === '::1' || $ip === 'localhost') {
        return true;
    }
    // 10.0.0.0/8
    if (strpos($ip, '10.') === 0) {
        return true;
    }
    // 192.168.0.0/16
    if (strpos($ip, '192.168.') === 0) {
        return true;
    }
    // 172.16.0.0/12
    if (preg_match('/^172\.(1[6-9]|2\d|3[01])\./', $ip)) {
        return true;
    }
    return false;
}

/**
 * 输出 JSON 错误响应并终止执行
 */
function redis_json_error($message, $status_code = 400)
{
    http_response_code($status_code);
    echo json_encode(array(
        'success' => false,
        'message' => $message,
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 获取（首次生成）当前会话的 CSRF Token
 */
function redis_csrf_token()
{
    if (empty($_SESSION['redis_csrf_token'])) {
        $_SESSION['redis_csrf_token'] = bin2hex(function_exists('random_bytes') ? random_bytes(32) : openssl_random_pseudo_bytes(32));
    }
    return $_SESSION['redis_csrf_token'];
}

/**
 * 校验请求携带的 CSRF Token 是否与会话中的一致
 */
function redis_verify_csrf($token)
{
    if (empty($token) || empty($_SESSION['redis_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['redis_csrf_token'], $token);
}

/**
 * 强制要求写操作使用 POST 方法并通过 CSRF 校验
 */
function redis_require_post_csrf()
{
    if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
        redis_json_error("写操作必须使用 POST 方法", 405);
    }
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token']
        : (isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '');
    if (!redis_verify_csrf($csrf_token)) {
        redis_json_error("CSRF 校验失败，请刷新页面后重试", 419);
    }
}

/**
 * 获取配置文件路径
 * 首先检查upload目录下是否有该配置文件，如果没有，就从api默认文件复制到upload路径下
 */
function config_path()
{
    $config_redis_file = Gc::$nav_root_path . "api" . DS . "web" . DS . "data" . DS . "redis.json";
    $config_dest       = Gc::$upload_path . "redis" . DS . "config" . DS . "redis.json";
    UtilFileSystem::createDir(dirname($config_dest));
    if (!file_exists($config_dest)) {
        @copy($config_redis_file, $config_dest);

        if (!file_exists($config_dest)) {
            return $config_redis_file;
        }
    }
    return $config_dest;
}

/**
 * 保存服务器配置
 */
function save_server_configs($configs)
{
    if ($configs) {
        $config_redis_file = config_path();
        $contents = json_encode($configs, JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (!empty($contents)) {
            file_put_contents($config_redis_file, $contents);
        }
    }
}

/**
 * 获取服务器配置
 */
function server_configs()
{
    $config_redis_file = config_path();
    $config_content    = file_get_contents($config_redis_file);
    $result            = json_decode($config_content, true);
    return $result;
}

/**
 * 根据指定id获取指定的服务器配置
 */
function server_config($server_id)
{
    $result      = array();
    $configs     = server_configs();
    $config_need = $configs["data"];
    $id_key      = array_search($server_id, array_column($config_need, 'id'));
    if ($id_key >= 0) {
        $result = $config_need[$id_key];
    }
    return $result;
}
