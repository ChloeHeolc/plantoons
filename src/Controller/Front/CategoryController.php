<?php

namespace App\Controller\Front;

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
     * @Route("front/category", name="front_app_category")
     */
    public function index(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    //Categories List
    /**
     * @Route("front/categories", name="front_categories_list")
     */
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render("front/category/categories_list.html.twig", ['categories' => $categories]);
    }

    //View details of a category
    /**
     * @Route("front/category/{id}", name="front_category_show")
     */
    public function showCategory($id, CategoryRepository $categoryRepository) {
        $category = $categoryRepository->find($id);

        return $this->render("front/category/category_show.html.twig", ['category' => $category]);
    }

    //Edit detail of a category
    /**
     * @Route("front/update/category/{id}", name="front_update_category")
     */
    public function updateCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface, Request $request) {
        $category = $categoryRepository->find($id);

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("front_categories_list");
        }
        return $this->redirectToRoute("front/category/category_form.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    //Create new category
    /**
     * @Route("front/create/category", name="front_create_category")
     */
    public function createCategory(EntityManagerInterface $entityManagerInterface, Request $request) {

        $category = new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("front_categories_list");
        }
        return $this->render("front/category/category_form.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    //Delete a category
    /**
     * @Route("front/delete/category/{id}", name="front_delete_category")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface) {
        $category = $categoryRepository->find($id);

        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("front/category/categories_list");
    }
}
