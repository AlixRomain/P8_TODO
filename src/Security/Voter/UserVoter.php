<?php

namespace App\Security\Voter;


use App\Services\RoleHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    private $roleHelper;
    private $security;

    public function __construct(RoleHelper $roleHelper, Security $security)
    {
        $this->roleHelper = $roleHelper;
        $this->security = $security;
    }
    protected function supports(string $attribute, $subject): bool
    {
        //Lance le voteOnAttribute() si l'attribut est dans le tableau et que le subject est une instance de la classe User
        return in_array($attribute, ['EDIT', 'ROLE_EDITION', 'DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                //Si l'utilisateur connecté est l'objet USER
                if ($user === $subject) {
                    return true;
                }
                //Return tur si le user connecté à un rôle supérieur à celui de l'objet user $subject
                return $this->roleHelper->roleSuperior($user->getRoles(), $subject->getRoles());
                break;
            //Return true si l'utilisateur connecter n'est pas l'objet User $subject et que son rôle est supérieur à celui de l'objet $subject
            case 'ROLE_EDITION':
                if ($user !== $subject && $this->roleHelper->roleSuperior($user->getRoles(), $subject->getRoles())) {
                    return true;
                }
                break;
            //Return True si le userconnecter n'est pas le user $subject mais que son rôle est ceui du superAdmin
            case 'DELETE':
                if ($user !== $subject && $this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                break;
        }
        return false;
    }
}
