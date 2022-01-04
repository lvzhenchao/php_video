<?php

namespace App\HttpController\Api;

use App\lib\Redis\Redis;
use \EasySwoole\Core\Component\Di;
use App\Model\Video as VideoModel;
use EasySwoole\Core\Http\Message\Status;

class Index extends Base
{

    /**
     * 第一套方案 原始  - 读取 Mysql
     * [lists description]
     * @auth   singwa
     * @return [type] [description]
     */
    public function lists0() {

        $condition = [];
        if(!empty($this->params['cat_id'])) {
            $condition['cat_id'] = intval($this->params['cat_id']);
        }
        //
        // 1 查询 条件 下 count
        // 2 lists
        //

        try {
            $videoModel = new VideoModel();
            $data = $videoModel->getVideoData($condition, $this->params['page'], $this->params['size']);
        }catch(\Exception $e) {
            // $e->getMessage();
            return $this->writeJson(Status::CODE_BAD_REQUEST, "服务异常");
        }

        if(!empty($data['lists'])) {
            foreach($data['lists'] as &$list) {
                //$data['lists'][$k]['create_time'] = date("Ymd H:i:s", $data['lists'][$k]['create_time']);
                $list['create_time'] = date("Ymd H:i:s", $list['create_time']);
                // 00:01:07
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
        }
        return $this->writeJson(Status::CODE_OK, "OK", $data);
    }



    /**
     * 第二套方案 直接读取 静态化 json数据
     * [lists description]
     * @auth   singwa
     * @return [type] [description]
     */
    public function lists() {
        $catId = !empty($this->params['cat_id']) ? intval($this->params['cat_id']) : 0;
        try {
            $videoData = (new VideoCache())->getCache($catId);
        }catch(\Exception $e) {
            return $this->writeJson(Status::CODE_BAD_REQUEST , "请求失败");
        }

        $count = count($videoData);

        return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingDatas($count, $videoData));
    }

    public function video() {
//        new ab();
        $data = [
            'id' =>1,
            'name'=>"imooc",
            'param'=>$this->request()->getRequestParam()
        ];
        return $this->writeJson(200,'ok', $data);
//        $this->response()->write('I am category');
    }

    public function getVideo() {
        $db = Di::getInstance()->get('MYSQL');

        //写入
//        $data = Array (
//            "name" => "test product",
//            "url" => "baidu.com",
//            "content" => "百度内容",
//        );
//        $id = $db->insert ("video", $data);

        //单条
//        $result = $db->where("id", 15)->getOne("video");

        //所有数据
//        $result = $db->get("video");
        $result = $db->get("video", 2);//limit
        return $this->writeJson(200,'ok', $result);
    }

    public function getRedis() {
        //第一种
//        $redis = new \Redis();
//        $redis->connect("192.168.33.10",6379, 5);
//        $redis->set("singws", 90);

        //第二种
//        $result = Redis::getInstance()->get("singws");

        //第三种注入方式
        $result = Di::getInstance()->get("REDIS")->get("singws");
        return $this->writeJson(200,'ok', $result);
    }

    //需要先安装扩展
    public function yaconf() {
        $result = \Yaconf::get('redis');
        return $this->writeJson(200,'ok', $result);
    }

    public function pub() {
        $params = $this->request()->getRequestParam();
        Di::getInstance()->get("REDIS")->rPush('imooc_list_test',$params['f']);

    }

}