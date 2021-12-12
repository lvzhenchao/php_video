<?php

namespace App\HttpController\Api;

use App\lib\ClassArr;
use App\lib\Upload\Image;
use App\lib\Upload\Video;

class Upload extends Base
{

    public function file() {
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        $types = array_keys($files);
        $type = $types[0];
        if (empty($type)) {
            return $this->writeJson(400, '上传文件不合法');
        }

        //将当前接口既支持图片和视频
        //第一种
//        if ($type == "image") {
//            $obj = new Image($request);
//        } else if($type == "video") {
//            $obj = new Video($request);
//        }

        //第二种
//        $obj = '\App\lib\Upload'.$type;

        //第三种 php反射机制
        $classObj = new ClassArr();
        $classStats = $classObj->uploadClassStat();
        $obj = $classObj->initClass($type, $classStats, [$request, $type]);

        try {
//            $obj = new Video($request);
//            $obj = new Image($request);
            $file = $obj->upload();
        } catch (\Exception $e) {
            return $this->writeJson(400, $e->getMessage(), []);
        }

        if (empty($file)) {
            return $this->writeJson(400, "上传失败", []);
        }

        $data = [
            'url' => $file
        ];
        return $this->writeJson(200, 'ok', $data);


    }



}