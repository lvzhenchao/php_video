<?php

namespace App\HttpController\Api;

use App\lib\Redis\Redis;
use \EasySwoole\Core\Component\Di;

class Index extends Base
{

    public function video() {
//        new ab();
        $data = [
            'id' =>1,
            'name'=>"imooc",
            'param'=>$this->request()->getRequestParam()
        ];
        return $this->writeJson(200,'ok', $data);
//        $this->response()->write('I am category');
    }

    public function getVideo() {
        $db = Di::getInstance()->get('MYSQL');

        //写入
//        $data = Array (
//            "name" => "test product",
//            "url" => "baidu.com",
//            "content" => "百度内容",
//        );
//        $id = $db->insert ("video", $data);

        //单条
//        $result = $db->where("id", 15)->getOne("video");

        //所有数据
//        $result = $db->get("video");
        $result = $db->get("video", 2);//limit
        return $this->writeJson(200,'ok', $result);
    }

    public function getRedis() {
        //第一种
//        $redis = new \Redis();
//        $redis->connect("192.168.33.10",6379, 5);
//        $redis->set("singws", 90);

        //第二种
//        $result = Redis::getInstance()->get("singws");

        //第三种注入方式
        $result = Di::getInstance()->get("REDIS")->get("singws");
        return $this->writeJson(200,'ok', $result);
    }

    //需要先安装扩展
    public function yaconf() {
        $result = \Yaconf::get('redis');
        return $this->writeJson(200,'ok', $result);
    }

    public function pub() {
        $params = $this->request()->getRequestParam();
        Di::getInstance()->get("REDIS")->rPush('imooc_list_test',$params['f']);

    }

}