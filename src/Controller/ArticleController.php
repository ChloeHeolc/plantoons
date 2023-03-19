<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/articles", name="articles_list")
     */
    public function articleList(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render("article/articles_list.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("article/{id}", name="article_show")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("article/article_show.html.twig", ['article' => $article]);
    }

    /**
     * @Route("update/article/{id}", name="update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute("articles_list");
        }
        return $this->render("article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    //Création d'un nouvel article
        /**
     * @Route("create/article", name="create_article")
     */
    public function createArticle(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
        
            return $this->redirectToRoute("articles_list");
        }
        return $this->render("article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    /**
     * @Route("delete/article/{id}", name="delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);

        $entityManagerInterface->remove($article);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("article/articles_list");
    }

}
