<?php

class RedisUtil
{
    private static $instance;

    private $obj;

    const HOSTNAME = "";
    const PASSWORD = '';
    const PORT = 6379;
    const DB_NUMBER = 44;

    private function __construct()
    {
        $this->obj = new \Redis();
        $this->obj->pconnect(self::HOSTNAME,self::PORT);
        $this->obj->auth(self::PASSWORD);
        $this->obj->select(self::DB_NUMBER);
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 获取redis连接
     * 可以用该方法调用原生方法
     * */
    public function getRedisConn() {
        return $this->obj;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->obj->close();
        $this->instance = null;
    }

    /* hash表操作函数 */

    /**
     * 得到hash表中的一个字段的值
     * @param string $key 缓存KEY
     * @param string $field 字段
     * @return string|false
     * */
    public function hGet($key, $field) {
        return $this->obj->hGet($key, $field);
    }

    /**
     * 为hash表设定一个字段的值
     * @param string $key 缓存KEY
     * @param string $field 字段
     * @param string $value 值
     * @return bool
     * */
    public function hSet($key, $field, $value) {
        return $this->obj->hSet($key, $field, $value);
    }

    /**
     * 判断hash表中，指定field是不是存在
     * @param string $key 缓存KEY
     * @param string $field 字段
     * @return bool
     * */
    public function hExists($key, $field) {
        return $this->obj->hExists($key, $field);
    }

    /**
     * 删除hash表中指定字段，支持批量删除
     * @param string $key 缓存KEY
     * @param string|array $field 字段
     * @return int
     * */
    public function hDel($key, $field) {
        if(is_array($field)) {
            $field = implode(',',$field);
        }
        return $this->obj->hDel($key, $field);
    }

    /**
     * 返回hash表元素个数
     * @param string $key 缓存KEY
     * @return int|bool
     * */
    public function hLen($key) {
        return $this->obj->hLen($key);
    }

    /**
     * 为hash表设定一个字段的值，如果字段存在，返回false
     * @param string $key 缓存KEY
     * @param string $field 字段
     * @param string $value 值
     * @return bool
     * */
    public function hSetNx($key, $field, $value) {
        return $this->obj->hSetNx($key, $field, $value);
    }

    /**
     * 为hash表多个字段设定值
     * @param string $key
     * @param array $value
     * @return array|bool
     * */
    public function hMSet($key, array $value) {
        if(empty($value)) return false;
        return $this->obj->hMSet($key, $value);
    }

    /**
     * 返回hash表中多个字段的值
     * @param string $key 缓存KEY
     * @param string|array $field 字段
     * @return array|bool
     * */
    public function hMGet($key, array $field) {
        if(empty($field)) return false;
        return $this->obj->hMGet($key, $field);
    }

    /**
     * 为hash表指定字段累加累减
     * @param string $key 缓存KEY
     * @param string $field 字段
     * @param int $value
     * */
    public function hIncrBy($key, $field, $value) {
        $value = intval($value);
        return $this->obj->hIncrBy($key, $field, $value);
    }

    /**
     * 返回所有hash表的字段值
     * @param string $key 缓存KEY
     * @return array|bool
     * */
    public function hKeys($key) {
        return $this->obj->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * @param string $key
     * @return array|bool
     * */
    public function hVals($key) {
        return $this->obj->hVals($key);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * @param string $key 缓存KEY
     * @return array|bool
     * */
    public function hGetAll($key) {
        return $this->obj->hGetAll($key);
    }

    /* 队列操作命令 */

    /**
     * 在列队尾部插入一个元素
     * @param string $key 缓存KEY
     * @param string $value
     * 返回队列长度
     * */
    public function rPush($key, $value) {
        return $this->obj->rPush($key, $value);
    }

    /**
     * 在队列尾部插入一个元素，如果key不存在，什么也不做
     * @param string $key 缓存KEY
     * @param string $value
     * 返回队列长度
     * */
    public function rPushx($key, $value) {
        return $this->obj->rPushx($key, $value);
    }

    /**
     * 在队列头部插入一个元素
     * @param string $key 缓存KEY
     * @param string $value
     * 返回队列长度
     * */
    public function lPush($key, $value) {
        return $this->obj->lPush($key, $value);
    }

    /**
     * 在队列头插入一个元素，如果key不存在，什么也不做
     * @param string $key 缓存KEY
     * @param string $value
     * 返回队列长度
     * */
    public function lPushx($key, $value) {
        return $this->obj->lPushx($key, $value);
    }

    /**
     * 返回队列长度
     * $param string $key 缓存KEY
     * @return int
     * */
    public function lLen($key) {
        return $this->obj->lLen($key);
    }

    /**
     * 返回队列指定区间的元素
     * @param string $key 缓存KEY
     * @param int $start
     * @param int $end
     * @return array
     * */
    public function lRange($key, $start, $end) {
        return $this->obj->lRange($key, $start, $end);
    }

    /**
     * 返回队列中指定索引的元素
     * @param string $key
     * @param int $index
     * @return string
     * */
    public function lIndex($key, $index) {
        return $this->obj->lIndex($key, $index);
    }

    /**
     * 设定队列中指定index的值
     * @param string $key 缓存KEY
     * @param int $index
     * @param string $value
     * @return
     * */
    public function lSet($key, $index, $value) {
        return $this->obj->lSet($key, $index, $value);
    }

    /**
     * 删除并返回队列中的头元素
     * @param string $key
     * */
    public function lPop($key) {
        return $this->obj->lPop($key);
    }

    /**
     * 删除并返回队列中的尾元素
     * @param string $key
     * */
    public function rPop($key) {
        return $this->obj->rPop($key);
    }

    /**
     * 删除值为value的count个元素
     * @param string $key
     * @param int $count
     * @param string $value
     * */
    public function lRem($key, $count, $value) {
        return $this->obj->lRem($key, $value, $count);
    }

    /* 字符串操作命令 */

    /**
     * 设置一个key
     * @param string $key 缓存KEY
     * @param string $value
     * @return bool
     * */
    public function set($key, $value) {
        return $this->obj->set($key, $value);
    }

    /**
     * 得到一个key
     * @param string $key 缓存KEY
     * @return bool
     * */
    public function get($key) {
        return $this->obj->get($key);
    }

    /**
     * 设置一个有过期时间的key
     * @param string $key 缓存KEY
     * @param int $expire
     * @param string $value
     * @return bool
     * */
    public function setex($key, $expire, $value){
        return $this->obj->setex($key, $expire,$value);
    }

    /**
     * 设置一个key，如果key存在，不做任何操作
     * @param string $key 缓存KEY
     * @param string $value
     * @return bool
     * */
    public function setnx($key, $value) {
        return $this->obj->setnx($key, $value);
    }

    /**
     * 批量设置key
     * @param array $arr
     * @return bool
     * */
    public function mset(array $arr) {
        if(empty($arr)) return false;
        return $this->obj->mset($arr);
    }

    /* redis 管理操作命令 */

    /**
     * 选择数据库
     * @param int $dbId
     * @return bool
     * */
    public function select($dbId) {
        return $this->obj->select($dbId);
    }

    /**
     * 返回当前库状态
     * @return array
     * */
    public function info() {
        return $this->obj->info();
    }

    /**
     * 同步保存数据到磁盘
     * */
    public function save() {
        return $this->obj->save();
    }

    /**
     * 异步保存数据到磁盘
     * */
    public function bgSave() {
        return $this->obj->bgsave();
    }

    /**
     * 返回最后保存到磁盘的时间
     * */
    public function lastSave() {
        return $this->obj->lastSave();
    }

    /**
     * 返回key，支持*多个，？一个
     * @param string $key
     * @return array
     * */
    public function keys($key) {
        return $this->obj->keys($key);
    }

    /**
     * 删除指定key值
     * @param string key
     * */
    public function del($key) {
        return $this->obj->del($key);
    }

    /**
     * 判断一个key值是否存在
     * @param string $key
     * */
    public function exists($key) {
        return $this->obj->exists($key);
    }

    /**
     * 为一个key设定过期时间，单位为秒
     * @param string $key
     * @param int $expire
     * */
    public function expire($key, $expire) {
        return $this->obj->expire($key, $expire);
    }

    /**
     * 返回一个key还有多久过期，单位秒
     * @param string $key
     * @return int
     * */
    public function ttl($key) {
        return $this->obj->ttl($key);
    }

    /**
     * 设定一个key在什么时候过期，time为一个时间戳
     * @param string $key
     * @print timestamp $time
     * */
    public function expireAt($key, $time) {
        return $this->obj->expireAt($key, $time);
    }

    /**
     * 返回当前数据库key数量
     * */
    public function dbSize() {
        return $this->obj->dbSize();
    }

    /** 无序集合 */

    /**
     * 返回集合中的所有元素
     * @param string $key
     * */
    public function sMembers($key) {
        return $this->obj->sMembers($key);
    }

    /**
     * 求2个集合的差集
     * @param string $key1
     * @param string $key2
     * */
    public function sDiff($key1, $key2) {
        return $this->obj->sDiff($key1, $key2);
    }

    /**
     * 添加集合
     * */
    public function sAdd($key, $value) {
        return $this->obj->sAdd($key,$value);
    }

    /**
     * 返回无序集合的元素个数
     * @param string $key
     * @return int
     * */
    public function sCard($key) {
        return $this->obj->sCard($key);
    }

    /**
     * 从集合中删除一个元素
     * @param string $key
     * @param string $value
     * @return int
     * */
    public function sRem($key, $value) {
        if(is_array($value)) $value = implode(',',$value);
        return $this->obj->sRem($key, $value);
    }

    /* 有序集合操作 */

    /**
     * 给当前集合添加一个元素
     * 如果value已经存在，会更新order的值
     * @param string $key 缓存KEY
     * @param integer $order 序号
     * @param string $value 值
     * @return bool
     * */
    public function zAdd($key, $order, $value) {
        return $this->obj->zAdd($key, $order, $value);
    }

    /**
     * 给$value成员的order值，增加$num,可以为负数
     * @param string $key
     * @param string $num 序号
     * @param string $value 值
     * @return 返回新的order
     */
    public function zinCry($key,$num,$value) {
        return $this->redis->zinCry($key,$num,$value);
    }

    /**
     * 删除值为value的元素
     * @param string $key
     * @param stirng $value
     * @return bool
     */
    public function zRem($key,$value) {
        return $this->redis->zRem($key,$value);
    }

    /**
     * 返回order值在start end之间的数量
     * @param unknown $key
     * @param unknown $start
     * @param unknown $end
     */
    public function zCount($key,$start,$end) {
        return $this->redis->zCount($key,$start,$end);
    }

    /**
     * 返回值为value的order值
     * @param unknown $key
     * @param unknown $value
     */
    public function zScore($key,$value) {
        return $this->redis->zScore($key,$value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param unknown $key
     * @param unknown $value
     */
    public function zRank($key,$value) {
        return $this->redis->zRank($key,$value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param unknown $key
     * @param unknown $value
     */
    public function zRevRank($key,$value) {
        return $this->redis->zRevRank($key,$value);
    }

    /**
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param unknown $key
     * @param unknown $start
     * @param unknown $end
     * @return 删除成员的数量。
     */
    public function zRemRangeByScore($key,$start,$end) {
        return $this->redis->zRemRangeByScore($key,$start,$end);
    }

    /**
     * 返回集合元素个数。
     * @param unknown $key
     */
    public function zCard($key) {
        return $this->redis->zCard($key);
    }
}