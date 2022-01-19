<?php

namespace App\Model\Es;

use EasySwoole\Core\AbstractInterface\Singleton;
use Elasticsearch\ClientBuilder;

class EsClient {
    use Singleton;

    public $esClient = null;

    private function __construct() {
        $config = \Yaconf::get('es');
        try {
            $this->esClient = ClientBuilder::create()->setHosts([$config['host'].":".$config['port']])->build();
        } catch(\Exception $e) {
            throw new \Exception("es服务异常");
        }

        if(empty($this->esClient)) {
            throw new \Exception("es服务异常");
        }
    }


    /**
     * 魔术方法
     * 当类中不存在该方法时候，直接调用call 实现调用底层redis相关的方法
     * @auth   singwa
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments) {

        return $this->esClient->$name(...$arguments);
    }

}