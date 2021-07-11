<?php

namespace App\Infrastructure;

use App\Application\Clock;

/**
 * Class SystemClock
 * @package App\Infrastructure
 */
class SystemClock implements Clock
{
    /**
     * @return \DateTimeImmutable
     */
    public function currentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
