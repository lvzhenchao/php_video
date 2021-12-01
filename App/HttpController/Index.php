<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
        $this->response()->write('I am index');
    }

}