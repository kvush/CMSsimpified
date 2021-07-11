<?php

namespace App\Application;

/**
 * Interface Calendar
 * @package App\Domain
 */
interface Clock
{
    public function currentTime(): \DateTimeImmutable;
}
