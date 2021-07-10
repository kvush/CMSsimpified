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
     * @param string $updatedAt
     * @param string|null $title
     * @param string|null $body
     */
    public function __construct(string $updatedAt, ?string $title = null, ?string $body = null)
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
