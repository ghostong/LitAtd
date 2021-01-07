<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use \Lit\Atd\Mapper\Message;

//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

//删除一条未发布的延时消息
//$message 消息体
$message = new Message();
$message->topic = "topic1";
$message->body = "消息体!";// . uniqid();
$message->uniq_id = ""; //唯一ID, 需和写入时的值相同

var_dump(\Lit\Atd\Client::init($redisHandler)->remove($message));


