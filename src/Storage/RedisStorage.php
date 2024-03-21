<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Storage;

/**
 * Redis 存储
 */
class RedisStorage extends Storage
{
    protected \Redis $redis;

    public function __construct($host = '127.0.0.1', $port = 6379, $password = null, $database = 0)
    {
        $this->redis = RedisConnector::getInstance($host, $port, $password, $database);
    }

    public function get(string $key): ?array
    {
        $content = $this->redis->get($key);

        if ($content === false || $content === null) {
            return null;
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function set(string $key, array $content): void
    {
        $this->redis->set($key, json_encode($content, JSON_THROW_ON_ERROR));
    }
}
