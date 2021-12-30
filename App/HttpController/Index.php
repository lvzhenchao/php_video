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
        $obj = new AliVod();
        $title = "singwa-imooc-video";
        $videoName = "1.mp4";
        $result = $obj->createUploadVideo($title, $videoName);

//        $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);
//
//        $uploadAuth = json_decode(base64_decode($result->UploadAuth), true);
//
//        $obj->initOssClient($uploadAuth, $uploadAddress);
//
//        $videoFile = "/home/work/hdtocs/imooc/imooc_esapi/webroot/video/2018/10/7648e6280470bbbc.mp4";
//        $result = $obj->uploadLocalFile($uploadAddress, $videoFile);
        print_r($result);
    }

    public function testali1() {
        $obj = new AliVod2();

        $videoFile = "/home/mycode/php_video/webroot/1.mp4";
        $result = $obj->testUploadLocalVideo(\Yaconf::get("aliyun.accessKeyId"), \Yaconf::get("aliyun.accessKeySecret"),$videoFile);

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

}