<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Limiter;

interface LimiterInterface
{
    /**
     * 判定是否被限制
     */
    public function isLimited(): bool;
}
