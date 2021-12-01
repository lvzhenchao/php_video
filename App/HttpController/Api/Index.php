<?php

namespace App\HttpController\Api;

class Index extends Base
{
    
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