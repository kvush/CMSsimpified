<?php

namespace App\Domain;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class Article
 * @package App\Domain
 */
final class Article
{
    private ArticleId $id;
    private string $title;
    private string $body;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private ?DateTime $archivedAt = null;

    private function __construct()
    {
    }

    public static function createFromDto(ArticleDto $articleDto): self
    {
        if (empty($articleDto->title)) {
            throw new InvalidArgumentException('title must be provided');
        }
        if (empty($articleDto->body)) {
            throw new InvalidArgumentException('body must be provided');
        }

        $instance = new self();
        $instance->id = $articleDto->id;
        $instance->title = $articleDto->title;
        $instance->body = $articleDto->body;
        $instance->createdAt = $articleDto->createdAt;
        if ($articleDto->updatedAt === null) {
            $instance->updatedAt = $articleDto->createdAt;
        } else {
            $instance->updatedAt = $articleDto->updatedAt;
        }

        return $instance;
    }

    public function apply(ChangeArticle $changeArticle)
    {
        if ($this->isArchived()) {
            throw new RuntimeException("Can not apply changes to archive article");
        }
        if ($changeArticle->getTitle() !== null) {
            $this->title = $changeArticle->getTitle();
        }
        if ($changeArticle->getBody() !== null) {
            $this->body = $changeArticle->getBody();
        }

        $this->updatedAt = $changeArticle->getUpdatedAt();
    }

    public function archive(ArchiveArticle $archiveArticle)
    {
        $this->archivedAt = $archiveArticle->getArchivedAt();
    }

    public function isArchived(): bool
    {
        return $this->archivedAt !== null;
    }

    public function mappedData(): array
    {
        return [
            'id' => $this->id->asString(),
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->createdAt->asString(),
            'updated_at' => $this->updatedAt->asString(),
        ];
    }
}
