<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use EsperoSoft\Faker\Faker;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        $faker = new Faker();
        
        $users = [];
        for ($i=0; $i < 100; $i++) { 
            $user = (new User())->setFullName($faker->full_name())
                                ->setEmail($faker->email())
                                ->setPassword(sha1("passer"))
                                ->setCreatedAt($faker->dateTimeImmutable())
            ;
            $address = (new Address())->setStreet($faker->streetAddress())
                                        ->setCodePostal($faker->codePostal())
                                        ->setCity($faker->city())
                                        ->setCountry($faker->country())
                                        ->setCreatedAt($faker->dateTimeImmutable())
                                        ->setUser($user); // Associez le profil à l'utilisateur


            ;

            $profile = (new Profile())->setPicture($faker->image())
                                        ->setCoverPicture($faker->image())
                                        ->setDescription($faker->description(60))
                                        ->setCreatedAt($faker->dateTimeImmutable())
                                        ->setUpdatedAt($faker->dateTimeImmutable())
                                        ->setUser($user); // Associez le profil à l'utilisateur
            ;
            
            $user->addAddresses($address);
            
            $user->setProfile($profile);

            $users[]= $user;

            $manager->persist($address);
            $manager->persist($profile);
            $manager->persist($user);
        }

        $categories = [];
        for ($i=0; $i < 10; $i++) { 
            $category = (new Category())->setName($faker->description(60))
                                        ->setDescription($faker->description(30))
                                        ->setImageUrl($faker->image())
                                        ->setCreatedAt($faker->dateTimeImmutable())
            ;

            $categories[] = $category;
            $manager->persist($category);
        }

        for ($i=0; $i < 300; $i++) { 
            $article = (new Article())->setTitle($faker->description(30))
                                    ->setContent($faker->text(5, 10))
                                    ->setAuthor($users[\rand(0, count($users)-1)])
                                    ->setImageUrl($faker->image())
                                    ->setCreatedAt($faker->dateTimeImmutable())
                                    ->addCategory($categories[rand(0, count($categories)-1)])
            ;

            $manager->persist($article);

        }
        
        $manager->flush();
    }
    
}
