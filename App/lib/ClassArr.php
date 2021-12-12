<?php
namespace App\lib;

/**
 * Class ClassArr
 * 反射机制
 * @package App\lib
 */
class ClassArr {

    public  function uploadClassStat() {
        return [
            "image" => "App\lib\Upload\Image",
            "video" => "App\lib\Upload\Video",
        ];
    }

    public function initClass($type, $suportedClass, $params = [], $needInstance = true) {
        if (!array_key_exists($type, $suportedClass)) {
            return false;
        }

        $className = $suportedClass[$type];

        return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;

    }
}
