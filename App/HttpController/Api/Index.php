<?php

namespace App\HttpController\Api;

use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
//        $data = [
//            'id' =>1,
//            'name'=>"imooc"
//        ];
//        return $this->writeJson(200,'ok', $data);
//        $this->response()->write('I am category');
    }

    public function video() {
        $data = [
            'id' =>1,
            'name'=>"imooc",
            'param'=>$this->request()->getRequestParam()
        ];
        return $this->writeJson(200,'ok', $data);
        $this->response()->write('I am category');
    }

}