<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\FilterType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
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
    public function listAction($param, Request $request)
    {
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $param = $request->get('filter')['filter'];
            switch ($param) {
                case 'all':
                    break;
                case 0:
                    $param = $this->getDoctrine()->getRepository('App:Task')->findBy([],['deadLine' => 'ASC']);
                    break;
                case 1:
                    $param = $this->getDoctrine()->getRepository('App:Task')->findBy(['id' =>'id > 0']);
                    break;
                case 2:
                    $param =  $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone' => 1]);
                    break;
                case 3:
                    $param =  $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone' => 0]);
                    break;
                case 4:
                    $param =  $this->getDoctrine()->getRepository('App:Task')->findBy(['targetUser' => $this->getUser()]);
                    break;
            }
        }
        ($param =='all')?$toto = true: $toto = null;
            return $this->render('task/list.html.twig',
                                 [
                                     'tasks' => ($toto)?$this->getDoctrine()->getRepository('App:Task')->findAll():$param,
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
    public function showAction(Task $task)
    {
        return $this->render('task/show.html.twig', ['task' => $task]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
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
    public function editAction(Task $task, Request $request)
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
    public function toggleTaskAction(Task $task)
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
    public function deleteTaskAction(Task $task)
    {
        if($task){
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }
        return $this->redirectToRoute('all_tasks', ['param' => 'all']);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
