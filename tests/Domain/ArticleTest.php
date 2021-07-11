<?php

namespace App\Tests\Domain;

use App\Domain\Dto\ArchiveArticle;
use App\Domain\Model\Article;
use App\Domain\Dto\ArticleDto;
use App\Domain\Dto\ChangeArticle;
use App\Tests\ReflectionAccess;
use PHPUnit\Framework\TestCase;

/**
 * Class ArticleTest
 * @package App\Tests\Domain
 */
class ArticleTest extends TestCase
{
    private const ARTICLE_ID = '9f769195-7221-4138-a295-7a1a4f498268';
    private const CREATED_DATE_TIME = '2021-01-01 10:03:13';
    private const UPDATED_DATE_TIME = '2021-01-02 12:59:00';
    private const DELETED_DATE_TIME = '2021-01-03 15:05:59';

    /** @test */
    public function create_new_article()
    {
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => 'Test Article',
            'body' => 'This is first article',
            'createdAt' => self::CREATED_DATE_TIME,
        ]);
        $article = Article::createFromDto($articleDto);

        $this->assertEquals(self::ARTICLE_ID, ReflectionAccess::getValue($article, 'id')->asString());
        $this->assertEquals('Test Article', ReflectionAccess::getValue($article, 'title'));
        $this->assertEquals('This is first article', ReflectionAccess::getValue($article, 'body'));
        $this->assertEquals(self::CREATED_DATE_TIME, ReflectionAccess::getValue($article, 'createdAt')->asString());
        $this->assertEquals(self::CREATED_DATE_TIME, ReflectionAccess::getValue($article, 'updatedAt')->asString());
    }

    /** @test */
    public function update_article()
    {
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => 'Test Article',
            'body' => 'This is first article',
            'createdAt' => self::CREATED_DATE_TIME
        ]);
        $article = Article::createFromDto($articleDto);

        $newTitle = 'new tile';
        $newBody = 'this is edited body';

        $changeArticle = new ChangeArticle($newTitle, $newBody, self::UPDATED_DATE_TIME);
        $article->apply($changeArticle);

        $this->assertEquals($newTitle, ReflectionAccess::getValue($article, 'title'));
        $this->assertEquals($newBody, ReflectionAccess::getValue($article, 'body'));
        $this->assertEquals(self::UPDATED_DATE_TIME, ReflectionAccess::getValue($article, 'updatedAt')->asString());
    }

    /** @test */
    public function delete_article()
    {
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => 'Test Article',
            'body' => 'This is first article',
            'createdAt' => self::CREATED_DATE_TIME
        ]);
        $article = Article::createFromDto($articleDto);

        $archiveArticle = new ArchiveArticle(self::DELETED_DATE_TIME);
        $article->archive($archiveArticle);

        $this->assertTrue($article->isArchived());
        $this->assertEquals(self::DELETED_DATE_TIME, ReflectionAccess::getValue($article, 'archivedAt')->asString());
    }

    /** @test  */
    public function cant_make_empty_body()
    {
        $this->expectExceptionMessage('body must be provided');
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => 'Test Article',
            'body' => '',
            'createdAt' => self::CREATED_DATE_TIME
        ]);
        Article::createFromDto($articleDto);
    }

    /** @test  */
    public function cant_make_empty_title()
    {
        $this->expectExceptionMessage('title must be provided');
        $articleDto = new ArticleDto([
            'id' => self::ARTICLE_ID,
            'title' => '',
            'body' => 'some article body',
            'createdAt' => self::CREATED_DATE_TIME
        ]);
        Article::createFromDto($articleDto);
    }
}
