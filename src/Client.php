<?php


namespace Lit\Atd;


use Lit\Atd\Constant\RedisKey;
use Lit\Atd\Mapper\Message;

class Client
{
    private static $redisHandler;

    /**
     * 初始化Atd
     * @date 2021/1/6
     * @param $redisHandler
     * @return string
     */
    public static function init($redisHandler) {
        self::$redisHandler = $redisHandler;
        return new self();
    }

    /**
     * 写入延时队列
     * @date 2021/1/7
     * @param int $timeStamp 10位时间戳
     * @param Message $message
     * @return bool
     */
    public function at($timeStamp, Message $message) {
        $message = json_encode($message->toArray());
        return self::$redisHandler->zAdd(RedisKey::DATA_STORE_KEY, $timeStamp, $message) !== false;
    }

    /**
     * 监听多个 topic
     * @date 2021/1/7
     * @param $topics
     * @param $callback
     * @return void
     */
    public function listen($topics, $callback) {
        $topics = array_map(function ($topic) {
            return RedisKey::getListKey($topic);
        }, $topics);
        while (true) {
            $jsonMsg = self::$redisHandler->brpop($topics, 5);
            if (!empty($jsonMsg)) {
                call_user_func($callback, new Message(json_decode($jsonMsg[1], true)));
            }
        }
    }

    /**
     * 消费 topic
     * @date 2021/1/7
     * @param string $topic
     * @return Message|null
     */
    public function pop($topic) {
        $jsonMsg = self::$redisHandler->rpop(RedisKey::getListKey($topic));
        if (empty($jsonMsg)) {
            return null;
        } else {
            return new Message(json_decode($jsonMsg, true));
        }
    }

    /**
     * 删除未到时间的消息
     * @date 2021/1/7
     * @param Message $message
     * @return bool
     */
    public function remove(Message $message) {
        $message = json_encode($message->toArray());
        return self::$redisHandler->zRem(RedisKey::DATA_STORE_KEY, $message) > 0;
    }
}