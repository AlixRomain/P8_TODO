<?php

namespace App\Tests\Controller;

use App\Tests\Utils;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends Utils
{


    public function testHomepageRedirection()
    {
        $client = $this::getClientCx();
        // Log in
        $client->request('GET', '/');

        // If you're not connected, you are redirected to /login page
        static::assertResponseRedirects('/login');
    }


    public function test404WhenFakeLink()
    {
        $client = $this::getClientCx();
        // Assert that not existing route return 404
        $client->request('GET', '/-1');
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
