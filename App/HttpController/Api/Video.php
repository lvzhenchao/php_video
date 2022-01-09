<?php

namespace App\HttpController\Api;
use App\Model\Video as VideoModel;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\Task\TaskManager;
use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Utility\Validate\Rule;
use EasySwoole\Http\Message\Status;

class Video extends Base
{

    public $logType = "video:";

    /**
     * 视频播放页面基本信息接口
     *
     * @return void
     */
    public function index() {
        $id = intval($this->params['id']);
        if(empty($id)) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "请求不合法");
        }

        // 获取视频的基本信息
        try {
            $video = (new VideoModel())->getById($id);
        }catch(\Exception $e) {
            // 记录日志 $e->getMessage()
            return $this->writeJson(Status::CODE_BAD_REQUEST, "请求不合法");
        }
        if(!$video || $video['status'] != \Yaconf::get("status.normal")) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "该视频不存在");
        }
        $video['video_duration'] = gmstrftime("%H:%M:%S", $video['video_duration']);

        // 播放数统计逻辑
        // 投放task异步任务
        TaskManager::async(function() use($id) {
            // 逻辑
//            sleep(10);
//            // redis
//            print_r("测试异步任务");

            $res = Di::getInstance()->get("REDIS")->zincrby(\Yaconf::get("redis.video_play_key"), 1, $id);
var_dump($res);
            // 按天记录
        });

        return $this->writeJson(200, 'OK', $video);
    }

    public function add() {
        $params = $this->request()->getRequestParam();
        Logger::getInstance()->log($this->logType . "|add" .json_encode($params, JSON_UNESCAPED_UNICODE));

        //数据校验
        $ruleObj = new Rules();
        $ruleObj->add('name', "视频名称错误")
                ->withRule(Rule::REQUIRED)
                ->withRule(Rule::MIN_LEN,2)
                ->withRule(Rule::MAX_LEN, 20);
        $ruleObj->add('url', "视频地址错误")
            ->withRule(Rule::REQUIRED);
        $ruleObj->add('image', "图片名称错误")
            ->withRule(Rule::REQUIRED);
        $ruleObj->add('content', "视频描述错误")
            ->withRule(Rule::REQUIRED);
        $ruleObj->add('cat_id', "栏目错误")
            ->withRule(Rule::REQUIRED);

        $validata = $this->validateParams($ruleObj);
        if ($validata->hasError()) {
//            print_r($validata->getErrorList());
            return $this->writeJson(Status::CODE_BAD_REQUEST, $validata->getErrorList()->first()->getMessage());
        }

        $data = [
            'name' => $params['name'],
            'url' => $params['url'],
            'image' => $params['image'],
            'content' => $params['content'],
            'cat_id' => intval($params['cat_id']),
            'create_time' => time(),
            'uploader' => 'lzc',
            'status' =>\Yaconf::get('status.normal'),
        ];

        try{
            $modelObj = new VideoModel();
            $video_id =  $modelObj->add($data);
        } catch (\Exception $exception) {
            Logger::getInstance()->log($this->logType . "|add" .json_encode($exception->getMessage(), JSON_UNESCAPED_UNICODE));
            return $this->writeJson(Status::CODE_BAD_REQUEST, $exception->getMessage());
        }

        if (empty($video_id)) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "提交视频有误", ['id'=>0]);
        }
        return $this->writeJson(Status::CODE_OK, 'ok', ['id'=>$video_id]);



        return $this->writeJson(200,'ok', $params);
    }


}