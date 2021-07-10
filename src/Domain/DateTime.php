<?php

namespace App\Domain;

use DateTimeImmutable;

/**
 * Class Date
 * @package App\Domain
 */
final class DateTime
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    private string $date;

    /**
     * Date constructor.
     * @param string $date
     */
    private function __construct(string $date)
    {
        if (!DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $date)) {
            throw new \InvalidArgumentException('Invalid date provided');
        }
        $this->date = $date;
    }

    public static function fromString(string $date): self
    {
        return new self($date);
    }

    public function asString(): string
    {
        return $this->date;
    }
}
