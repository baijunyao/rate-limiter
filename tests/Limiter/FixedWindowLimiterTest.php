<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Tests\Limiter;

use Baijunyao\RateLimiter\Limiter\FixedWindowLimiter;
use Baijunyao\RateLimiter\Storage\FileStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FixedWindowLimiter::class)]
class FixedWindowLimiterTest extends TestCase
{
    public function testIsLimited(): void
    {
        $seconds = 1;
        $limits  = 10;

        $globalFixedWindowLimiter = new FixedWindowLimiter(new FileStorage(__DIR__ . '/tmp'), $seconds, $limits);

        for ($i = 0; $i < $limits; $i++) {
            static::assertFalse($globalFixedWindowLimiter->isLimited());
        }

        static::assertTrue($globalFixedWindowLimiter->isLimited());

        $fixedWindowLimiter = new FixedWindowLimiter(new FileStorage(__DIR__ . '/tmp'), $seconds, $limits, 'id-2');

        for ($i = 0; $i < $limits; $i++) {
            static::assertFalse($fixedWindowLimiter->isLimited());
        }

        static::assertTrue($fixedWindowLimiter->isLimited());

        sleep($seconds);
    }
}
