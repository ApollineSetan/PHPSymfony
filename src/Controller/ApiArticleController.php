<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly AccountRepository $accountRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
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

    // Ajouter un article
    #[Route('/api/article', name: 'api_article_add', methods: ['POST'])]
    public function addArticle(Request $request): Response
    {
        $json = $request->getContent();
        $article = $this->serializer->deserialize($json, Article::class, 'json');

        if ($article->getTitle() && $article->getContent() && $article->getAuthor()) {
            $article->setAuthor($this->accountRepository->findOneBy(["email" => $article->getAuthor()->getEmail()]));
            foreach ($article->getCategories() as $key => $value) {
                $cat = $value->getName();
                $article->removeCategory($value);
                $cat = $this->categoryRepository->findOneBy(["name" => $cat]);
                $article->addCategory($cat);
            }

            if (!$this->articleRepository->findOneBy(["title" => $article->getTitle(), "content" => $article->getContent()])) {
                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $code = 201;
            } else {
                $code = 400;
                $article = "Article existe déjà";
            }
        } else {
            $code = 400;
            $article = "Champs non remplis";
        }
        return $this->json(
            $article,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'articles:read']
        );
    }
}
