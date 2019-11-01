<?php
/*
 +---------------------------------<br/>
 * 使用Redis作为系统缓存。<br/>
 * 使用方法: 添加以下内容到Config_Memcache中<br/>
 *     所有的缓存服务器Memcache 主机IP地址和端口配置<br/>
 *     保存数据是否需要压缩。 <br/>
 +---------------------------------
 * @see phpredis: https://github.com/phpredis/phpredis
 * @see install: https://github.com/phpredis/phpredis/blob/develop/INSTALL.markdown
 * @see redis服务的配置文件修改 bind ip,默认是bind 127.0.0.1 只允许本地连接 0.0.0.0允许任意ip,也可根据需要自己修改。
 * @see redis.conf 配置文件中设置: bind 127.0.0.1 192.168.64.2
 * [以下为老的解决方案，已作废，仅供学习参考]
 *      X@see Reference Method:http://code.google.com/p/phpredis/wiki/referencemethods
 *      X@see php-redis:http://code.google.com/p/php-redis/
 *      X@see PHP-redis中文说明:http://hi.baidu.com/%B4%AB%CB%B5%D6%D0%B5%C4%C8%CC%D5%DF%C3%A8/blog/item/c9ff4ac1898afa4fb219a8c7.html
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
class Cache_Redis extends Cache_Base
{
    private $redis;

    /**
     * 测试体验Redis Cache
     */
    public function TestRun()
    {
        // $this->testData();
        $dbInfos = $this->dbInfos();
        print_pre($dbInfos, true, "获取Redis DB信息:");
        $i = 0;
        foreach ($dbInfos as $key => $value) {
            $this->select($i);
            // $count = $this->countKeys();
            // echo "[$key]共计:" . $count . "个keys<br/>";

            $allKeys = $this->keys();
            print_pre($allKeys, true, "[$key]清单如下:");
            $i++;
        }
    }

    /**
     * 验证测试数据
     */
    private function testData() {
        $this->set( 'key', 'value' );
        $this->update( 'key', 'hello,girl' );
        $test = $this->get( 'key' );
        echo $test;
        $member = new User();
        $member->setName("skygreen2001");
        $member->setPassword("administrator");
        $member->setDepartmentId(3211);
        $this->save( "Member1", $member );
        $user = $this->get( "Member1" );
        echo $user;
        $member = new User();
        $member->setName("学必填");
        $member->setPassword("password");
        $member->setDepartmentId(3212);
        $this->save( "Member2", $member );

        $users = $this->gets( Array('Member1', 'Member2') );
        print_r($users);
    }

    /**
     * 实例化初始化Redis服务器
     * @param string $host
     * @param string $port
     * @param string $password
     */
    public function __construct($host = '', $port = '', $password = '')
    {
        if ( empty($host) ) {
            $host = Config_Redis::$host;
        }
        if ( empty($port) ) {
            $port = Config_Redis::$port;
        }
        $this->redis = new Redis();
        if ( Config_Redis::$is_persistent ) {
            $this->redis->pconnect($host, $port);
        } else {
            $this->redis->connect($host, $port);
        }

        if ( empty($password) ) {
            $password = Config_Redis::$password;
        }
        if ( !empty($password) ) {
            $this->redis->auth($password);
        }
        if ( !empty(Config_Redis::$prefix_key) ) {
            $this->redis->setOption(Redis::OPT_PREFIX, Config_Redis::$prefix_key);
        }
    }

    /**
     * 选择指定第几个数据库
     */
    public function select($index)
    {
        $this->redis->select($index);
    }

    /**
     * 所有键值清单
     */
    public function keys()
    {
        $result = $this->redis->keys('*');
        return $result;
    }

    /**
     * 计数: 所有键
     */
    public function dbInfos()
    {
        $info = $this->redis->info();
        // print_pre($info, true, "Redis系统信息:");
        $result = UtilArray::like( $info, "^db\d+" );
        return $result;
    }

    /**
     * 计数: 所有键
     */
    public function countKeys()
    {
        $result = $this->redis->dbSize();
        return $result;
    }

    /**
     * 查看键key是否存在。
     * @param string $key
     */
    public function Contains($key)
    {
        if ( isset($this->redis) ) {
            return  $this->redis->exists($key);
        }
        return false;
    }

    /**
    * 在缓存里保存指定$key的数据<br/>
    * 仅当存储空间中不存在键相同的数据时才保存<br/>
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */
    public function save($key, $value, $expired = 86400)
    {
        if ( is_object($value) ) {
            $value = serialize($value);
        }
        $this->redis->setnx($key, $value);
        $now = time(NULL); // current timestamp
        $this->redis->expireAt($key, $now + $expired);
    }

   /**
    * 在缓存里保存指定$key的数据 <br/>
    * 与save和update不同，无论何时都保存 <br/>
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */
    public function set($key, $value, $expired = 86400)
    {
        if ( is_object($value) ) {
            $value = serialize($value);
        }
        $this->redis->setex($key, $expired, $value);
    }

   /**
    * 在缓存里更新指定key的数据<br/>
    * 仅当存储空间中存在键相同的数据时才保存<br/>
    * @param string $key
    * @param string|array|object $value
    * @return bool
    */
    public function update($key, $value, $expired = 86400)
    {
        if ( is_object($value) ) {
            $value = serialize($value);
        }
        $this->redis->setex($key, $expired, $value);
    }

   /**
    * 在缓存里删除所有指定$key的数据
    * @param string|array $key
    * @return bool
    */
    public function delete($key)
    {
        $this->redis->delete($key);
    }

    /**
     * 获取指定key的值
     * @param string $key
     * @return string|array|object
     */
    public function get($key)
    {
        $data = $this->redis->get($key);

        if ( @unserialize($data) ) {
            $data = unserialize($data);
        }
        return $data;
    }

    /**
     * 获取指定keys的值们。<br/>
     * 允许一次查询多个键值，减少通讯次数。
     * @param array $key
     * @return array
     */
    public function gets($keyArr)
    {
        $data = $this->redis->getMultiple($keyArr);
        if ( $data ) {
            $result = array();
            foreach ($data as $element)
            {
                if ( @unserialize($element) ) {
                   $element = unserialize($element);
                }
                $result[] = $element;
            }
        }
        return $result;
    }

    /**
     * 清除所有的对象。
     *
     */
    public function clear()
    {
        $allKeys = $this->redis->keys('*');
        $this->delete( $allKeys );
    }


    public function close()
    {
        $this->redis->close();
    }
}
?>
