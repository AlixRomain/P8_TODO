<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class Utils extends WebTestCase
{
    protected static $application;

    protected $client;

    protected static $container;

    protected $em;

    /**
     * Create the database | Create tables | Load Fixture
     * Create client | Get container | Get entityManager
     */
    protected function setUp():void
    {

        /*self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load -n');
        exit();*/
    }

    /**
     * Run console command line
     *
     * @param string $command
     *
     * @return Int 0 if if everything went fine
     */
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    /**
     * Get Application
     *
     * @return Application
     */
    protected static function getApplication(): Application
    {
        self::ensureKernelShutdown();
        $client =  static::createClient();
        self::$application = new Application($client->getKernel());
        self::$application->setAutoExit(false);

        return self::$application;
    }
    /**
     * Log in with every ROLE
     */
    protected function createClientNav($mail)
    {
        $client = $this::createClient();
        self::$container     = $client->getContainer();
        $repo = $this->em = self::$container->get('doctrine')->getManager();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail($mail);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        return [$client,$repo];
    }

    /**
     * get a tools Container
     */
    protected function getTools()
    {

        $client = $this::createClient();
        self::$container     = $client->getContainer();
        return $this->em = self::$container->get('doctrine')->getManager();

    }
   /**
     * get a client
     */
    protected function getClientCx()
    {
        return  $this::createClient();
    }


    /**
     * Drop database
     */
   /*protected function tearDown():void
    {
        self::runCommand('doctrine:database:drop --force');
        $this->em->close();
        $this->em = null;
    }*/
}
