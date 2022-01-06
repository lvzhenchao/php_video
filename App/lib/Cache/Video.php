<?php
namespace App\lib\Cache;
use App\Model\Video as VideoModel;
class Video{

    public function setIndexVideo(){
        $catIds = array_keys(\Yaconf::get("category.cats"));
        array_unshift($catIds, 0);

        //写video json 缓存数据
        $modelObj = new VideoModel();
        foreach ($catIds as $catId) {
            $condition = [];
            if (!empty($catId)) {
                $condition['cat_id'] = $catId;
            }

            try{
                $data = $modelObj->getVideoCacheData($condition);
            } catch (\Exception $e) {
                //报警-短信-邮件

                $data = [];
            }

            if (empty($data)) {
                continue;
            }

            foreach($data as &$list) {
                $list['create_time'] = date("Ymd H:i:s", $list['create_time']);
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }

            $flag = file_put_contents(EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json", json_encode($data));
            if (empty($flag)){
                //报警-短信-邮件
                echo "cat_id:".$catId."静态数据缓存失败".PHP_EOL;
            } else {
                echo "cat_id:".$catId."静态数据缓存成功".PHP_EOL;
            }


        }
    }

    public function getCache($catId){
        $videoFile = EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json";
        $videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
        $videoData = !empty($videoData) ? json_decode($videoData,true) : [];

        return $videoData;
    }
}