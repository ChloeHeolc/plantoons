<?php

namespace App\Controller\User;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("user/category", name="user_app_category")
     */
    public function index(): Response
    {
        return $this->render('user/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    //Categories List
    /**
     * @Route("user/categories", name="user_categories_list")
     */
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render("user/category/categories_list.html.twig", ['categories' => $categories]);
    }

    //View details of a category
    /**
     * @Route("user/category/{id}", name="user_category_show")
     */
    public function showCategory($id, CategoryRepository $categoryRepository) {
        $category = $categoryRepository->find($id);

        return $this->render("user/category/category_show.html.twig", ['category' => $category]);
    }

    //Edit detail of a category
    /**
     * @Route("user/update/category/{id}", name="user_update_category")
     */
    public function updateCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface, Request $request) {
        $category = $categoryRepository->find($id);

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("user_categories_list");
        }
        return $this->redirectToRoute("user/category/category_form.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    //Create new category
    /**
     * @Route("user/create/category", name="user_create_category")
     */
    public function createCategory(EntityManagerInterface $entityManagerInterface, Request $request) {

        $category = new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("user_categories_list");
        }
        return $this->render("user/category/category_form.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    //Delete a category
    /**
     * @Route("user/delete/category/{id}", name="user_delete_category")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface) {
        $category = $categoryRepository->find($id);

        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("user/category/categories_list");
    }
}
