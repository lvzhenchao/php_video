<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
class Category extends Controller
{
    function index()
    {
        $data = [
            'id' =>1,
            'name'=>"test"
        ];
        return $this->writeJson(200,'ok', $data);
        $this->response()->write('I am category');
    }

}