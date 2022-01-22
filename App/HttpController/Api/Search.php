<?php

namespace App\HttpController\Api;



use App\Model\Es\EsVideo;
use EasySwoole\Http\Message\Status;

class Search extends Base
{
    public function index()
    {
        $keyword = trim($this->params['keyword']);
        if (empty($keyword)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingDatas(0, []));
        }


        $esObj  = new EsVideo();
        $result = $esObj->searchByName($keyword, $this->params['from'], $this->params['size']);

        if (empty($result)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingDatas(0, []));
        }

        $hits = $result['hits']['hits'];
        $total = $result['hits']['total'];

        if (empty($total)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingDatas(0, []));
        }

        foreach ($hits as $hit) {
            $source = $hit['_source'];
            $resData[] = [
                'id' => $source['_id'],
                'name' => $source['name'],
                'image' => $source['image'],
                'uploader' => $source['uploader'],
                'create_time' => '',
                'video_duration' => '',
                'keywords' => [$keyword],
            ];
        }

        return $this->writeJson(200, "ok", $this->getPagingDatas($total, $resData));
    }
}
