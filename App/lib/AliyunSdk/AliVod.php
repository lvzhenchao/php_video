<?php
namespace App\Lib\AliyunSdk;
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-php-sdk-core/Config.php';
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-oss-php-sdk-2.4.3/autoload.php';

use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class AliVod {

    public $regionId = 'cn-beijing';
    public $client;
    public function __construct($accessKeyId, $accessKeySecret) {
            // 根据点播接入服务所在的Region填写，例如：接入服务在上海，则填cn-shanghai
        $profile = \DefaultProfile::getProfile($this->regionId, \Yanconf::get("aliyun.accessKeyId"), \Yanconf::get("aliyun.accessKeySecret"));
        $this->client = new \DefaultAcsClient($profile);
    }

    public function create_upload_video($title, $videoFileName,$other=[]) {
        $request = new vod\CreateUploadVideoRequest();
        $request->setTitle($title);        // 视频标题(必填参数)
        $request->setFileName($videoFileName); // 视频源文件名称，必须包含扩展名(必填参数)
        if (!empty($other['description'])) {
            $request->setDescription("视频描述");  // 视频源文件描述(可选)
        }
        //CoverURL示例：http://example.alicdn.com/tps/TB1qnJ1PVXXXXXCXXXXXXXXXXXX-700-****.png
//        $request->setCoverURL("<your Cover URL>"); // 自定义视频封面(可选)
//        $request->setTags("标签1,标签2"); // 视频标签，多个用逗号分隔(可选)
        return $this->client->getAcsResponse($request);
    }

}
