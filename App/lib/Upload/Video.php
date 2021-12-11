<?php
namespace App\lib\Upload;

class Video extends Base {

    public $fileType = "video";
    public $maxSize = 122;

    //文件后缀
    public $fileExtTypes = [
        'mp4',
        'x-flv',
    ];

}