<?php
namespace App\Model\Es;

use EasySwoole\Core\Component\Di;

class EsVideo {

    public $index = "imooc_video";
    public $type  = "video";

    public function searchByName($name, $search_type = "match") {
        $name = trim($name);
        if (empty($name)) {
            return [];
        }

        $params = [
            "index" => $this->index,
            "type"  => $this->type,
            "body" => [
                'query' => [
                    $search_type => [
                        'name' => $name
                    ],
                ],
            ],
        ];

        $client = Di::getInstance()->get("ES");
        $result = $client->search($params);


        return $result;
    }
}

