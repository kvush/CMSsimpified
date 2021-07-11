<?php

namespace App\Domain;

use InvalidArgumentException;

/**
 * Class ChangeArticle
 * @package App\Domain
 */
final class ChangeArticle
{
    private DateTime $updatedAt;
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
        $this->updatedAt = DateTime::fromString($updatedAt);
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
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
