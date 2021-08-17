<?php

namespace App\Tests\Controller;

use App\Tests\Utils;
use Symfony\Component\HTTPFoundation\Response;

class SecurityControllerTest extends Utils
{

    public function testNoAdminLinkWhenUser()
    {
        $client = $this::createClientNav('user@gmail.com');
        $crawler = $client[0]->request('GET', '/');
        // Extract links from homepage and assert that admin links is not in it
        $links = $crawler->filter('a.btn')->extract(['_text']);
        static::assertNotContains('Créer un utilisateur', $links);
        static::assertNotContains('Utilisateurs', $links);
    }

    public function testAdminLink()
    {
        $client = $this::createClientNav('admin@gmail.com');
        $crawler = $client[0]->request('GET', '/users-all/all');
        static::assertResponseIsSuccessful();
        static::assertRouteSame('all-users');
        // Extract links from homepage and assert that admin links is in it
        $links = $crawler->filter('a.btn')->extract(['_text']);
        static::assertContains('Créer un utilisateur', $links);
        static::assertContains('Utilisateurs', $links);

        // Assert link "Créer un utilisateur" exist and href content is OK
        $createUserLink = $crawler->selectLink('Créer un utilisateur')->link();
        $uriCreateUser = $createUserLink->getUri();
        static::assertStringContainsString('/users/create', $uriCreateUser);

        // Assert link "Liste des utilisateurs" exist and and href content is OK
        $listUserLink = $crawler->selectLink('Utilisateurs')->link();
        $uriListUser = $listUserLink->getUri();
        static::assertStringContainsString('/users-all/all', $uriListUser);
    }

        public function testInvalidCredentialsMessage()
        {
            $client = $this::getClientCx();

            $crawler = $client->request('GET', '/login');

            // Log in with wrong password
            $crawler = $client->submitForm('Se connecter', [
                'email' => 'Admin@fake.com',
                'password' => 'Fake'
            ]);

            // Assert that flash message is displayed
            $client->followRedirect();
            static::assertSelectorTextSame('div.alert', "Identifiants invalides.");
        }

               public function testLogoutUsingLink()
               {
                   $client = $this::createClientNav('user@gmail.com');
                   $crawler = $client[0]->request('GET', '/tasks-all/all');
                   // Log out using link
                   $link = $crawler->selectLink('Se déconnecter')->link();

                   $crawler = $client[0]->click($link);

                   // Assert that we are back to the login page with the button "Se connecter"
                   $client[0]->followRedirect();
                   static::assertSelectorTextSame('button.btn', 'Se connecter');
                   static::assertRouteSame('app_login');
               }

              public function testLogoutUsingRoute()
              {
                  $client = $this::createClientNav('user@gmail.com');

                  // Log out using route
                  $crawler = $client[0]->request('GET', '/logout');

                  // Assert that we are back to the login page with the button "Se connecter"
                  $client[0]->followRedirect();
                  static::assertSelectorTextSame('button.btn', 'Se connecter');

                  static::assertRouteSame('app_login');
              }
}
