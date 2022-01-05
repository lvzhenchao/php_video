# php_video

# 参考
http://www.javashuo.com/article/p-nsgealtt-dc.html
https://blog.csdn.net/qq_31164125/article/details/118091677

# 执行如下命令用于更新命名空间的新引进资源：
composer dump-autoload

# 压力测试工具
yum -y install httpd-tools
ab -n 1000 -c 100 https://baidu.com/
top命令查看资源

# 消息队列
生产者--> Broker[消息处理中心：存储...] --> 消费者

# Yaconf
Yaconf是一个高性能的PHP配置容器， 它在PHP启动的时候把格式为INI的配置文件Parse后存储在PHP的常驻内存中
php.ini增加一个默认文件指向：yaconf.directory = /home/mycode/php_video/ini
## 宝塔安装
- 下载
git clone https://github.com/laruence/yaconf
- 进入源码目录
cd yaconf
- 生成配置
/www/server/php/74/bin/phpize
./configure --with-php-config=/www/server/php/74/bin/php-config
- 编译
make && make install
- 写配置文件
echo "extension = yaconf.so" >> /www/server/php/74/etc/php.ini
- 重启php
- 检查
php -m

# 添加前端vue页面
# 添加前端vue的nginx转发
` 
    index index.php index.html index.htm default.php default.htm default.html;
    root /home/mycode/php_video/webroot;
    location / {
        if (!-e $request_filename) {
            proxy_pass http://127.0.0.1:9501;//如果访问的url不存在，则转发到后端
        }
    }
`

# 反射机制一定要熟知：利用反射机制实现图片和视频的上传
`

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

`

# 阿里云点播
