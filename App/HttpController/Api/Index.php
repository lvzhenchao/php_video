<?php

namespace App\HttpController\Api;

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

}