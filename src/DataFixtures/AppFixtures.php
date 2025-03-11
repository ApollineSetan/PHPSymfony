<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // Création d'un tableau pour stocker les comptes fake
        // Création d'un tableau pour stocker les catégories fake
        $accountsArray = [];
        $categoriesArray = [];

        // Générer 50 comptes fake
        for ($i = 0; $i < 50; $i++) {
            $account = new Account();
            $account->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($faker->password())
                ->setRoles("ROLE_USER");

            // Stocker les comptes fake dans le tableau de comptes fake
            $accountsArray[] = $account;
            $manager->persist($account);
        }

        // Générer 30 catégories fake
        for ($i = 0; $i < 30; $i++) {
            $category = new Category();
            $category->setName($faker->word());

            // Stocker les catégories fake dans le tableau de catégories fake
            $categoriesArray[] = $category;
            $manager->persist($category);
        }

        // Générer 100 articles fake
        for ($i = 0; $i < 100; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence())
                ->setContent($faker->text())
                ->setCreateAt($faker->dateTimeBetween()); //Datetime immutable$faker->date()

            // On crée une variable auteur fake et on lui assigne un compte fake au hasard du tableau
            $randomAuthor = $accountsArray[array_rand($accountsArray)];
            $article->setAuthor($randomAuthor);

            // On associe trois catégories aléatoires à un article
            $randomCategories = $faker->randomElements($categoriesArray, 3);
            foreach ($randomCategories as $category) {
                $article->addCategory($category);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
