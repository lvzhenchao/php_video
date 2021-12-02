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
        $result = $db->where("id", 15)->getOne("video");
        return $this->writeJson(200,'ok', $result);
    }

}