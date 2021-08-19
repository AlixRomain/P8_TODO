<?php

namespace App\Services;


use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FilterService extends AbstractController
{

    private $repoUser;

    public function __construct( UserRepository $repoUser)
    {
        $this->repoUser = $repoUser;
    }

    /**
     * Return true if the subject has no role (creation)
     * Return true if current user role is superior to the user he wants to edit
     *
     * @param $param
     *
     * @return boolean
     */
    public function filterUser($param)
    {
        if ($param) {
            switch ($param) {
                case 'all':
                    $param = 'all';
                    break;
                case 0:
                    $param = $this->repoUser->findByRole('ROLE_ADMIN');
                    break;
                case 1:
                    $param = $this->repoUser->findByRole('ROLE_USER');
                    break;
            }
        }
        return $param;
    }
/**
     * Return true if the subject has no role (creation)
     * Return true if current user role is superior to the user he wants to edit
     *
     * @param $param
     *
     * @return boolean
     */
    public function filterTask($param)
    {

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
        return $param;
    }

}
