<?php
namespace App\Lib\AliyunSdk2;

//require_once EASYSWOOLE_ROOT.'/App/lib/AliyunSdk2/Autoloader.php';
require_once dirname(__DIR__) . '/AliyunSdk2/Autoloader.php';
require_once dirname(__DIR__) . '/AliyunSdk2/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录
use vod\Request\V20170321 as vod;

class AliVod2 {

//    function initVodClient($accessKeyId, $accessKeySecret) {
//        $regionId = 'cn-shanghai';  // 点播服务接入区域
//        $profile = \DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
//        return new \DefaultAcsClient($profile);
//    }


// 测试上传本地视频
    function testUploadLocalVideo($accessKeyId, $accessKeySecret, $filePath)
    {
        try {
            $uploader = new \AliyunVodUploader($accessKeyId, $accessKeySecret);
            $uploadVideoRequest = new \UploadVideoRequest($filePath, 'testUploadLocalVideo via PHP-SDK');
            //$uploadVideoRequest->setCateId(1);
            //$uploadVideoRequest->setCoverURL("http://xxxx.jpg");
            //$uploadVideoRequest->setTags('test1,test2');
            //$uploadVideoRequest->setStorageLocation('outin-xx.oss-cn-beijing.aliyuncs.com');
            //$uploadVideoRequest->setTemplateGroupId('6ae347b0140181ad371d197ebe289326');
//            $userData = array(
//                "MessageCallback"=>array("CallbackURL"=>"https://demo.sample.com/ProcessMessageCallback"),
//                "Extend"=>array("localId"=>"xxx", "test"=>"www")
//            );
//            $uploadVideoRequest->setUserData(json_encode($userData));
            $res = $uploader->uploadLocalVideo($uploadVideoRequest);
            return $res;
//            echo "<pre>";
//            print_r($res);
//            echo "</pre>";
        } catch (Exception $e) {
            printf("testUploadLocalVideo Failed, ErrorMessage: %s\n Location: %s %s\n Trace: %s\n",
                $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        }
    }

// 测试上传网络视频
    function testUploadWebVideo($accessKeyId, $accessKeySecret, $fileURL)
    {
        try {
            $uploader = new AliyunVodUploader($accessKeyId, $accessKeySecret);
            $uploadVideoRequest = new UploadVideoRequest($fileURL, 'testUploadWebVideo via PHP-SDK');
            $res = $uploader->uploadWebVideo($uploadVideoRequest);
            print_r($res);
        } catch (Exception $e) {
            printf("testUploadWebVideo Failed, ErrorMessage: %s\n Location: %s %s\n Trace: %s\n",
                $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        }
    }

// 测试上传本地m3u8视频
    function testUploadLocalM3u8($accessKeyId, $accessKeySecret, $m3u8FilePath)
    {
        try {
            $uploader = new AliyunVodUploader($accessKeyId, $accessKeySecret);
            $uploadVideoRequest = new UploadVideoRequest($m3u8FilePath, 'testUploadLocalM3u8 via PHP-SDK');
            // 调用接口解析m3u8的分片地址列表，如果解析结果不准确，请自行拼接地址列表(默认分片文件和m3u8文件位于同一目录)
            $sliceFiles = $uploader->parseM3u8File($m3u8FilePath);
            //print_r($sliceFiles);
            $res = $uploader->uploadLocalM3u8($uploadVideoRequest, $sliceFiles);
            print_r($res);
        } catch (Exception $e) {
            printf("testUploadLocalM3u8 Failed, ErrorMessage: %s\n Location: %s %s\n Trace: %s\n",
                $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        }
    }

// 测试上传网络m3u8视频
    function testUploadWebM3u8($accessKeyId, $accessKeySecret, $m3u8FileUrl)
    {
        try {
            $uploader = new AliyunVodUploader($accessKeyId, $accessKeySecret);
            $uploadVideoRequest = new UploadVideoRequest($m3u8FileUrl, 'testUploadWebM3u8 via PHP-SDK');
            // 调用接口解析m3u8的分片地址列表，如果解析结果不准确，请自行拼接地址列表(默认分片文件和m3u8文件位于同一目录)
            $sliceFileUrls = $uploader->parseM3u8File($m3u8FileUrl);
            //print_r($sliceFileUrls);
            $res = $uploader->uploadWebM3u8($uploadVideoRequest, $sliceFileUrls);
            print_r($res);
        } catch (Exception $e) {
            printf("testUploadWebM3u8 Failed, ErrorMessage: %s\n Location: %s %s\n Trace: %s\n",
                $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        }
    }

}
