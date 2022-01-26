<?php

namespace App\lib\Map;


use mysql_xdevapi\DocResult;

class Map{

    //根据地址获取经纬度
    public static function getLngLat($address = ''){
        //https://api.map.baidu.com/geocoding/v3/?address=北京市海淀区上地十街10号&output=json&ak=您的ak&callback=showLocation //GET请求

        $data = [
            'address' => $address,
            'ak' => 'TCGoME0gIHZ2xZjHcaGUEfI0A7Hb4qun',
            'output' => 'json',
        ];
        $url = 'https://api.map.baidu.com/geocoding/v3/?' .http_build_query($data);

        //1、file_get_content($url)
        
        $res = self::DoCurl($url);

    }

    public function DoCurl($url, $type=0, $data=[]) {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果

        if ($type == 1) {
            curl_setopt($curl, CURLOPT_PORT, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }


        $responseText = curl_exec($curl);

        curl_close($url);

        return $responseText;
    }

    //获取静态图
    public function staticImage($center) {
        $data = [
            'center' => $center,
            'markers' => $center,
            'ak' => 'TCGoME0gIHZ2xZjHcaGUEfI0A7Hb4qun',
            'width' => '400',
            'height' => '300',
        ];
        $url = 'https://api.map.baidu.com/?' .http_build_query($data);

        $res = self::DoCurl($url);

//        <img style="margin:20px" width="280" height="140" src="<?=$res?>"/>
    }


}