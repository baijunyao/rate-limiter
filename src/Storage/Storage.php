<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Storage;

abstract class Storage implements StorageInterface
{
    /**
     * 获取 key 对应的内容
     *
     * @throws \JsonException
     */
    abstract public function get(string $key): ?array;

    /**
     * 存储 key 对应的内容
     */
    abstract public function set(string $key, array $content): void;

    /**
     * 格式化 key 处理特殊符号问题
     */
    public function formatKey(string $key): string
    {
        return md5($key);
    }

    public function getCount(string $key, int $time): ?int
    {
        $windows        = $this->get($key) ?? [];
        $contentChanged = false;
        $count          = null;

        foreach ($windows as $index => $window) {
            if ($time >= $window['end_at']) {
                unset($windows[$index]);
                $contentChanged = true;
                continue;
            }

            if ($time >= $window['start_at']) {
                $count = $window['count'];
                break;
            }
        }

        if ($contentChanged === true) {
            $this->set($key, $windows);
        }

        return $count;
    }

    public function incrementCount(string $key, int $time): void
    {
        $windows = $this->get($key) ?? [];

        foreach ($windows as $index => $window) {
            if ($time >= $window['start_at'] && $time < $window['end_at']) {
                $windows[$index]['count']++;
                break;
            }
        }

        $this->set($key, $windows);
    }

    public function getLimit(string $key, int $time): ?int
    {
        $windows = $this->get($key) ?? [];

        foreach ($windows as $window) {
            if ($time >= $window['start_at'] && $time < $window['end_at']) {
                return $window['limit'];
            }
        }

        return null;
    }

    public function setLimitAndCount(string $key, int $startAt, int $endAt, int $limit, int $count): void
    {
        $fileContent = $this->get($key) ?? [];

        $fileContent[$endAt] = [
            'start_at' => $startAt,
            'end_at'   => $endAt,
            'limit'    => $limit,
            'count'    => $count,
        ];

        $this->set($key, $fileContent);
    }
}
