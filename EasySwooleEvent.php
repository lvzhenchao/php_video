<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use App\lib\Redis\Redis;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Swoole\Process\ProcessManager;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Component\Di;
use EasySwoole\Core\Utility\File;
use App\Lib\Process\ConsumerTest;
use EasySwoole\Core\Component\Crontab\CronTab;
use App\lib\Cache\Video as videoCache;
use EasySwoole\Core\Swoole\Time\Timer;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');

        self::loadConf(EASYSWOOLE_ROOT . '/Config');
    }

    static function loadConf($ConfPath)
    {
        $Conf = Config::getInstance();
        $files = File::scanDir($ConfPath);
//        var_dump($files);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        Di::getInstance()->set('MYSQL',\MysqliDb::class,Array (
                'host' => '192.168.33.10',
                'username' => 'root',
                'password' => 'BspKCZLRZWeHeaTR',
                'db'=> 'imooc_video',
                'port' => 3306,
                'charset' => 'utf8')
        );

        //将redis注入
        Di::getInstance()->set('REDIS', Redis::getInstance());

        //将生产者模型加入到新的进程内，并注入
        $allNum = 3;
        for ($i = 0 ;$i < $allNum;$i++){
            ProcessManager::getInstance()->addProcess("imooc_consumer_testp_{$i}",ConsumerTest::class);
        }

        //定时任务
//        CronTab::getInstance()
//            ->addRule("test_singwa_crontab1", "*/1 * * * *",
//                function()  {
//                    var_dump("第一个定时任务");
//            })
//            ->addRule("test_singwa_crontab2", "*/1 * * * *",
//                function()  {
//                    var_dump("第二个定时任务");
//            });
        //定时任务 静态化API数据：最小一分钟定时任务
        //方案一：定时任务
//        $cacheVideoObj = new videoCache();
//        CronTab::getInstance()
//            ->addRule("test_singwa_crontab", "*/1 * * * *", function() use($cacheVideoObj) {
//                $cacheVideoObj->setIndexVideo();
//        });


        //方案二：swoole 定时器，支持毫秒级
//        Timer::loop(1000*2, function () {
//            var_dump(111);
//        });
        //简单使用
        $cacheVideoObj = new videoCache();
        Timer::loop(1000*2, function () use($cacheVideoObj){
            $cacheVideoObj->setIndexVideo();
        });
        //复杂使用
//        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId)use($cacheVideoObj){
//            if ($workerId == 0) {
//                Timer::loop(1000*2, function () use($cacheVideoObj){
//                    $cacheVideoObj->setIndexVideo();
//                });
//            }
//
//        });



    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}