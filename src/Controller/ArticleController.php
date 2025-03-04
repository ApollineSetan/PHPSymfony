<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_articlecontroller_allarticles')]
    public function allArticles(): Response {
        return $this->render('articles.html.twig');
    }

    #[Route('/article/{id}', name: 'app_articlecontroller_articleid')]
    public function articleId($id): Response {
        return $this->render('article_detail.html.twig', [
            'id' => $id,
        ]);
    }
}
