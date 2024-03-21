<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Limiter;

/**
 * 固定窗口
 */
class FixedWindowLimiter extends Limiter
{
    public function isLimited(): bool
    {
        $time  = time();
        $count = $this->storage->getCount($this->key, $time);

        if ($count === null) {
            $this->storage->setLimitAndCount($this->key, $time, $time + $this->seconds, $this->limits, 1);

            return false;
        }

        if ($count < $this->limits) {
            $this->storage->incrementCount($this->key, $time);

            return false;
        }

        return true;
    }
}
