<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\FilterType;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/{param}", name="task_list")
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
                    $param = $this->getDoctrine()->getRepository('App:Task')->findBy([],['deadLine' => 'DESC']);
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
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
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

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
