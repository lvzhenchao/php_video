<?php
namespace App\Model\Es;

use EasySwoole\Core\Component\Di;

class EsBase {

    public $esClient = null;

    public function __construct()
    {
        $this->esClient = Di::getInstance()->get("ES");
    }

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

        $result = $this->esClient->search($params);

        return $result;
    }
}

