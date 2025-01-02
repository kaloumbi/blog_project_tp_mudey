<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoriesServices {
    
    //Recuperer le repository des categ
    private $repoCat;

    //Y stocker la requette
    private $requestStack;

    public function __construct(RequestStack $requestStack, CategoryRepository $categoryRepository) {
        
        $this->repoCat = $categoryRepository;
        $this->requestStack = $requestStack;
    }

    //function pour la mise à jour de la session
    public function updatSession() {
        //recuperer la session
        $session = $this->requestStack->getSession();
        //recuperer les categories
        $categories = $this->repoCat->findAll();

        //sauvegarer les dans la session
        $session->set("categories", $categories);
    }
}

