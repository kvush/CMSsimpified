<?php

namespace App\Tests;

use App\Domain\Article;
use App\Domain\ArticleRepository;
use RuntimeException;

/**
 * Class InMemoryArticleRepository
 * @package App\Tests
 */
class InMemoryArticleRepository implements ArticleRepository
{
    /** @var Article[]  */
    private array $articles = [];

    public function getById(string $id): Article
    {
        if (isset($this->articles[$id]) && !$this->articles[$id]->isArchived()) {
            return $this->articles[$id];
        }
        throw new RuntimeException('article not found');
    }

    public function getNextId(): string
    {
        return '97bf8759-40df-4488-9130-5de170ea847e';
    }

    public function save(Article $article): void
    {
        $this->articles[$article->getId()] = $article;
    }
}
