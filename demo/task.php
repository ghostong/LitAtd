<?php
require dirname(__DIR__) . "/vendor/autoload.php";

//连接redis
$redisHandler = new \Redis();
$redisHandler->pconnect("192.168.1.163"); //需使用长连接

//延时队列服务
\Lit\Atd\Task::init($redisHandler)->run();
