<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Limiter;

use Baijunyao\RateLimiter\Exception\InvalidIntervalCountException;

/**
 * 滑动窗口
 */
class SlidingWindowLimiter extends Limiter
{
    public function isLimited(): bool
    {
        $time  = time();
        $count = $this->storage->getCount($this->key, $time);

        if ($count === null) {
            if ($this->windowCount === null) {
                throw new InvalidIntervalCountException('The interval count must be greater than 0.');
            }

            // 每个窗口的时间
            $windowTime          = intdiv($this->seconds, $this->windowCount);
            $windowTimeRemainder = $this->seconds % $this->windowCount;

            // 每个窗口的次数
            $limitNumber          = intdiv($this->limits, $this->windowCount);
            $limitNumberRemainder = $this->limits % $this->windowCount;

            $startAt = $time;

            for ($i = 1; $i <= $windowTime; $i++) {
                $endAt = $startAt + $windowTime + ($i <= $windowTimeRemainder ? 1 : 0);

                $this->storage->setLimitAndCount(
                    $this->key,
                    $startAt,
                    $endAt,
                    $limitNumber + ($i <= $limitNumberRemainder ? 1 : 0),
                    $i === 1 ? 1 : 0
                );

                $startAt = $endAt;
            }

            return false;
        }

        if ($count < $this->storage->getLimit($this->key, $time)) {
            $this->storage->incrementCount($this->key, $time);

            return false;
        }

        return true;
    }
}
