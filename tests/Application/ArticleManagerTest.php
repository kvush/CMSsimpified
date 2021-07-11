<?php

namespace App\Tests\Application;

use App\Application\ArticleManager;
use App\Domain\Model\Article;
use App\Domain\Dto\ArticleDto;
use App\Domain\ArticleRepository;
use App\Domain\MyDateTime;
use App\Infrastructure\SystemClock;
use App\Tests\InMemoryArticleRepository;
use App\Tests\ReflectionAccess;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Class ArticleManagerTest
 * @package App\Tests\Application
 */
class ArticleManagerTest extends TestCase
{
    private const SECRET_TOKEN = 'some_secret_token';
    private const WRONG_SECRET_TOKEN = 'wrong_secret_token';
    private const ARTICLE_ID = '9f769195-7221-4138-a295-7a1a4f498268';
    private const CREATED_DATE_TIME = '2021-01-01 10:03:13';

    private ArticleManager $articleManager;
    private ArticleRepository $articleRepository;

    public function setUp(): void
    {
        $repo = new InMemoryArticleRepository();
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => 'Test Article',
            'body' => 'This is first article',
            'createdAt' => self::CREATED_DATE_TIME
        ]);
        $initialArticle = Article::createFromDto($articleDto);
        $repo->save($initialArticle);


        $fakeClock = $this->createStub(SystemClock::class);
        $fakeClock->method('currentTime')
            ->willReturn(DateTimeImmutable::createFromFormat(MyDateTime::DATE_TIME_FORMAT, '2021-07-11 12:34:17'));

        $this->articleManager = new ArticleManager($repo, $fakeClock);
        $this->articleRepository = $repo;
    }

    /** @test  */
    public function create_article()
    {
        $title = 'This is Test';
        $body = 'body was fat';

        $articleId = $this->articleManager->createArticle($title, $body, self::SECRET_TOKEN);
        $article = $this->articleRepository->getById($articleId);

        $this->assertEquals($articleId, ReflectionAccess::getValue($article, 'id')->asString());
        $this->assertEquals($title, ReflectionAccess::getValue($article, 'title'));
        $this->assertEquals($body, ReflectionAccess::getValue($article, 'body'));
    }

    /** @test */
    public function cant_create_with_wrong_key()
    {
        $this->expectExceptionMessage("you have no access");
        $this->articleManager->createArticle("la la la", "bla bla bla", self::WRONG_SECRET_TOKEN);
    }

    /** @test  */
    public function update_article()
    {
        $this->articleManager->updateArticle(self::ARTICLE_ID, null, 'new body', self::SECRET_TOKEN);
        $article = $this->articleRepository->getById(self::ARTICLE_ID);
        $this->assertEquals('new body', ReflectionAccess::getValue($article, 'body'));

        $this->articleManager->updateArticle(self::ARTICLE_ID, 'new title', null, self::SECRET_TOKEN);
        $article = $this->articleRepository->getById(self::ARTICLE_ID);
        $this->assertEquals('new title', ReflectionAccess::getValue($article, 'title'));
    }

    /** @test  */
    public function cant_update_with_wrong_key()
    {
        $this->expectExceptionMessage("you have no access");
        $this->articleManager->updateArticle(self::ARTICLE_ID, null, 'new body', self::WRONG_SECRET_TOKEN);
    }

    /** @test  */
    public function delete_article()
    {
        $article = $this->articleRepository->getById(self::ARTICLE_ID);
        $this->assertNotEmpty($article);

        $this->articleManager->deleteArticle(self::ARTICLE_ID, self::SECRET_TOKEN);

        $this->expectExceptionMessage("article not found");
        $this->articleRepository->getById(self::ARTICLE_ID);
    }

    /** @test  */
    public function cant_delete_article_with_wrong_key()
    {
        $this->expectExceptionMessage("you have no access");
        $this->articleManager->deleteArticle(self::ARTICLE_ID, self::WRONG_SECRET_TOKEN);
    }
}
