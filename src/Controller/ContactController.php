<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CategoryRepository;
use App\Service\CategoriesServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public function __construct(CategoriesServices $categoriesServices) {
        $categoriesServices->updatSession();
    }
    
    
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        //Ajout de message flash
        $session = $request->getSession();
        if ($form->isSubmitted() && $form->isValid()  ) {
            # code...
            $contact->setCreatedAt(createdAt: new \DateTimeImmutable());
            $em->persist($contact);
            $em->flush();

            //pour vide notre formulaire après la soumission
            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);


            $session->getFlashBag()->add("message", "Message envoyé avec succès !");
            $session->set("status", "success");
            // dd($contact);
        }elseif ($form->isSubmitted() && ! $form->isValid()) {

            $session->getFlashBag()->add("message", "Merci de corriger les erreurs !");
            $session->set("status", "primary");
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contact' => $form->createView()
        ]);
    }
}
