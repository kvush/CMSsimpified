<?php

namespace App\Infrastructure\Controller;

use App\Application\ArticleManager;
use App\Infrastructure\Repository\SqliteArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Infrastructure\Controller
 * @Route("/api", name="article_api")
 */
class ArticleController extends AbstractController
{
    /**
     * @param Request $request
     * @param SqliteArticleRepository $articleRepository
     * @return JsonResponse
     * @Route("/articles", name="articles", methods={"GET"})
     */
    public function getArticles(Request $request, SqliteArticleRepository $articleRepository): JsonResponse
    {
        $sort = $request->query->get('sort');
        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('perPage', 20);

        $orderBy = [];
        if ($sort) {
            if (strpos($sort,'-') === 0) {
                $orderBy = [str_replace('-', '', $sort) => 'DESC'];
            } else {
                $orderBy = [$sort => 'ASC'];
            }
        }
        $offset = ($page - 1) * $perPage;
        $articles = $articleRepository->findBy(['deleted_at' => null], $orderBy, $perPage, $offset);
        return $this->json($articles);
    }

    /**
     * @param Request $request
     * @param ArticleManager $articleManager
     * @return JsonResponse
     * @Route("/articles", name="articles_add", methods={"POST"})
     */
    public function addArticle(Request $request, ArticleManager $articleManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $articleManager->createArticle($data['title'], $data['body'], $data['token']);

        return $this->json([
            'status' => 200,
            'errors' => "Article created successfully",
        ]);
    }

    /**
     * @param string $id
     * @param string $token
     * @param ArticleManager $articleManager
     * @return JsonResponse
     * @Route("/articles/{id}", name="article", methods={"GET"})
     */
    public function getArticle(ArticleManager $articleManager, string $id, string $token): JsonResponse
    {
        $article = $articleManager->getArticle($id, $token);
        return $this->json($article);
    }

    /**
     * @param Request $request
     * @param ArticleManager $articleManager
     * @param string $id
     * @return JsonResponse
     * @Route("/articles/{id}", name="articles_put", methods={"PUT"})
     */
    public function updateArticle(Request $request, ArticleManager $articleManager, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $articleManager->updateArticle($id, $data['title'], $data['body'], $data['token']);

        return $this->json([
            'status' => 200,
            'errors' => "Article updated successfully",
        ]);
    }

    /**
     * @param Request $request
     * @param ArticleManager $articleManager
     * @param string $id
     * @return JsonResponse
     * @Route("/articles/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function deleteArticle(Request $request, ArticleManager $articleManager, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $articleManager->deleteArticle($id, $data['token']);

        return $this->json([
            'status' => 200,
            'errors' => "Article deleted successfully",
        ]);
    }
}
