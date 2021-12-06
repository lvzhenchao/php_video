<?php

namespace App\lib\Redis;

use EasySwoole\Core\AbstractInterface\Singleton;

class Redis{
    use Singleton;

    public $redis = "";

    private function __construct(){
        if (!extension_loaded('redis')) {
            throw new \Exception("redis扩展不存在");
        }

        try {
            $this->redis = new \Redis();
            $result = $this->redis->connect("192.168.33.10",6379, 5);
        } catch (\Exception $e){
            throw new \Exception("redis服务异常");
        }

        if ($result === false) {
            throw new \Exception("redis链接失败");
        }

    }

    public function get($key) {
        if (!$key){
            return '';
        }
        return $this->redis->get($key);
    }

}