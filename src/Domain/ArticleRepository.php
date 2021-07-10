<?php

namespace App\Domain;

/**
 * Interface ArticleRepository
 * @package App\Domain
 */
interface ArticleRepository
{
    public function getById(string $id): Article;
}
