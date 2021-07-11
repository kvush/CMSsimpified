<?php

namespace App\Domain\Dto;

use App\Domain\MyDateTime;
use App\Domain\Model\ArticleId;

/**
 * Class ArticleDto
 * @package App\Domain
 */
final class ArticleDto
{
    public ArticleId $id;
    public string $title;
    public string $body;
    public MyDateTime $createdAt;
    public ?MyDateTime $updatedAt = null;
    public ?MyDateTime $deletedAt = null;

    /**
     * ArticleDto constructor.
     * @param array $rowData
     */
    public function __construct(array $rowData)
    {
        $this->id = ArticleId::fromString($rowData['id']);
        $this->title = $rowData['title'];
        $this->body = $rowData['body'];
        $this->createdAt = MyDateTime::fromString($rowData['createdAt']);
        if (isset($rowData['updatedAt'])) {
            $this->updatedAt = MyDateTime::fromString($rowData['updatedAt']);
        }
        $this->deletedAt = $rowData['deletedAt'] ?? null;
    }
}
