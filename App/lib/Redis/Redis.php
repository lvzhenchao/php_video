<?php

namespace App\lib\Redis;

use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\Singleton;

class Redis{
    use Singleton;

    public $redis = "";

    private function __construct(){
        if (!extension_loaded('redis')) {
            throw new \Exception("redis扩展不存在");
        }

        try {
//            $redisConfig = Config::getInstance()->getConf("REDIS");//读取
//            $redisConfig = Config::getInstance()->getConf("redis");//读取自定义文件redis
            $redisConfig = \Yaconf::get('redis');//使用yaconf读取自定义文件redis
//            var_dump($redisConfig);

            $this->redis = new \Redis();
            $result = $this->redis->connect($redisConfig['host'],$redisConfig['port'], $redisConfig['time_out']);
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

    public function lpop($key){
        if (!$key){
            return '';
        }
        return $this->redis->lPop($key);
    }

    public function rPush($key, $value){
        if (!$key){
            return '';
        }
        return $this->redis->rPush($key, $value);
    }

}