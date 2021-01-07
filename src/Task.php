<?php


namespace Lit\Atd;


use Lit\Atd\Constant\RedisKey;
use Lit\Atd\Mapper\Message;

class Task
{
    private static $redisHandler;

    /**
     * 初始化 Atd
     * @date 2021/1/6
     * @param $redisHandler
     * @return string
     */
    public static function init($redisHandler) {
        self::$redisHandler = $redisHandler;
        return new self();
    }

    /**
     * Atd 中间程序, 用户导入定时发布数据
     * @date 2021/1/7
     * @return void
     */
    public function run() {
        while (true) {
            $zRange = self::$redisHandler->zRangeByScore(RedisKey::DATA_STORE_KEY, 0, time(), ["limit" => [0, 100]]);
            if (empty($zRange)) {
                sleep(1);
                continue;
            }
            self::$redisHandler->multi();
            foreach ($zRange as $jsonMsg) {
                $message = json_decode($jsonMsg, true);
                self::$redisHandler->lpush(RedisKey::getListKey($message['topic']), $jsonMsg);
                self::$redisHandler->zrem(RedisKey::DATA_STORE_KEY, $jsonMsg);
            }
            self::$redisHandler->exec();
        }
    }
}