<?php

namespace App\Domain;

use App\Domain\Model\Article;

/**
 * Interface ArticleRepository
 * @package App\Domain
 */
interface ArticleRepository
{
    public function getById(string $id): Article;

    public function getNextId(): string;

    public function save(Article $article): void;
}
