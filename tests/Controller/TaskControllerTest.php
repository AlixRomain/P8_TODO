<?php

namespace App\Tests;


use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HTTPFoundation\Response;

class TaskControllerTest extends Utils
{
    public function setUp():void
    {
        parent::setUp();
    }
    //J'ai commenté le WebTestCase.php
  /*  public function testListActionWithRoute()
    {
        $client = $this::createClientNav('user@gmail.com');

        // test e.g. the profile page
        $crawler = $client[0]->request('GET', '/tasks-all/all');
        static::assertEquals(Response::HTTP_OK, $client[0]->getResponse()->getStatusCode());

        // Go to task list
        static::assertResponseIsSuccessful();
        static::assertRouteSame('all_tasks');

        // Assert there are tasks
        $links = $crawler->filter('a')->extract(['_text']);
        static::assertContains('Tâche_Super_Admin_1', $links);
        static::assertContains('Tâche_Super_Admin_2', $links);
    }

    public function testListWithButtonBackTo()
    {
        $client = $this::createClientNav('user@gmail.com');

        // Go to task creation page
        $crawler = $client[0]->request('GET', '/tasks/create');
        static::assertResponseIsSuccessful();

        // Click on back to task list button
        $link = $crawler->selectLink('Retour à la liste des tâches')->link();
        $crawler = $client[0]->click($link);
        static::assertResponseIsSuccessful();

        // Assert there are tasks
        $links = $crawler->filter('a')->extract(['_text']);
        static::assertContains('Tâche_Super_Admin_1', $links);
        static::assertContains('Tâche_Super_Admin_2', $links);
    }

        public function testListDoneAction()
        {
            $client = $this::createClientNav('user@gmail.com');
            // Go to done task list
            $crawler = $client[0]->request('GET', '/tasks/2/toggle');
            static::assertSame(301, $client[0]->getResponse()->getStatusCode());

        }

          public function testShowAction()
          {
              $client = $this::createClientNav('user@gmail.com');
              // Go to show task page
              $crawler = $client[0]->request('GET', '/tasks/1/show');
              static::assertResponseIsSuccessful();

              // Assert it's "Tâche 1"
              static::assertSelectorTextSame('small', 'Tâche_Super_Admin_1');
          }*/

             public function testCreateTask()
             {
                 $client = $this::createClientNav('user@gmail.com');

                 // Create task with form
                 $crawler = $client[0]->request('GET', '/tasks/create');

                 $form = $crawler->selectButton('Ajouter')->form();
//                 print_r($form->getName());exit;
                 $user = $client[1]->getRepository(User::class)->findOneBy(['name' => 'Admin']);
                 $form['task[title]'] = 'Tâche_Test';
                 $form['task[content]'] = 'Tâche de Test';
                 $form['task[deadline][month]'] = '09';
                 $form['task[deadline][day]'] = '19';
                 $form['task[deadline][year]'] = '2021';
                 $form['task[targetUser]'] = $user->getId();

                 $crawler = $client[0]->submit($form);
                 static::assertResponseIsSuccessful();
                 // Il faut suivre la redirection
                 /*$crawler = $client[0]->followRedirect();*/

                 static::assertSelectorNotExists("div.alert", 'La tâche a été bien été ajoutée.');
             }

               /*  public function testCreateTaskActionUniqueEntity()
                 {
                     $client = $this::createClientNav('user@gmail.com');

                     // Get task from DB
                     $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 1]);

                     // Create task with title that already exist
                     $crawler = $client[0]->request('GET', '/tasks/create');

                     $form = $crawler->selectButton('Ajouter')->form();

                     $form['task[title]'] = $task->getTitle();
                     $form['task[content]'] = 'Tâche Unique Entity Test';
                     $form['task[deadline][month]'] = '09';
                     $form['task[deadline][day]'] = '19';
                     $form['task[deadline][year]'] = '2021';

                     $crawler = $client[0]->submit($form);
                     static::assertResponseIsSuccessful();

                     // Assert error message
                     $message = $crawler->filter('li')->extract(['_text']);
                     static::assertContains('Cette valeur est déjà utilisée.', $message);
                     // Assert that there is still only one task with this title in DB
                     $tasks = $client[1]->getRepository(Task::class)->findBy(['title' => $task->getTitle()]);
                     static::assertSame(count($tasks), 1);
                 }*/

                 /*public function testEditTask()
                    {
                        $client = $this::createClientNav('user@gmail.com');
                        // Go to the edition page of the task id = 1
                        $crawler = $client[0]->request('GET', '/tasks/18/edit',);
                        static::assertResponseIsSuccessful();

                        // Edit the task with form
                        $form = $crawler->selectButton('Modifier')->form();

                        $form['task[title]'] = 'Tâche_Edition_Test';
                        $form['task[content]'] = 'Tâche Edition Test';
                        $crawler = $client[0]->submit($form);
                        static::assertSame(302, $client[0]->getResponse()->getStatusCode());

                        // Assert task is in DB and contains what we expect
                        $editedTask = $client[1]->getRepository(Task::class)->findOneBy(['title' => 'Tâche_Edition_Test']);
                        static::assertNotNull($editedTask);
                        static::assertSame('Tâche Edition Test', $editedTask->getContent());


                        // Assert the bounded user is still the good one
                        $user = $editedTask->getUser();
                        static::assertSame('User', $user->getName());
                    }*/
/*
                        public function testEditTaskActionUniqueEntity()
                        {
                            $client = $this::createClientNav('user@gmail.com');

                            // Get tasks from DB
                            $taskToEdit = $client[1]->getRepository(Task::class)->findOneBy(['id' => 1]);
                            $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 2]);

                            // Edit task with title of an existing task
                            $crawler = $client[0]->request('GET', '/tasks/' . $taskToEdit->getId() . '/edit');

                            $form = $crawler->selectButton('Modifier')->form();

                            $form['task[title]'] = $task->getTitle();
                            $form['task[content]'] = 'Tâche Unique Entity Test';

                            $crawler = $client[0]->submit($form);
                            static::assertResponseIsSuccessful();

                            // Assert error message
                            $message = $crawler->filter('li')->extract(['_text']);
                            static::assertContains('Cette valeur est déjà utilisée.', $message);

                            // Assert that there is still only one task with this title in DB
                            $tasks = $client[1]->getRepository(Task::class)->findBy(['title' => $task->getTitle()]);
                            static::assertSame(count($tasks), 1);
                        }

                        public function testToggleTaskAction()
                        {
                            $client = $this::createClientNav('user@gmail.com');

                            // Assert task id = 1 is not done in DB
                            $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 2]);
                            static::assertNotNull($task);
                            static::assertFalse($task->getIsDone());

                            // Go to task list
                            $crawler = $client[0]->request('GET', '/tasks-all/all');

                            // Assert that Tâche_1 is in task list
                            $links = $crawler->filter('a')->extract(['_text']);
                            static::assertContains($task->getTitle(), $links);

                            // Mark as done
                            $crawler = $client[0]->request('GET', '/tasks/'.$task->getId().'/toggle');
                            static::assertSame(301, $client[0]->getResponse()->getStatusCode());
                            //Go to task Done
                            $crawler = $client[0]->request('GET', '/tasks-all/all');
                            $form = $crawler->selectButton('Go')->form();
                            $form['filter[filter]'] = 2;
                            $crawler = $client[0]->submit($form);
                            static::assertResponseIsSuccessful();

                            // Assert that Tâche_1 is now in done task list
                            $links = $crawler->filter('a')->extract(['_text']);
                            static::assertContains($task->getTitle(), $links);

                            // Assert that Tâche_1 isn't in task list anymore
                            $crawler = $client[0]->request('GET', '/tasks-all/all');
                            $form = $crawler->selectButton('Go')->form();
                            $form['filter[filter]'] = 3;
                            $crawler = $client[0]->submit($form);
                            static::assertResponseIsSuccessful();
                            $links = $crawler->filter('a')->extract(['_text']);
                            static::assertNotContains($task->getTitle(), $links);

                            // Assert task id = 1 is now done in DB
                            $client[1]->close();
                            $doneTask = $client[1]->getRepository(Task::class)->findOneBy(['id' => 1]);
                            static::assertTrue($doneTask->getIsDone());
                        }

                          public function testDeleteTaskActionRoleUser()
                           {
                               $client = $this::createClientNav('user@gmail.com');

                               // Test deleting task with user != task.user (denied)
                               $crawler = $client[0]->request('GET', '/tasks/1/delete');
                               static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

                               // Assert task 11 exist in DB
                               $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 18]);
                               static::assertNotNull($task);

                               //Go to task Done
                               $crawler = $client[0]->request('GET', '/tasks-all/all');
                               $form = $crawler->selectButton('Go')->form();
                               $form['filter[filter]'] = 2;
                               $crawler = $client[0]->submit($form);
                               static::assertResponseIsSuccessful();

                               // Assert that task is in done task list
                               $links = $crawler->filter('a')->extract(['_text']);
                               static::assertContains($task->getTitle(), $links);

                               // Assert that task 11 is bounded to "User"
                               static::assertSame('User', $task->getUser()->getName());

                            // Assert user can delete his own tasks
                               $crawler = $client[0]->request('GET', '/tasks/'.$task->getId().'/delete');
                               static::assertSame(302, $client[0]->getResponse()->getStatusCode());

                               //Go to task Done
                               $crawler = $client[0]->request('GET', '/tasks-all/all');
                               $form = $crawler->selectButton('Go')->form();
                               $form['filter[filter]'] = 2;
                               $crawler = $client[0]->submit($form);
                               static::assertResponseIsSuccessful();

                               // Assert that task is no longer in done task list
                               $links = $crawler->filter('a')->extract(['_text']);
                               static::assertNotContains($task->getTitle(), $links);

                               // Assert that task no longer exist in database
                               $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 18]);
                              static::assertNull($task);
                               static::assertSame(404, $client[0]->getResponse()->getStatusCode());
                           }

                               public function testDeleteTaskActionRoleAdmin()
                               {
                                   $client = $this::createClientNav('admin@gmail.com');

                                   // Get the task and assert task exist in DB
                                   $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 14]);
                                   static::assertNotNull($task);

                                   // Assert that task is in task list
                                   $crawler = $client[0]->request('GET', '/tasks-all/all');
                                   $links = $crawler->filter('a')->extract(['_text']);
                                   static::assertContains($task->getTitle(), $links);

                                   // Assert Admin is not the owner
                                   $user = $task->getUser()->getName();
                                   static::assertNotSame('Admin', $user);

                                   // UserAnon is the owner
                                   static::assertSame('UserAnon', $user);

                                   // Assert Admin can delete tasks that he's not the owner
                                   $crawler = $client[0]->request('GET', '/tasks/' . $task->getId() . '/delete');
                                   static::assertSame(302, $client[0]->getResponse()->getStatusCode());

                                   // Go to task list
                                   $crawler = $client[0]->request('GET', '/tasks-all/all');
                                   static::assertResponseIsSuccessful();

                                   // Assert that task is no longer in task list
                                   $links = $crawler->filter('a')->extract(['_text']);
                                   static::assertNotContains($task->getTitle(), $links);

                                   // Assert that task no longer exist in database
                                   $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 14]);
                                   static::assertNull($task);
                               }
    /*
                                 /**
                                  * Test of unused (Symfony native) function of Task Entity
                                  */

      /* public function testTaskEntityFunction()
        {
            $tools = $this::getTools();
            // Get task from DB
            $task = $tools->getRepository(Task::class)->findOneBy(['title' => 'Tâche_Admin_2', 'user' => 2]);

            // Get user from DB
            $user =$tools->getRepository(User::class)->findOneBy(['id' => 2]);

            // Assert that getCreatedAt return an instance of datetime
            static::assertInstanceOf(DateTime::class, $task->getCreatedAt());

            // Assert that getUser return the good user
            static::assertSame($user, $task->getUser());

            // Assert that getIsDone return false
            static::assertFalse($task->getIsDone());
        }

        public function testAccessTaskEditionWhenNotConnected()
        {
            // Go to edit form of an user being not authenticated
            $client = $this::getClientCx();
            $client->request('GET', '/tasks/1/edit');
            static::assertResponseRedirects('/login');
        }

            public function testAccessTaskDeletionSuperAdmin()
            {
                $client = $this::createClientNav('superadmin@gmail.com');
                $task = $client[1]->getRepository(Task::class)->findOneBy(['id' => 1]);
                // Go to edit form of an user being not authenticated
                $crawler = $client[0]->request('GET', '/tasks/1/delete');
                static::assertSame(302, $client[0]->getResponse()->getStatusCode());

                // Go to task list
                $crawler = $client[0]->request('GET', '/tasks-all/all');
                static::assertResponseIsSuccessful();

                // Assert that task is no longer in task list
                $links = $crawler->filter('a')->extract(['_text']);
                static::assertNotContains($task->getTitle(), $links);
            }


        protected function tearDown():void
        {
            parent::tearDown();
        }*/
}
