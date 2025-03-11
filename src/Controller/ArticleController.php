<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/article', name: 'app_articlecontroller_showallarticle')]
    public function showAllArticle(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_articlecontroller_showarticle', requirements: ['id' => '\d+'])]
    public function showArticle(int $id): Response
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('article_detail.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/add', name: 'app_articlecontroller_add')]
    public function addArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $msg = "";
        $status = "";

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($article);  // Correction de la variable: $account -> $article
                $this->em->flush();
                $msg = 'L\'article a été ajouté avec succès';
                $status = 'success';
            } catch (\Exception $e) {
                $msg = 'Une erreur est survenue lors de l\'ajout de l\'article';
                $status = 'danger';
            }
        }

        $this->addFlash($status, $msg);

        return $this->render('addarticle.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
