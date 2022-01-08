<?php
require_once("../../../init.php");

// Outputs all the result of shellcommand "ls", and returns
// the last output line into $last_line. Stores the return value
// of the shell command in $retval.
//$last_line = system('ls', $retval);

//exec('mysqldump -uroot -p enjoyoung>ey20110803.bak', $result);
//echo $result;
$dest_db_config=array
(
    "host"     => ConfigDb::$host,
    "port"     => ConfigDb::$port,
    "user"     => ConfigDb::$username,
    "password" => ConfigDb::$password,  
    "dbname"   => ConfigDb::$dbname,
    "script_filename"=>Gc::$nav_root_path . "db\\mysql\\db_betterlife.sql"
);
//创建Betterlife后台所需的库。
DbInfo_Mysql::run_script($dest_db_config);   
?>