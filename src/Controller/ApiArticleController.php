<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    #[Route('/api/articles', name: 'api_article_all', methods: ['GET'])]
    public function getAllArticles(): Response
    {
        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [],
            ['groups' => 'article:read']
        );
    }

    #[Route('/api/articles/{id}', name: 'api_article_by_id', methods: ['GET'])]
    public function getArticleById(int $id): Response
    {
        $article = $this->articleRepository->find($id);

        return $this->json(
            $article,
            200,
            [],
            ['groups' => 'article:readbyid']
        );
    }
}
