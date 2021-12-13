<?php

namespace App\HttpController\Api;
use App\Model\Video as VideoModel;
use EasySwoole\Http\Message\Status;

class Video extends Base
{

    public function add() {
        $params = $this->request()->getRequestParam();

        $data = [
            'name' => $params['name'],
            'url' => $params['url'],
            'image' => $params['image'],
            'content' => $params['content'],
            'cat_id' => $params['cat_id'],
            'create_time' => time(),
            'status' =>\Yaconf::get('status.normal'),
        ];

        try{
            $modelObj = new VideoModel();
            $video_id =  $modelObj->add($data);
        } catch (\Exception $exception) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, $exception->getMessage());
        }

        if (empty($video_id)) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "提交视频有误", ['id'=>0]);
        }
        return $this->writeJson(Status::CODE_OK, 'ok', ['id'=>$video_id]);



        return $this->writeJson(200,'ok', $params);
    }


}