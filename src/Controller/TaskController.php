<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManager;
use PhpParser\Node\Expr\FuncCall;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @var TaskRepository
     *
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     *
     */
    private $manager;

    public function __construct(
        TaskRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
    }


    /**
     * @Route("/task/listening", name="task-listening")
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
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/create", name="task_create")
     * @Route("/task/update/{id}", name="task_update", requirements={"id"="\d+"})
     */
    public function task(Task $task = null, Request $request)
    {
        if (!$task) {

            $task = new Task;

            $task->setCreatedAt(new \DateTime());
        }


        $form = $this->createForm(TaskType::class, $task, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setName($form['name']->getData())
                ->setDescription($form['description']->getData())
                ->setDueAt($form['dueAt']->getData())
                ->setTag($form['tag']->getData());

            $this->manager->persist($task);
            $this->manager->flush();

            return $this->redirectToRoute('task-listening');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/task/delete/{id}", name="task_delete", requirements={"id"="\d+"})
     *
     */
    public function deleteTask(Task $task): Response
    {

        $this->manager->remove($task);
        $this->manager->flush();

        return $this->redirectToRoute("task-listening");
    }
}
