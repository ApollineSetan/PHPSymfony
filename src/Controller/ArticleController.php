<?php 

namespace App\Controller;

// src/Controller/ArticleController.php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_articlecontroller_showallarticle')]
    public function showAllArticle(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_articlecontroller_showarticle')]
    public function showArticle(Article $article): Response
    {
        return $this->render('article_detail.html.twig', [
            'article' => $article,
        ]);
    }
}
