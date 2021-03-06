<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FilterUserType;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\FilterService;
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
    public function list($param, Request $request, FilterService $filterService)
    {
        $form = $this->createForm(FilterUserType::class,['csrf_protection' => false, 'method' => 'GET']);
        $form->handleRequest($request);
        if(isset($request->get('filter_user')['filterUser'])){
            $filter = $request->get('filter_user')['filterUser'];
            $param = $filterService->filterUser($filter);
        }

        ($param =='all')?$toto = true: $toto = null;
        return $this->render('user/list.html.twig', [
            'users' => ($toto)?$this->repoUser->findAll():$param,
            'form' =>  $form->createView()
        ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request )
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,[
            'action' => $this->generateUrl('user_create'),
            'method' => 'GET',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$request->get('user')['role']]);
            $password =$this->hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien ??t?? ajout??.");

            return $this->redirectToRoute('all-users', ['param' => 'all']);
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @IsGranted("EDIT", subject="userId")
     * @param User    $userId
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(User $userId, Request $request)
    {
        $form = $this->createForm(UserEditType::class, $userId,[
            'action' => $this->generateUrl('user_edit', ['id' => $userId->getId()]),
            'method' => 'GET',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $userId->setRoles([$request->get('user_edit')['role']]);
           $password =$this->hasher->hashPassword($userId, $userId->getPassword());
           $userId->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien ??t?? modifi??");

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
    public function delete(User $userToDelete)
    {
        $this->em->remove($userToDelete);
        $this->em->flush();
        $this->addFlash('success', 'L\'utilisateur a bien ??t?? supprim??');
        return $this->redirectToRoute('all-users', ['param' => 'all']);
    }
}
