<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixtures extends Fixture
{
    private $encoder;
    private $repoUser;

    public function __construct(UserPasswordHasherInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->repoUser = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        // Super Admin
        $userSuperAdmin = new User;
        $userSuperAdmin->setName('SuperAdmin');

        $password = $this->encoder->hashPassword($userSuperAdmin,'OpenClass21!');
        $userSuperAdmin->setPassword($password);

        $userSuperAdmin->setEmail('superadmin@gmail.com');
        $userSuperAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($userSuperAdmin);

        // Admin
        $userAdmin = new User;
        $userAdmin->setName('Admin');

        $password = $this->encoder->hashPassword($userAdmin,'OpenClass21!');
        $userAdmin->setPassword($password);

        $userAdmin->setEmail('admin@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $manager->persist($userAdmin);

        // Anonymous User
        $userAnon = new User;
        $userAnon->setName('UserAnon');

        $password = $this->encoder->hashPassword($userAnon,'OpenClass21!');
        $userAnon->setPassword($password);

        $userAnon->setEmail('user-anon@gmail.com');
        $userAnon->setRoles(['ROLE_ADMIN']);

        $manager->persist($userAnon);

        // User
        $user = new User;
        $user->setName('User');

        $password = $this->encoder->hashPassword($user,'OpenClass21!');
        $user->setPassword($password);

        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();


        $users = $this->repoUser->findAll();
        foreach ($users as $user ){

            for ($i = 0; $i < 5; $i++) {
                $date 		= new \DateTime();
                ($i % 2 == 0)?$toto = true : $toto = false;
                $nameTache = '';
                if($user->getRoles()[0] =='ROLE_ADMIN'){
                    if($user->getEmail() == 'admin@gmail.com'){
                        $nameTache = 'Tâche_Admin_';
                    }else{
                        $nameTache = 'Tâche_Anonym_';
                    }
                }elseif ($user->getRoles()[0] =='ROLE_USER'){
                    $nameTache = 'Tâche_User_';
                }elseif ($user->getRoles()[0] =='ROLE_SUPER_ADMIN'){
                    $nameTache = 'Tâche_Super_Admin_';
                }
                $task = new Task();
                $task->setCreatedAt(new \DateTime());
                $task->setDeadLine($date->setTimestamp(strtotime("+15 days")));
                $task->setTitle($nameTache.($i + 1));
                $task->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
                $task->setIsDone($toto);
                $task->setUser($user);
                ($i % 2 == 0)?$task->setTargetUser($users[rand(0,3)]) : '';
                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
