<?php

namespace App\Domain\Model;

use Webmozart\Assert\Assert;

/**
 * Class ArticleId
 * @package App\Domain
 */
final class ArticleId
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::uuid($id);
        $this->id = $id;
    }

    public static function fromString(string $uuid): ArticleId
    {
        return new self($uuid);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
