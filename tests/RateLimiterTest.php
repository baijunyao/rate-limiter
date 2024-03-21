<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Tests;

use Baijunyao\RateLimiter\RateLimiter;
use Baijunyao\RateLimiter\Storage\FileStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RateLimiter::class)]
class RateLimiterTest extends TestCase
{
    public function testCreate(): void
    {
        $rateLimiter = new RateLimiter(new FileStorage(__DIR__ . '/tmp'), 4, 3, 'id-1', 2);
        $limiter     = $rateLimiter->createLimiter('sliding_window');

        static::assertFalse($limiter->isLimited());
        sleep(1);
        static::assertFalse($limiter->isLimited());
        static::assertTrue($limiter->isLimited());
        sleep(1);
        static::assertFalse($limiter->isLimited());
        static::assertTrue($limiter->isLimited());
    }
}
