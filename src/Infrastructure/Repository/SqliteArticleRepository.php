<?php

namespace App\Infrastructure\Repository;

use App\Domain\ArticleRepository;
use App\Domain\Dto\ArticleDto;
use App\Domain\MyDateTime;
use App\Infrastructure\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SqliteArticleRepository extends ServiceEntityRepository implements ArticleRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Article::class);
        $this->em = $em;
    }

    public function getById(string $id): \App\Domain\Model\Article
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new RuntimeException('article not found');
        }
        $article = \App\Domain\Model\Article::createFromDto(new ArticleDto([
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'body' => $entity->getBody(),
            'createdAt' => $entity->getCreatedAt()->format(MyDateTime::DATE_TIME_FORMAT),
            'updatedAt' => $entity->getUpdatedAt()->format(MyDateTime::DATE_TIME_FORMAT),
            'deletedAt' => $entity->getDeletedAt()->format(MyDateTime::DATE_TIME_FORMAT),
        ]));
        if ($article->isArchived()) {
            throw new RuntimeException('article not found');
        }

        return $article;
    }

    public function getNextId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function save(\App\Domain\Model\Article $article): void
    {
        $mappedData = $article->mappedData();
        $entity = new Article();
        $entity->setId($mappedData['id']);
        $entity->setTitle($mappedData['title']);
        $entity->setBody($mappedData['body']);
        $entity->setCreatedAt($mappedData['created_at']);
        $entity->setUpdatedAt($mappedData['updated_at']);
        $entity->setDeletedAt($mappedData['deleted_at']);

        $this->em->persist($entity);
        $this->em->flush();
    }
}
