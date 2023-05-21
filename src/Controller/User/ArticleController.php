<?php

namespace App\Controller\User;

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
     * @Route("user/article", name="user_app_article")
     */
    public function index(): Response
    {
        return $this->render('user/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("user/articles", name="user_articles_list")
     */
    public function articleList(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render("user/article/articles_list.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("user/article/{id}", name="user_article_show")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("user/article/article_show.html.twig", ['article' => $article]);
    }

    /**
     * @Route("user/update/article/{id}", name="user_update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute("user_articles_list");
        }
        return $this->render("user/article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    //Création d'un nouvel article
        /**
     * @Route("user/create/article", name="user_create_article")
     */
    public function createArticle(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
        
            return $this->redirectToRoute("user_articles_list");
        }
        return $this->render("user/article/article_form.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    /**
     * @Route("user/delete/article/{id}", name="user_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);

        $entityManagerInterface->remove($article);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("user/article/articles_list");
    }

}
