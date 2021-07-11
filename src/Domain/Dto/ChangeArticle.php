<?php

namespace App\Domain\Dto;

use App\Domain\MyDateTime;
use InvalidArgumentException;

/**
 * Class ChangeArticle
 * @package App\Domain
 */
final class ChangeArticle
{
    private MyDateTime $updatedAt;
    private ?string $title;
    private ?string $body;

    /**
     * ChangeArticle constructor.
     * @param string|null $title
     * @param string|null $body
     * @param string $updatedAt
     */
    public function __construct(?string $title, ?string $body, string $updatedAt)
    {
        if ($title === null && $body === null) {
            throw new InvalidArgumentException("at least title or body must be provided");
        }
        $this->updatedAt = MyDateTime::fromString($updatedAt);
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * @return MyDateTime
     */
    public function getUpdatedAt(): MyDateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
}
