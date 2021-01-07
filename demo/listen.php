<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use \Lit\Atd\Mapper\Message;

//连接redis
$redisHandler = new \Redis();
$redisHandler->pconnect("192.168.1.163"); //需使用长连接

//监听延时队列, 并使用回调函数执行相应操作
//$topics 要监听的topic 必须为数组, 可同时监听多个 topic
$topics[] = "topic1";
$topics[] = "topic2";
\Lit\Atd\Client::init($redisHandler)->listen($topics, function (Message $message) {
    var_dump($message->topic, $message->body, $message->uniq_id);
});