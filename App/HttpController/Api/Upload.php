<?php

namespace App\HttpController\Api;

use App\lib\Upload\Video;

class Upload extends Base
{

    public function file() {
        $request = $this->request();

        try {
            $obj = new Video($request);
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