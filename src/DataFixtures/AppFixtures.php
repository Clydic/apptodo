<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     * 
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');

        // Création de nos 5 catégories
        for ($c = 0; $c < 5; $c++) {
            //Création d'un nouvel objet Tag
            $tag = new Tag;

            // On ajoute un nom à notre catégorie
            $tag->setName($faker->colorName());

            // On fait persister les données
            $manager->persist($tag);
        }

        $manager->flush();

        // Récupérations des catégories crées
        $tags = $manager->getRepository(Tag::class)->findAll();
        //Création entre 15 et 30 tâches alétoiremen,

        for ($t = 0; $t < mt_rand(15, 30); $t++) {
            $task = new Task;
            // On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime()) // Attention les dates
                // sont fonctions du serveur
                ->setDueAt($faker->dateTimeBetween('now', '6 months'))
                ->setTag($faker->randomElement($tags));
            $manager->persist($task);
        }

        // Création de 5 Utilisateurs
        for ($u = 0; $u < 5; $u++) {
            // Création d'un  nouvel objer User
            $user = new User;

            // Hashaqe de notre mot de passe avec les paramètres de sécurité 
            //de notre mot de passe dans /config/password/security

            $hash = $this->encoder->hashPassword($user, "passwor");
            $user->setPassword($hash);

            // Si premier utilisateur créé on lui donne le rôle d'admein
            if ($u === 0) {
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail("admin@admin.fr");
            } else {
                $user->setEmail($faker->safeEmail());
            }

            // On fait persister les données
            $manager->persist($user);
        }

        $manager->flush();
    }
}
