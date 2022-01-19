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

    //创建映射
    public function createMappings($index_name = 'shop_good')
    {
        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $params = [
            'index' => $index_name,//这里是索引名，相当于数据库名
            'body' => [
                //下面是数据类型定义，相当于数据库字段
                'properties' => [
                    'id' => [
                        'type' => 'long', // 整型
                        'index' => 'false', // 非全文搜索
                    ],
                    'good_sn' => [
                        'type' => 'text', // 字符串型
                        'index' => 'true', // 全文搜索
                        'analyzer' => 'ik_max_word'
                    ],
                    'good_name' => [
                        'type' => 'text',// 字符串型
                        'index' => 'true', // 全文搜索
                        'analyzer' => 'ik_max_word'
                    ],
                    'good_introduction' => [
                        'type' => 'text',
                        'index' => 'true', // 全文搜索
                        'analyzer' => 'ik_max_word'
                    ],
                    'good_descript' => [
                        'type' => 'text',
                        'index' => 'false', //非 全文搜索
                    ],
                ]
            ]
        ];

        $result = $client->indices()->putMapping($params);

        return $this->writeJson(200, "ok", $result);
//        {
//            "acknowledged": true
//        }

    }



}