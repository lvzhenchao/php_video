<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use Elasticsearch\ClientBuilder;

class Es extends Controller
{


    //创建索引
    function index($index_name = "shop_good")
    {

        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $params = [
            'index' => $index_name,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0
                ]
            ],
        ];

        $result = $client->indices()->create($params);
        return $this->writeJson(200, "ok", $result);
//        {
//            "code": 200,
//            "msg": "ok",
//            "result": {
//                "acknowledged": true,
//                "shards_acknowledged": true,
//                "index": "shop_good"
//            }
//        }

    }



}