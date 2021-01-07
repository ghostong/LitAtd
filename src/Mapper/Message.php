<?php

namespace Lit\Atd\Mapper;

class Message
{

    /**
     * 构造函数,可以通过参数初始化 Message
     * @date 2021/1/7
     * @param array $message
     * @author litong
     */
    public function __construct($message = []) {
        foreach ($message as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }

    public $topic = "";

    public $body = "";

    public $uniq_id = "";

    public function toArray() {
        return (array)$this;
    }
}