<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use \Lit\Atd\Mapper\Message;

//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

//发布一条延时消息
//$message 消息体, 如果消息体重复会重置消息时间, 使用 $message->uniq_id 避免此问题
$message = new Message();
$message->topic = "topic1";
$message->body = "消息体!" ;;//. uniqid();
$message->uniq_id = ""; //唯一ID, 如果需要延期某条消息或者删除某条消息,需记录此ID
//$time 要执行的10位时间戳
$time = time() + 1;

var_dump(\Lit\Atd\Client::init($redisHandler)->at($time, $message));


