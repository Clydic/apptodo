<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/listening", name="task")
     */
    public function index(): Response
    {
        // On va chercher avec la Doctrine le Repository
        $repository = $this->getDoctrine()->getRepository(Task::class);

        // Dans ce repository nous récupérons toutes les données
        $tasks = $repository->findAll();

        //Affichage du var_dump
        //var_dump($tasks);
        //die;
        // dd($tasks);
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
}
