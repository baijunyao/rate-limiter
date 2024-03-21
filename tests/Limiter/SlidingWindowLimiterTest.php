<?php

declare(strict_types=1);

namespace Baijunyao\RateLimiter\Tests\Limiter;

use Baijunyao\RateLimiter\Limiter\SlidingWindowLimiter;
use Baijunyao\RateLimiter\Storage\FileStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SlidingWindowLimiter::class)]
class SlidingWindowLimiterTest extends TestCase
{
    /**
     * 测试窗口数能整除的情况
     */
    public function testIsLimitedDivisible(): void
    {
        $slidingWindowLimiter = new SlidingWindowLimiter(new FileStorage(__DIR__ . '/tmp'), 4, 2, 'id-3', 2);

        // 第一个周期
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());

        // 新的周期
        sleep(2);

        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
    }

    /**
     * 测试窗口数不能整除有余数的情况
     */
    public function testIsLimitedIndivisible(): void
    {
        // 4 秒内最多访问 3 次
        $slidingWindowLimiter = new SlidingWindowLimiter(new FileStorage(__DIR__ . '/tmp'), 4, 3, 'id-4', 2);

        static::assertFalse($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());

        // 5 秒内最多访问 3 次
        $slidingWindowLimiter = new SlidingWindowLimiter(new FileStorage(__DIR__ . '/tmp'), 5, 3, 'id-5', 2);

        static::assertFalse($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());

        // 5 秒内最多访问 4 次
        $slidingWindowLimiter = new SlidingWindowLimiter(new FileStorage(__DIR__ . '/tmp'), 5, 4, 'id-6', 2);

        static::assertFalse($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertTrue($slidingWindowLimiter->isLimited());
        sleep(1);
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertFalse($slidingWindowLimiter->isLimited());
        static::assertTrue($slidingWindowLimiter->isLimited());
    }
}
