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

# 静态化API [https://www.jianshu.com/p/6d605248d5a7]
- 把接口生成json文件存储到服务器本地
- 一般的系统瓶颈点，在数据库这层
- 方式：
`
方案1 easySwoole + crontab
方案2 easySwoole定时器
方案3 Swoole table
方案4 Redis
`

# ES
[https://blog.csdn.net/qq_28289405/article/details/88566245?spm=1001.2101.3001.6661.1&utm_medium=distribute.pc_relevant_t0.none-task-blog-2%7Edefault%7ECTRLIST%7Edefault-1.no_search_link&depth_1-utm_source=distribute.pc_relevant_t0.none-task-blog-2%7Edefault%7ECTRLIST%7Edefault-1.no_search_link&utm_relevant_index=1]

- java.lang.RuntimeException: can not run elasticsearch as root 不能用root启动
- Exception in thread "main" java.nio.file.AccessDeniedException: /home/es/elasticsearch-6.6.1/config/jvm.options
- 报错could not find java in bundled jdk at /opt/elasticsearch-7.7.0/jdk/bin/java  //附权限 chown -R

`

    解决方法有两类：
    1、修改elaticsearch配置，使其可以允许root用户启动（不建议）
        #在执行elasticSearch时加上参数-Des.insecure.allow.root=true，完整命令如下
        ./elasticsearch -Des.insecure.allow.root=true
        #或者 用vi打开elasicsearch执行文件，在变量ES_JAVA_OPTS使用前添加以下命令
        ES_JAVA_OPTS="-Des.insecure.allow.root=true"
    
    2、为elaticsearch创建用户并赋予相应权限
    命令如下 具体介绍参考我的另一篇博客linux创建新用户并将为其赋予权限 
        adduser es
        passwd es
        chown -R es:es elasticsearch-6.3.2/
        chmod 770 elasticsearch-6.3.2/
        
    3、切换到es用户
        su es
        ./bin/elasticsearch

`
- 参考相关
[https://blog.csdn.net/ray_352801250/category_9664768.html]

- max file descriptors [65535] for elasticsearch process is too low 进程相关权限设定
[https://blog.csdn.net/jiahao1186/article/details/90235771]

- max virtual memory areas vm.max_map_count [65530] is too low, increase to at least [262144] 内存相关设定
[https://xdoctorx.blog.csdn.net/article/details/106247111?spm=1001.2101.3001.6661.1&utm_medium=distribute.pc_relevant_t0.none-task-blog-2%7Edefault%7ECTRLIST%7Edefault-1.pc_relevant_paycolumn_v2&depth_1-utm_source=distribute.pc_relevant_t0.none-task-blog-2%7Edefault%7ECTRLIST%7Edefault-1.pc_relevant_paycolumn_v2&utm_relevant_index=1]

`

    修改/etc/sysctl.conf文件，增加配置
        vm.max_map_count=262144
    执行命令sysctl -p生效

`
# elasticsearch-head 跨域访问es
- config/elasticsearch.yml

`

    # 开启跨域
    http.cors.enabled: true
    # 允许所有
    http.cors.allow-origin: "*"

`

# kibana安装启动
[https://blog.csdn.net/qq_28289405/article/details/88572569]

# mysql数据转es
[https://cloud.tencent.com/developer/article/1051451]
[http://www.dreamwu.com/post-1195.html]

# es集群配置

`

    （1）cluster.name【】
    集群名字，三台集群的集群名字都必须一致
    
    （2）node.name【】
    节点名字，三台ES节点字都必须不一样
    
    （3）discovery.zen.minimum_master_nodes:2
    表示集群最少的master数，如果集群的最少master数据少于指定的数，将无法启动，官方推荐node master数设置为集群数/2+1，我这里三台ES服务器，配置最少需要两台master，整个集群才可正常运行，
    
    （4）node.master【】
    该节点是否有资格选举为master，如果上面设了两个mater_node 2，也就是最少两个master节点，则集群中必须有两台es服务器的配置为node.master: true的配置，配置了2个节点的话，如果主服务器宕机，整个集群会不可用，所以三台服务器，需要配置3个node.masdter为true,这样三个master，宕了一个主节点的话，他又会选举新的master，还有两个节点可以用，只要配了node master为true的ES服务器数正在运行的数量不少于master_node的配置数，则整个集群继续可用，我这里则配置三台es node.master都为true，也就是三个master，master服务器主要管理集群状态，负责元数据处理，比如索引增加删除分片分配等，数据存储和查询都不会走主节点，压力较小，jvm内存可分配较低一点
    
    （5）node.data
    存储索引数据，三台都设为true即可
    
    （6）bootstrap.memory_lock: true
    锁住物理内存，不使用swap内存，有swap内存的可以开启此项
    
    （7）discovery.zen.ping_timeout: 3000s
    自动发现拼其他节点超时时间
    
    
    （8）discovery.zen.ping.unicast.hosts: ["172.16.0.8:9300","172.16.0.6:9300","172.16.0.22:9300"] 【】
    设置集群的初始节点列表，集群互通端口为9300


`




















