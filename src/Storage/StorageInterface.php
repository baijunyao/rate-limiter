<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Storage;

/**
 * 存储的结构如下
 * [
 *   'key' => [
 *     '结束时间' => [
 *       'start_at' => '开始时间',
 *       'end_at' => '结束时间',
 *       'limit' => '限制的次数',
 *       'count' => '已请求的次数',
 *     ],
 *   ]
 * ]
 * 示例
 * [
 *   'id-1' => [
 *     '1710905568' => [
 *       'start_at' => '1710905560',
 *       'end_at' => '1710905568',
 *       'limit' => 2,
 *       'count' => 2,
 *     ],
 *   ]
 * ]
 */
interface StorageInterface
{
    /**
     * 根据 key 和时间获取已经存储的次数
     *
     * @return int|null 当指定的时间不存在时返回 null
     */
    public function getCount(string $key, int $time): ?int;

    /**
     * 递增指定时间已请求的次数
     */
    public function incrementCount(string $key, int $time): void;

    /**
     * 设置指定时间限制次数和已请求的次数
     */
    public function setLimitAndCount(string $key, int $startAt, int $endAt, int $limit, int $count): void;

    /**
     * 获取指定时间限制的次数
     */
    public function getLimit(string $key, int $time): ?int;
}
