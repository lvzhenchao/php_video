<?php
namespace App\Lib\AliyunSdk;
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录。
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-oss-php-sdk-master/autoload.php';

use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class AliVod {


	public $regionId = "cn-beijing";
	public $client ;
	public $ossClient;

    /**
     *
     * 初始化
     * AliVod constructor.
     */
	public function __construct() {
	    $profile = \DefaultProfile::getProfile($this->regionId, \Yaconf::get("aliyun.accessKeyId"), \Yaconf::get("aliyun.accessKeySecret"));
	    $this->client = new \DefaultAcsClient($profile);
	}

	/**
     * 获取视频上传地址和凭证
	 * [create_upload_video description]
	 * @auth   singwa
	 * @date   2018-10-29T07:58:49+0800
	 * @param  [type]                   $vodClient [description]
	 * @return [type]                              [description]
	 */
	public function createUploadVideo($title, $videoFileName, $other = []) {
	    $request = new vod\CreateUploadVideoRequest();
	    $request->setTitle($title);        // 视频标题(必填参数)
	    $request->setFileName($videoFileName); // 视频源文件名称，必须包含扩展名(必填参数)
	    if(!empty($other['description'])) {
	    	$request->setDescription("视频描述");  // 视频源文件描述(可选)
		}
		// tags 
	    
	    $result = $this->client->getAcsResponse($request);
	    if(empty($result) || empty($result->VideoId)) {
	    	throw new \Exception("获取上传凭证不合法");
	    }

//        (
//            [VideoId] => 2455a6661c494cd498167b83ec892c0c
//            [UploadAddress] => eyJFbmRwb2ludCI6Imh0dHBzOi8vb3NzLWNuLWJlaWppbmcuYWxpeXVuY3MuY29tIiwiQnVja2V0Ijoib3V0aW4tMjYxYmRjOWU2NzFmMTFlY2I1MzIwMDE2M2UwMzg3OTMiLCJGaWxlTmFtZSI6InN2LzFiMWQzNzlkLTE3ZTA5M2Q5ODcwLzFiMWQzNzlkLTE3ZTA5M2Q5ODcwLm1wNCJ9
//            [RequestId] => 35FAAB8A-117C-51A6-8D01-E2D95F8A1935
//            [UploadAuth] => eyJTZWN1cml0eVRva2VuIjoiQ0FJUy9RUjFxNkZ0NUIyeWZTaklyNURIR3RERDMrNUQwYVdPTjBUcm9Ia2hlYnRkdHYzZHJqejJJSDFOZEhGb0FlNGF0UDR4blcxVjdmc2NsclVxRmNBZEd4eWFOSmNodHNzS3JWLzlKcGZadjh1ODRZQURpNUNqUVlkMXNiMXptNTI4V2Y3d2FmK0FVQ2pHQ1RtZDVQWVlvOWJUY1RHbFFDWnVXLy90b0pWN2I5TVJjeENsWkQ1ZGZybC9MUmRqcjhsbzF4R3pVUEcyS1V6U24zYjNCa2hsc1JZZTcyUms4dmFIeGRhQXpSRGNnVmJtcUpjU3ZKK2pDNEM4WXM5Z0c1MTlYdHlwdm9weGJiR1Q4Q05aNXo5QTlxcDlrTTQ5L2l6YzdQNlFIMzViNFJpTkw4L1o3dFFOWHdoaWZmb2JIYTlZcmZIZ21OaGx2dkRTajQzdDF5dFZPZVpjWDBha1E1dTdrdTdaSFArb0x0OGphWXZqUDNQRTNyTHBNWUx1NFQ0OFpYVVNPRHREWWNaRFVIaHJFazRSVWpYZEk2T2Y4VXJXU1FDN1dzcjIxN290ZzdGeXlrM3M4TWFIQWtXTFg3U0IyRHdFQjRjNGFFb2tWVzRSeG5lelc2VUJhUkJwYmxkN0JxNmNWNWxPZEJSWm9LK0t6UXJKVFg5RXoycExtdUQ2ZS9MT3M3b0RWSjM3V1p0S3l1aDRZNDlkNFU4clZFalBRcWl5a1QzOUhRNERlN3ROMTdUM01wS1M4YUtNeFA3cEFkVExFZmNhb0Y1WEF6MnpwQ09HVWlGWE5qYWpwSTloTzEyRjU4WFhpZmFTcXBsc0cxb2p1SThHQTFqY2ZOMW44Z2M4dS9QcnNVK2VxTFN6RFNIeHVYRW12WURTcG9VVXRocG9PNjc3anJmRDVUS1A3Q1BJSnZBendjdU1CenhqQkUzb2RpUW9tL3pEMWkxZjRSVVp6MFBNWWt4QXRnblBqemp1SlpkSGlhVFRtRWtwV1BrRHhiN3FMQjY3NVhobEJONno1TElHWlBodlp1Z21VSXpuZ0ZnNW5xVy84VUhxNGRPWTdFbytPckR1QzVJaVlwOVZCU3owN0ova1pNb3RoZlJZQ0R2emFUamtmWG5YTE5QOUdvQUJmbEFqc2xsZTBacUxUMjZoSmRLWHFGTk9IVUNXMEIvRjBlOUdxT2ZDS0RSSzFyN29FUUszcVpEK2ppNS9jNFQyS2JtNFpGTklBQUhoM2ErODgxSWZ3N2ZxWGZNZll6UjFLRld5UUUzaHBXN2phdG9yaXlLKzgxMy9kbmJtczFLSDlSbVl4aFlQajZvY3RBVi9iTmM4TCtxVjZBanZManFremVoQnlDejVZeVE9IiwiQWNjZXNzS2V5SWQiOiJTVFMuTlNyUWp3MjFiZmdlNWJaRHl0dTRxWTh2TCIsIkV4cGlyZVVUQ1RpbWUiOiIyMDIxLTEyLTMwVDAzOjQ4OjU4WiIsIkFjY2Vzc0tleVNlY3JldCI6IkhlUG1iVFRobXVHRmNEbXhlNHdkTHl0cjdWOHhzUW9vZjZjc3ROUzZLYU1jIiwiRXhwaXJhdGlvbiI6IjM2MDAiLCJSZWdpb24iOiJjbi1iZWlqaW5nIn0=
//        )
	    return $result;
	}

	public function initOssClient($uploadAuth, $uploadAddress) {
		//$uploadAddress['Endpoint'] = "http://oss-cn-shanghai.aliyuncs.com";
        $this->ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'],
        false, $uploadAuth['SecurityToken']);
    	$this->ossClient->setTimeout(86400*7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
    	$this->ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
    
	}

	function uploadLocalFile($uploadAddress, $localFile) {
    	return $this->ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
	}

	/**
     * 获取播放地址
	 * [getPlayInfo description]
	 * @auth   singwa
	 * @date   2018-10-29T09:19:05+0800
	 * @param  integer                  $videoId [description]
	 * @return [type]                            [description]
	 */
	public function getPlayInfo($videoId = 0) {

		if(empty($videoId)) {
			return [];
		}
		$request = new vod\GetPlayInfoRequest();
		$request->setVideoId($videoId);
		$request->setAcceptFormat("JSON");

		return $this->client->getAcsResponse($request);
	}
}