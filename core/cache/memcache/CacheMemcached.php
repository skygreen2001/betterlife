<?php

/**
 * 使用memcached作为系统缓存。
 *
 * 基于libmemcached的客户端叫memcached，据说性能更好，功能也更多。
 *
 * 暂未提供实现，该组件需要在Linux系统上方能使用。
 *
 * @link php的memcached客户端memcached: http://www.9enjoy.com/php-memcached/
 * @todo
 * @category Betterlife
 * @package core.cache
 * @author skygreen2001 <skygreen2001@gmail.com>
 */
class CacheMemcached extends CacheBase
{
    /**
     * 测试体验MemCache
     */
    public function TestRun()
    {
        $value = "Hello World";
        for ($i = 0; $i < 100; $i++) {
            $this->set($i, $i . ":testrrrr" . $value);
        }

        //$mem->save("key", $value);
        $this->update("key", "I'm a newbie!");
        $val = $this->get("key");
        echo $val;
        $this->update("key", array("Home" => $value,"Guest" => "I'm a newbie!"));
        $val = $this->get("key");
        print_r($val);
        $this->delete("key");
        $val = $this->get("key");
        echo $val;
        $member = new User();
        $member->setName("skygreen2001");
        $member->setPassword("administrator");
        $member->setDepartmentId(3211);
        $this->save("Member1", $member);
        $user = $this->get("Member1");
        echo $user;
        $member = new User();
        $member->setName("学必填");
        $member->setPassword("password");
        $member->setDepartmentId(3212);
        $this->save("Member2", $member);

        $users = $this->gets(array('Member1', 'Member2'));
        print_r($users);
        $this->clear();
        $this->close();
    }

    public function CacheMemcached()
    {
        if (!class_exists('Memcached')) {
            LogMe::log('请检查是否加载了LibMemcached,Memcached', EnumLogLevel::ERR);
        }
        $this->obj = new Memcached();
        //加载所有的分布式服务器
        $this->obj->addServers($cache_server[0], $cache_server[1]);
    }

    /**
     * 查看键key是否存在。
     * @param string $key
     */
    public function contains($key)
    {
        if (isset($this->obj)) {
            $tmp = $this->get($key);
            if (!empty($tmp)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 在缓存里保存指定$key的数据
     *
     * 仅当存储空间中不存在键相同的数据时才保存
     * @param string $key
     * @param string|array|object $value
     * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
     * @return bool
     */
    public function save($key, $value, $expired = 86400)
    {
        return $this->obj->add($key, $value, ConfigMemcache::$is_compressed, $expired);
    }

    /**
     * 在缓存里保存指定$key的数据
     *
     * 与save和update不同，无论何时都保存
     * @param string $key
     * @param string|array|object $value
     * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
     * @return bool
     */
    public function set($key, $value, $expired = 86400)
    {
        return $this->obj->set($key, $value, ConfigMemcache::$is_compressed, $expired);
    }

    /**
     * 在缓存里更新指定key的数据
     *
     * 仅当存储空间中存在键相同的数据时才保存
     * @param string $key
     * @param string|array|object $value
     * @return bool
     */
    public function update($key, $value, $expired = 86400)
    {
        //替换数据
        return $this->obj->replace($key, $value, ConfigMemcache::$is_compressed, $expired);
    }

    /**
     * 在缓存里删除指定$key的数据
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        $this->obj->delete($key);
    }

    /**
     * 获取指定key的值
     * @param string $key
     * @return string|array|object
     */
    public function get($key)
    {
        $data = $this->obj->get($key);
        return $data;
    }

    /**
     * 获取指定keys的值们。
     * 允许一次查询多个键值，减少通讯次数。
     * @param array $key
     * @return array
     */
    public function gets($keyArr)
    {
        $data = $this->obj->get($keyArr);
        return $data;
    }

    /**
     * 清除所有的对象。
     */
    public function clear()
    {
        return $this->obj->flush();
    }

    /**
     * 显示缓存服务器端状态信息
     * @param mixed $curBytes
     * @param mixed $totalBytes
     */
    public function status(&$curBytes, &$totalBytes)
    {
        $info = $this->obj->getStats();
        $curBytes = $info['bytes'];
        $totalBytes = $info['limit_maxbytes'];

        $return[] = array('name' => '子系统运行时间', 'value' => timeLength($info['uptime']));
        $return[] = array('name' => '缓存服务器', 'value' => MEMCACHED_HOST . ':' . MEMCACHED_PORT . " (ver:{$info['version']})");
        $return[] = array('name' => '数据读取', 'value' => $info['cmd_get'] . '次 ' . formatBytes($info['bytes_written']));
        $return[] = array('name' => '数据写入', 'value' => $info['cmd_set'] . '次 ' . formatBytes($info['bytes_read']));
        $return[] = array('name' => '缓存命中', 'value' => $info['get_hits'] . '次');
        $return[] = array('name' => '缓存未命中', 'value' => $info['get_misses'] . '次');
        $return[] = array('name' => '已缓存数据条数', 'value' => $info['curr_items'] . '条');
        $return[] = array('name' => '进程数', 'value' => $info['threads']);
        $return[] = array('value' => $info['pid'], 'name' => '服务器进程ID');
        $return[] = array('value' => $info['rusage_user'], 'name' => '该进程累计的用户时间(秒:微妙)');
        $return[] = array('value' => $info['rusage_system'], 'name' => '该进程累计的系统时间(秒:微妙)');
        $return[] = array('value' => $info['curr_items'], 'name' => '服务器当前存储的内容数量');
        $return[] = array('value' => $info['total_items'], 'name' => '服务器启动以来存储过的内容总数');

//    $return[] = array('value'=>$info['curr_connections'],'name'=>'连接数量');
//    $return[] = array('value'=>$info['total_connections'],'name'=>'服务器运行以来接受的连接总数 ');
//    $return[] = array('value'=>$info['connection_structures'],'name'=>'服务器分配的连接结构的数量');
        return $return;
    }

    public function close()
    {
        $this->obj->close();
    }
}
