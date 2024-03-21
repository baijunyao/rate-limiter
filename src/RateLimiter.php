<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter;

use Baijunyao\RateLimiter\Exception\InvalidLimitersException;
use Baijunyao\RateLimiter\Limiter\FixedWindowLimiter;
use Baijunyao\RateLimiter\Limiter\Limiter;
use Baijunyao\RateLimiter\Limiter\SlidingWindowLimiter;
use Baijunyao\RateLimiter\Storage\StorageInterface;

class RateLimiter
{
    private array $limiters = [
        'fixed_window'   => FixedWindowLimiter::class,
        'sliding_window' => SlidingWindowLimiter::class,
    ];

    public function __construct(
        protected StorageInterface $storage,
        protected int $seconds,
        protected int $limits,
        protected ?string $key = null,
        protected ?int $windowCount = null
    ) {
    }

    public function addLimiter(string $limiterType, $limiterClassFQCN): void
    {
        $this->limiters[$limiterType] = $limiterClassFQCN;
    }

    public function createLimiter(string $limiterType): Limiter
    {
        /**
         * @var class-string<Limiter> $limiterFQCN
         */
        $limiterFQCN = $this->limiters[$limiterType] ?? null;

        if ($limiterFQCN === null) {
            throw new InvalidLimitersException(sprintf('Limiter policy "%s" does not exists.', $limiterType));
        }

        return new $limiterFQCN($this->storage, $this->seconds, $this->limits, $this->key, $this->windowCount);
    }
}
