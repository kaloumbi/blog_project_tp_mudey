<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $articles = $articleRepository->findAll();
        $categories = $categoryRepository->findAll();

        // dd($articles);
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    //Notre article par sluge
    #[Route('/article/{slug}', name: 'app_single_article')]
    public function single(ArticleRepository $articleRepository, string $slug, CategoryRepository $categoryRepository): Response
    {
        $article = $articleRepository->findOneBySlug($slug);
        $categories = $categoryRepository->findAll();

        // dd($articles);
        return $this->render('blog/single.html.twig', [
            'article' => $article,
            'categories' => $categories
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

        $categories = $categoryRepository->findAll();

        
        // dd($articles);
        return $this->render('blog/articles_by_category.html.twig', [
            'articles' => $articles,
            'category' => $category,
            'categories' => $categories
        ]);
    }
}
