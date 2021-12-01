<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
class Category extends Controller
{
    function index()
    {
        $this->response()->write('I am category');
    }

}