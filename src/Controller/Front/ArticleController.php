<?php

namespace App\Controller\Front;

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
     * @Route("front/article", name="front_app_article")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("front/articles", name="front_articles_list")
     */
    public function articleList(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render("front/articles_list.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("front/article/{id}", name="front_article_show")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("front/article/article_show.html.twig", ['article' => $article]);
    }

    /**
     * @Route("front/update/article/{id}", name="front_update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute("front_articles_list");
        }
        return $this->render("front/article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    //Création d'un nouvel article
        /**
     * @Route("front/create/article", name="front_create_article")
     */
    public function createArticle(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
        
            return $this->redirectToRoute("front_articles_list");
        }
        return $this->render("front/article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    /**
     * @Route("front/delete/article/{id}", name="front_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);

        $entityManagerInterface->remove($article);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("front/article/articles_list");
    }

}
