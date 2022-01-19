<?php

namespace App\HttpController;

use App\Lib\AliyunSdk\AliVod;
use App\Lib\AliyunSdk2\AliVod2;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use Elasticsearch\ClientBuilder;

class Index extends Controller
{
    function index()
    {

        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $params = [
            "index" => "imooc_video",
            "type"  => "video",
//            "id"    => 1,
            "body" => [
                'query' => [
                    'match' => [
                        'name' => '刘德华'
                    ],
                ],
            ],
        ];
//        $result = $client->get($params);
        $result = $client->search($params);


        return $this->writeJson(200, "ok", $result);

//        $this->response()->write('I am index');
    }

    function index_single()
    {


        $params = [
            "index" => "imooc_video",
            "type"  => "video",
//            "id"    => 1,
            "body" => [
                'query' => [
                    'match' => [
                        'name' => '刘德华'
                    ],
                ],
            ],
        ];

        $client = Di::getInstance()->get("ES");
        $result = $client->search($params);


        return $this->writeJson(200, "ok", $result);

//        $this->response()->write('I am index');
    }

    /**
     * 测试旧版上传
     *
     */
    public function testali() {
        $obj = new AliVod();
        $title = "singwa-imooc-video";
        $videoName = "1.mp4";
        $result = $obj->createUploadVideo($title, $videoName);

        $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);

        $uploadAuth = json_decode(base64_decode($result->UploadAuth), true);

        $obj->initOssClient($uploadAuth, $uploadAddress);

        $videoFile = "/home/mycode/php_video/webroot/1.mp4";
        $result = $obj->uploadLocalFile($uploadAddress, $videoFile);
        print_r($result);
    }

    /**
     * 测试新版上传
     *
     */
    public function testali1() {
        $obj = new AliVod2();

        $videoFile = "/home/mycode/php_video/webroot/1.mp4";
        $result = $obj->testUploadLocalVideo(\Yaconf::get("aliyun.accessKeyId"), \Yaconf::get("aliyun.accessKeySecret"),$videoFile);

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function testPlay() {
        $obj = new AliVod();
        $videoId = "737b631aab5644138f52b07f5cba9de6";
        $playInfo = $obj->getPlayInfo($videoId);

        print_r($playInfo->PlayInfoList->PlayInfo);
        print_r($playInfo);
    }

}