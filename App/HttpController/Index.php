<?php

namespace App\HttpController;

use App\Lib\AliyunSdk\AliVod;
use App\Lib\AliyunSdk2\AliVod2;
use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
        $this->response()->write('I am index');
    }

    public function testali() {
        $obj = new AliVod2();
        $result = $obj->testUploadLocalVideo(\Yanconf::get("aliyun.accessKeyId"), \Yanconf::get("aliyun.accessKeySecret"),1);
        print_r($result);
    }

}