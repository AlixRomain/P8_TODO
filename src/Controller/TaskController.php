<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\FilterType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Services\FilterService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private $em;
    private $repoUser;
    private $repoTask;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, TaskRepository $taskRepository)
    {
        $this->em = $entityManager;
        $this->repoUser = $userRepository;
        $this->repoTask = $taskRepository;
    }
    /**
     * @Route("/tasks-all/{param}", name="all_tasks")
     * @param $param string
     */
    public function list($param, Request $request, FilterService $filterService)
    {
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $request->get('filter')['filter'];
            $param = $filterService->filterTask($filter);
        }
        ($param =='all')?$toto = true: $toto = null;
            return $this->render('task/list.html.twig',
                                 [
                                     'tasks' => ($toto)?$this->getDoctrine()->getRepository('App:Task')->findBy([],['createdAt' => 'DESC']):$param,
                                     'form' =>  $form->createView()
                                 ]);
    }

    /**
     * Show one task
     *
     * @Route("/tasks/{id}/show", name="task_show")
     *
     * @param Task $task
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Task $task)
    {
        return $this->render('task/show.html.twig', ['task' => $task]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request)
    {
        $users = $this->repoUser->findAll();
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task,[
            'action' => $this->generateUrl('task_create'),
            'method' => 'GET',
            'users'=> $users
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('all_tasks', ['param' => 'all']);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function edit(Task $task, Request $request)
    {
        $users = $this->repoUser->findAll();
        $form = $this->createForm(TaskType::class, $task,[
            'action' => $this->generateUrl('task_edit', ['id' => $task->getId()]),
            'method' => 'GET',
            'users'=> $users
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('all_tasks', ['param' => 'all']);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('all_tasks', ['param' => 'all'],301);
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("ROLE_USER")
     * @Security(
     *      "user === task.getUser() || is_granted('ROLE_ADMIN')",
     *      message = "Vous n'avez pas les droits pour supprimer cette tâche"
     * )
     */
    public function deleteTask(Task $task)
    {
        if($task){
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }
        return $this->redirectToRoute('all_tasks', ['param' => 'all']);
    }
}
