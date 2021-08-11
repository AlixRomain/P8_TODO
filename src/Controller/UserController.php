<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FilterUserType;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;
    private $hasher;
    private $repoUser;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hashe, UserRepository $repoUser)
    {
        $this->em = $entityManager;
        $this->hasher = $hashe;
        $this->repoUser = $repoUser;

    }

    /**
     * @Route("/users-all/{param}", name="all-users")
     * @IsGranted("ROLE_ADMIN")
     * @param $param string
     */
    public function listAction($param, Request $request)
    {
        $form = $this->createForm(FilterUserType::class,['csrf_protection' => false, 'method' => 'GET']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $param = $request->get('filter_user')['filterUser'];
            switch ($param) {
                case 'all':
                    $param = 'all';
                    break;
                case 0:
                    $param = $this->repoUser->findBy(['roles' => 'ROLE_ADMIN']);
                    break;
                case 1:
                    $param = $this->repoUser->findBy(['roles' => json_encode('ROLE_USER')]);
                    break;
            }
        }
        ($param =='all')?$toto = true: $toto = null;
        $user= $this->repoUser->findAll();
        return $this->render('user/list.html.twig', [
            'users' => ($toto)?$this->repoUser->findAll():$param,
            'form' =>  $form->createView()
        ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request )
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$request->get('user')['role']]);
            $password =$this->hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('all-users', ['param' => 'all']);
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @Security(
     *      "user === userId || is_granted('ROLE_ADMIN')",
     *      message = "Vous n'avez pas les droits pour modifier cette utilisateur"
     * )
     * @param User    $userId
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(User $userId, Request $request)
    {
        $form = $this->createForm(UserEditType::class, $userId);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $userId->setRoles([$request->get('user_edit')['role']]);
           $password =$this->hasher->hashPassword($userId, $userId->getPassword());
           $userId->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('all-users', ['param' => 'all']);
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $userId]);
    }
    /**
     * User deletion
     *
     * @Route("/users/{id}/delete", name="user_delete")
     * @IsGranted("DELETE", subject="userToDelete")
     * @param User $userToDelete
     *
     * @return Response
     */
    public function deleteAction(User $userToDelete)
    {
        $this->em->remove($userToDelete);
        $this->em->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé');
        return $this->redirectToRoute('all-users', ['param' => 'all']);
    }
}
