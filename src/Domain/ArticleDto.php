<?php

namespace App\Domain;

/**
 * Class ArticleDto
 * @package App\Domain
 */
final class ArticleDto
{
    public ArticleId $id;
    public string $title;
    public string $body;
    public DateTime $createdAt;
    public ?DateTime $updatedAt = null;
    public ?DateTime $deletedAt = null;

    /**
     * ArticleDto constructor.
     * @param array $rowData
     */
    public function __construct(array $rowData)
    {
        $this->id = ArticleId::fromString($rowData['id']);
        $this->title = $rowData['title'];
        $this->body = $rowData['body'];
        $this->createdAt = DateTime::fromString($rowData['createdAt']);
        if (isset($rowData['updatedAt'])) {
            $this->updatedAt = DateTime::fromString($rowData['updatedAt']);
        }
        $this->deletedAt = $rowData['deletedAt'] ?? null;
    }
}
