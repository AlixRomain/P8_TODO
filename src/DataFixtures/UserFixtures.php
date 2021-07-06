<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixtures extends Fixture
{
    public const SUPER_ADMIN_USER_REFERENCE = 'super-admin-user';
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const USER_REFERENCE = 'user';
    public const ANON_USER_REFERENCE = 'user-anon';

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Super Admin
        $userSuperAdmin = new User;
        $userSuperAdmin->setName('SuperAdmin');

        $password = $this->encoder->hash('SuperAdmin34!');
        $userSuperAdmin->setPassword($password);

        $userSuperAdmin->setEmail('superadmin@gmail.com');
        $userSuperAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($userSuperAdmin);

        // Admin
        $userAdmin = new User;
        $userAdmin->setName('Admin');

        $password = $this->encoder->hash('Admin34!');
        $userAdmin->setPassword($password);

        $userAdmin->setEmail('admin@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $manager->persist($userAdmin);

        // Anonymous User
        $userAnon = new User;
        $userAnon->setName('UserAnon');

        $password = $this->encoder->hash('UserAnon34!');
        $userAnon->setPassword($password);

        $userAnon->setEmail('user-anon@gmail.com');
        $userAnon->setRoles(['ROLE_ADMIN']);

        $manager->persist($userAnon);

        // User
        $user = new User;
        $user->setName('User');

        $password = $this->encoder->hash('User340!');
        $user->setPassword($password);

        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
        $this->addReference(self::ANON_USER_REFERENCE, $userAnon);
        $this->addReference(self::USER_REFERENCE, $user);
    }
}
