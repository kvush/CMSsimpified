<?php

namespace App\Domain;

/**
 * Class ArchiveArticle
 * @package App\Domain
 */
final class ArchiveArticle
{
    private DateTime $archivedAt;

    /**
     * ArchiveArticle constructor.
     * @param string $archivedAt
     */
    public function __construct(string $archivedAt)
    {
        $this->archivedAt = DateTime::fromString($archivedAt);
    }

    /**
     * @return DateTime
     */
    public function getArchivedAt(): DateTime
    {
        return $this->archivedAt;
    }
}
