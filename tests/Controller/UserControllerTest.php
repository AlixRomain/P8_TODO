<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class UserControllerTest extends Utils
{
    public function setUp($toto = false):void
    {
        parent::setUp(false);
    }
   public function testListAction()
    {
        $client = $this::createClientNav('admin@gmail.com');

        // Go to users page
        $crawler = $client[0]->request('GET', '/users-all/all');
        static::assertResponseIsSuccessful();
        static::assertRouteSame('all-users');

        // Asserting User
        $text = $crawler->filter('td')->extract(['_text']);
        static::assertContains('Admin', $text);
        static::assertContains('User', $text);

        // Assert not contains other admin
        static::assertNotContains('UserAnon', $text);
        static::assertNotContains('SuperAdmin', $text);
    }
    public function testListActionByTargetUser()
    {
        $client = $this::createClientNav('admin@gmail.com');
        // Go to task list
        $crawler = $client[0]->request('GET', '/users-all/all');
        static::assertResponseIsSuccessful();
        static::assertRouteSame('all-users');

        $form = $crawler->selectButton('Go')->form();
        $form['filter_user[filterUser]'] = 1;
        $crawler = $client[0]->submit($form);
        static::assertRouteSame('all-users');

        // Assert there are tasks
        $links = $crawler->filter('td.role')->extract(['_text']);
        static::assertNotContains('ROLE_ADMIN', $links);
        static::assertContains('ROLE_USER', $links);
    }

   public function testCreateUserAction()
    {
         $client = $this::createClientNav('admin@gmail.com');
         $crawler = $client[0]->request('GET', '/users/create');

         static::assertResponseIsSuccessful();
         $buttonCrawlerNode = $crawler->selectButton('Ajouter');
         // Add user with form
         $form = $buttonCrawlerNode->form();
         $form['user[name]'] = 'UserTest';
         $form['user[password][first]'] = 'OpenClass21!';
         $form['user[password][second]'] ='OpenClass21!';
         $form['user[email]'] = 'user-test@gmail.com';
         $form['user[role]']->select('ROLE_USER');

        $crawler = $client[0]->submit($form);
        $crawler = $client[0]->followRedirect();

        // Assert flash message is displayed
        static::assertSelectorTextSame('div.alert', 'Superbe ! L\'utilisateur a bien été ajouté.');

        // Go to users page
        $crawler = $client[0]->request('GET', '/users-all/all');
        static::assertResponseIsSuccessful();
        static::assertRouteSame('all-users');
         // Assert that the new user is in the list
         $tds = $crawler->filter('td')->extract(['_text']);
         static::assertContains('UserTest', $tds);

         // Assert user in DB
         $user = $client[1]->getRepository(User::class)->findOneBy(['name' => 'UserTest']);
         static::assertNotNull($user);
         static::assertSame('UserTest', $user->getName());
         static::assertSame('user-test@gmail.com', $user->getEmail());
         static::assertSame(array('ROLE_USER'), $user->getRoles());
    }

       public function testCreateActionRoleUser()
       {
           $client = $this::createClientNav('user@gmail.com');

           // Go to user creation page, assert that is forbidden
           $crawler = $client[0]->request('GET', '/users/create');
           static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
       }


      public function testEditUserAction()
      {
          $client = $this::createClientNav('admin@gmail.com');
          // Go to the edition page of the task id = 1
          $crawler = $client[0]->request('GET', '/users/4/edit');
          static::assertResponseIsSuccessful();

          // Edit the task with form
          $form = $crawler->selectButton('Modifier')->form();
          $form['user_edit[name]'] = 'UserUpdate';
          $form['user_edit[email]'] = 'user-update@gmail.com';
          $form['user_edit[role]'] = 'ROLE_USER';

          $crawler = $client[0]->submit($form);
          $crawler = $client[0]->followRedirect();

          // Assert flash message is displayed
          static::assertSelectorTextSame('div.alert', 'Superbe ! L\'utilisateur a bien été modifié');

          // Assert that the edited user is in the list
         $text = $crawler->filter('td')->extract(['_text']);
          static::assertContains('UserUpdate', $text);

          // Assert user in DB
          $user = $client[1]->getRepository(User::class)->findOneBy(['name' => 'UserUpdate']);
          static::assertNotNull($user);
          static::assertSame('UserUpdate', $user->getName());
          static::assertSame('user-update@gmail.com', $user->getEmail());
          static::assertSame(array('ROLE_USER'), $user->getRoles());
      }


    public function testEditActionRoleUser()
    {
        $client = $this::createClientNav('user-update@gmail.com');

        // Go to user edition page, assert that is forbidden
        $crawler = $client[0]->request('GET', '/users/3/edit');
        static::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test of unused (Symfony native) function of User Entity
     */
    public function testUserEntityFunction()
    {
        $client = $this::getTools();
        // Get the user from DB
        $user = $client->getRepository(User::class)->findOneBy(['name' => 'Admin']);
        static::assertNotNull($user);

        // Get user's task, asserting there is 5
        $tasks = $user->getTasks();
        static::assertSame(5, count($tasks));

        // Create new task, add user to task, assert user is the good one
        $task = new Task;
        $user->addTask($task);
        static::assertSame($user, $task->getUser());

        // Remove user from task, assert user is null now
        $user->removeTask($task);
        static::assertNull($task->getUser());
    }

        public function testDeleteActionUser()
        {
            $client = $this::createClientNav('superadmin@gmail.com');

            // Go to list of user
            $crawler = $client[0]->request('GET', '/users-all/all');
            static::assertResponseIsSuccessful();

            // Assert that the user with Name User exist
            $text = $crawler->filter('td')->extract(['_text']);
            static::assertContains('UserUpdate', $text);

            // Delete an user
            $crawler = $client[0]->request('GET', '/users/4/delete');
            $crawler = $client[0]->followRedirect();
            // Assert flash message is displayed
            static::assertSelectorTextSame('div.alert', 'Superbe ! L\'utilisateur a bien été supprimé');

            // Assert that the user with Name USer not exist
            $text = $crawler->filter('td')->extract(['_text']);
            static::assertNotContains('UserUpdate', $text);

        }

           public function testAccessUserPageWhenNotConnected()
            {
                $client = $this::getClientCx();
                // Go to edit form of an user being not authenticated
                $client->request('GET', '/users/3/edit');
                static::assertResponseRedirects('/login');
            }
}
