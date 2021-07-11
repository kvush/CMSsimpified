<?php

namespace App\Application;

use App\Domain\ArchiveArticle;
use App\Domain\Article;
use App\Domain\ArticleDto;
use App\Domain\ArticleRepository;
use App\Domain\ChangeArticle;
use App\Domain\DateTime;
use RuntimeException;

/**
 * Class ArticleManager
 * @package App\Application
 */
final class ArticleManager
{
    private string $hardCodedSecretKey = 'some_secret_token';

    private ArticleRepository $repository;
    private Clock $clock;

    /**
     * ArticleManager constructor.
     * @param ArticleRepository $repository
     * @param Clock $clock
     */
    public function __construct(ArticleRepository $repository, Clock $clock)
    {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    /**
     * @param string $title
     * @param string $body
     * @param string $token
     * @return string article id
     */
    public function createArticle(string $title, string $body, string $token): string
    {
        if ($token !== $this->hardCodedSecretKey) {
            throw new RuntimeException("you have no access");
        }

        $id = $this->repository->getNextId();
        $articleDto = new ArticleDto([
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'createdAt' => $this->clock->currentTime()->format(DateTime::DATE_TIME_FORMAT),
        ]);

        $article = Article::createFromDto($articleDto);
        $this->repository->save($article);

        return $id;
    }

    public function updateArticle(string $id, ?string $title, ?string $body, string $token): void
    {
        if ($token !== $this->hardCodedSecretKey) {
            throw new RuntimeException("you have no access");
        }

        $article = $this->repository->getById($id);
        $updatedAt = $this->clock->currentTime()->format(DateTime::DATE_TIME_FORMAT);
        $article->apply(new ChangeArticle($title, $body, $updatedAt));

        $this->repository->save($article);
    }

    public function deleteArticle(string $id, string $token): void
    {
        if ($token !== $this->hardCodedSecretKey) {
            throw new RuntimeException("you have no access");
        }

        $article = $this->repository->getById($id);
        $deletedAt = $this->clock->currentTime()->format(DateTime::DATE_TIME_FORMAT);

        $article->archive(new ArchiveArticle($deletedAt));

        $this->repository->save($article);
    }
}
