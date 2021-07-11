<?php

namespace App\Domain\Dto;

use App\Domain\MyDateTime;

/**
 * Class ArchiveArticle
 * @package App\Domain
 */
final class ArchiveArticle
{
    private MyDateTime $archivedAt;

    /**
     * ArchiveArticle constructor.
     * @param string $archivedAt
     */
    public function __construct(string $archivedAt)
    {
        $this->archivedAt = MyDateTime::fromString($archivedAt);
    }

    /**
     * @return MyDateTime
     */
    public function getArchivedAt(): MyDateTime
    {
        return $this->archivedAt;
    }
}
