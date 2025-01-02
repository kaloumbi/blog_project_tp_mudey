<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoriesServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    public function __construct(CategoriesServices $categoriesServices) {
        $categoriesServices->updatSession();
    }
    

    #[Route('/', name: 'app_blog')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();



        // dd($articles);
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            // 'categories' => $categories, (remplacer par les sessions)
        ]);
    }

    //Notre article par sluge
    #[Route('/article/{slug}', name: 'app_single_article')]
    public function single(ArticleRepository $articleRepository, string $slug): Response
    {
        $article = $articleRepository->findOneBySlug($slug);

        // dd($articles);
        return $this->render('blog/single.html.twig', [
            'article' => $article,
        ]);
    }


    #[Route('/category/{slug}', name: 'app_article_by_category')]
    public function articleByCategory(ArticleRepository $articleRepository, string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        
        $articles = [];
        if($category){
            $articles = $category->getArticles()->getValues(); //pour les collections
        }

        
        // dd($articles);
        return $this->render('blog/articles_by_category.html.twig', [
            'articles' => $articles,
            'category' => $category,
        ]);
    }
}
