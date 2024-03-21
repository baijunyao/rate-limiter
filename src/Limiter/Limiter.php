<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Limiter;

use Baijunyao\RateLimiter\Exception\InvalidLimitsException;
use Baijunyao\RateLimiter\Exception\InvalidSecondsException;
use Baijunyao\RateLimiter\Storage\StorageInterface;

abstract class Limiter implements LimiterInterface
{
    /**
     * @param \Baijunyao\RateLimiter\Storage\StorageInterface $storage     存储对象
     * @param int                                             $seconds     限制的秒数
     * @param int                                             $limits      限制的次数
     * @param string|null                                     $key         限制的对象的唯一标识 key，为 null 时用于限制全局
     * @param int|null                                        $windowCount 分割的窗口数量，用于滑动窗口
     */
    public function __construct(
        protected StorageInterface $storage,
        protected int $seconds,
        protected int $limits,
        protected ?string $key = null,
        protected ?int $windowCount = null
    ) {
        if ($this->seconds < 1) {
            throw new InvalidSecondsException(sprintf('The seconds must be greater than 1, %d given.', $this->seconds));
        }

        if ($this->limits < 0) {
            throw new InvalidLimitsException(sprintf('The limits must be greater than 0, %d given.', $this->limits));
        }

        if ($this->key === null) {
            $this->key = 'global';
        } else {
            $this->key .= 'custom-';
        }
    }
}
