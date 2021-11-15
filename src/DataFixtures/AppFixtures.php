<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Laminas\Code\Generator\DocBlock\Tag;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        //Création entre 15 et 30 tâches alétoirement
        for ($t = 0; $t < mt_rand(15, 30); $t++) {
            $task = new Task;
            // On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime()) // Attention les dates
                // sont fonctions du serveur
                ->setDueAt($faker->dateTimeBetween('now', '6 months'));
            $manager->persist($task);
        }

        $manager->flush();
    }
}
