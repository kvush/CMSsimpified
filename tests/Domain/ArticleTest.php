<?php

namespace App\Tests\Domain;

use PHPUnit\Framework\TestCase;

/**
 * Class ArticleTest
 * @package App\Tests\Domain
 */
class ArticleTest extends TestCase
{
    /**
     * @test
     */
    public function create_new_article()
    {
        $id = 'some_unique_id';
        $title = 'test article';
        $body = 'this is first article';
        $createdAt = 1;
        $updatedAt = 1;

        $article = Article::createNew($id, $title, $body);

        $this->assertEquals($article->id, $id);
        $this->assertEquals($article->title, $title);
        $this->assertEquals($article->body, $body);
        $this->assertEquals($article->createdAt, $createdAt);
        $this->assertEquals($article->updatedAt, $updatedAt);
    }

    /**
     * @test
     */
    public function update_article()
    {
        $id = 'some_unique_id';
        $title = 'test article';
        $body = 'this is first article';
        $createdAt = 1;
        $updatedAt = 1;

        $article = Article::createNew($id, $title, $body);

        $newBody = 'this is edited body';
        $article->applyChangedBody($newBody);

        $newTitle = 'new tile';
        $article->rename($newTitle);

        $this->assertEquals($article->title, $newTitle);
        $this->assertEquals($article->body, $newBody);
        $this->assertEquals($article->updatedAt, $updatedAt);
    }

    /**
     * @test
     */
    public function delete_article()
    {
        $id = 'some_unique_id';
        $title = 'test article';
        $body = 'this is first article';
        $createdAt = 1;
        $updatedAt = 1;
        $archivedAt = 1;

        $article = Article::createNew($id, $title, $body);

        $article->archive();
        $this->assertTrue($article->archived);
        $this->assertEquals($article->archivedAt, $archivedAt);
    }
}
