## 延时队列 atd

### 变量解释

````php
$redisHandler;  //redis链接句柄, Task与listen 使用时需长连接
$topic; //话题
$topics; //话题数组
$message; //消息体
````

### 启动服务

````php
//连接redis
$redisHandler = new \Redis();
$redisHandler->pconnect("192.168.1.163"); //需使用长连接

//延时队列服务
\Lit\Atd\Task::init($redisHandler)->run();
````

### 发布一条延时消息

````php
use \Lit\Atd\Mapper\Message;

//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

//$message 消息体, 如果消息体重复会重置消息时间, 使用 $message->uniq_id 避免此问题
$message = new Message();
$message->topic = "topic1";
$message->body = "消息体!" ;;//. uniqid();
$message->uniq_id = ""; //唯一ID, 如果需要延期某条消息或者删除某条消息,需记录此ID
//$time 要执行的10位时间戳
$time = time() + 1;

var_dump(\Lit\Atd\Client::init($redisHandler)->at($time, $message));
````

### 消费延时队列

````php
//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

$topic = "topic1";
$msg = \Lit\Atd\Client::init($redisHandler)->pop($topic);
if (null !== $msg) {
    var_dump($msg->uniq_id, $msg->body, $msg->topic);
}
````

### 监听延时队列

监听延时队列, 并使用回调函数执行相应操作

````php
//连接redis
$redisHandler = new \Redis();
$redisHandler->pconnect("192.168.1.163"); //需使用长连接

//$topics 要监听的topic 必须为数组, 可同时监听多个 topic
$topics[] = "topic1";
$topics[] = "topic2";
\Lit\Atd\Client::init($redisHandler)->listen($topics, function (Message $message) {
    var_dump($message->topic, $message->body, $message->uniq_id);
});
````

### 删除未发布的消息

````php
use \Lit\Atd\Mapper\Message;

//连接redis
$redisHandler = new \Redis();
$redisHandler->connect("192.168.1.163");

//$message 消息体
$message = new Message();
$message->topic = "topic1";
$message->body = "消息体!";// . uniqid();
$message->uniq_id = ""; //唯一ID, 需和写入时的值相同

var_dump(\Lit\Atd\Client::init($redisHandler)->remove($message));
````