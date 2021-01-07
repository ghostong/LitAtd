<?php
require dirname(__DIR__) . "/vendor/autoload.php";


//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

//消费延时队列
$topic = "topic1";
$msg = \Lit\Atd\Client::init($redisHandler)->pop($topic);
if (null !== $msg) {
    var_dump($msg->uniq_id, $msg->body, $msg->topic);
}