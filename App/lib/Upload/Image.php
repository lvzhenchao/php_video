<?php
namespace App\lib\Upload;

class Image extends Base {

    public $fileType = "image";
    public $maxSize = 122;

    //文件后缀
    public $fileExtTypes = [
        'png',
        'jpeg',
    ];

}