<?php

namespace Lit\Atd\Constant;

class RedisKey
{

    const DATA_STORE_KEY = "atd:data:store";

    const LIST_KEY = "atd:list:topic";

    public static function getListKey($topic) {
        return self::LIST_KEY . ":" . $topic;
    }

}