<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Storage;

/**
 * Redis 连接单例
 */
class RedisConnector
{
    private static ?\Redis $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance($host = '127.0.0.1', $port = 6379, $password = null, $database = 0)
    {
        if (self::$instance === null) {
            self::$instance = new \Redis();
        }

        self::$instance->connect($host, $port);

        if ($password !== null) {
            self::$instance->auth($password);
        }

        if ($database !== 0) {
            self::$instance->select($database);
        }

        return self::$instance;
    }
}
