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
                    //分片数量
                    'number_of_shards'   => 1,
                    //分片副本
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
            'type' => $index_name,//这里是索引名，相当于数据库名
            'body' => [

                '_source' => [
                    //查找格式：开启的话代表可以在原数据查找，已插入数据的格式什么样，就以什么格式存储，并查找
                    //默认是已倒排索引
                    'enabled' => true
                ],
                //下面是数据类型定义，相当于数据库字段
                'properties' => [
                    'id' => [
                        'type' => 'long', // 整型
                        'index' => 'false', // 非全文搜索
                    ],
                    'good_sn' => [
                        'type' => 'text', // 字符串型
                        'index' => 'true', // 全文搜索
//                        'analyzer' => 'ik_max_word'
                    ],
                    'good_name' => [
                        'type' => 'text',// 字符串型
                        'index' => 'true', // 全文搜索
//                        'analyzer' => 'ik_max_word'
                    ],
                    'good_introduction' => [
                        'type' => 'text',
                        'index' => 'true', // 全文搜索
//                        'analyzer' => 'ik_max_word'
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

    //添加文档
    public function addDoc($id = 1, $doc = [], $index_name = 'shop_good')
    {

        $doc = [
            'id' => 1,
            'good_sn' => '4217251852947',
            'good_name' => '【12期免息 再减500元】Apple/苹果 iPhone 11全网通4G 超广角拍照手机苏宁易购官方store 苹果11 ',
            'good_introduction' => '选套餐一免费享更多好礼',
            'good_descript' => '商品详细信息'
        ];

        $params = [
            'index' => $index_name,
            'type' => $index_name,
            'id' => $doc['id'],
            'body' => $doc
        ];

        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();

        $result = $client->index($params);
        return $this->writeJson(200, "ok", $result);
//        {
//            "_index": "shop_good",
//            "_type": "shop_good",
//            "_id": "1",
//            "_version": 6,
//            "result": "updated",
//            "_shards": {
//                    "total": 1,
//                "successful": 1,
//                "failed": 0
//            },
//            "_seq_no": 5,
//            "_primary_term": 1
//        }
    }

    public function getDoc($id = 1, $index_name = 'shop_good')
    {
        $params = [
            'index' => $index_name,
            'type' => $index_name,
            'id' => $id
        ];

        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $result = $client->get($params);
        return $this->writeJson(200, "ok", $result);
    }

    public function searchDoc($keywords = "", $from = 0, $size = 10, $index_name = "shop_good")
    {
        $keywords = '手机';
        $params = [
            'index' => $index_name,
            'type' => $index_name,
            'body' => [
                'query' => [
                    //条件查询
                    'match' => [
                        'good_name' => [
                            'query' => "苹果"
                        ]
                    ]
                ],
                'sort' => [
                    'id' => [
                        'order' => 'desc'
                    ]
                ],
                'from' => $from,
                'size' => $size
            ]
        ];
        $hosts = [
            "192.168.33.10:9200",
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $result = $client->search($params);
        return $this->writeJson(200, "ok", $result);
    }

}