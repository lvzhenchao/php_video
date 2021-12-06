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

#消息队列
生产者--> Broker[消息处理中心：存储...] --> 消费者

#Yaconf
Yaconf是一个高性能的PHP配置容器， 它在PHP启动的时候把格式为INI的配置文件Parse后存储在PHP的常驻内存中