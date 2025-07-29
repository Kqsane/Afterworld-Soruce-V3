<?php
namespace Afterworld;

use Redis;

class RedisConnection {
    public static function connect() : Redis
    {
        global $config;
        $redis = new Redis();
        $redis->connect($config["redis"]["host"]);
        return $redis;
    }
}