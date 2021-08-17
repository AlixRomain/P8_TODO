<?php

namespace App\Services;


class RoleHelper
{


    /**
     * Return true if the subject has no role (creation)
     * Return true if current user role is superior to the user he wants to edit
     *
     * @param array $currentUserRole An array with roles of the current user
     * @param       $subjectRole
     *
     * @return boolean
     */
    public function roleSuperior($currentUserRole, $subjectRole)
    {
        // Case of user creation
        if (empty($subjectRole)) {
            return true;
        }
        $roles = [
            'ROLE_USER'=> 0,
            'ROLE_ADMIN'=> 1,
            'ROLE_SUPER_ADMIN'=> 2,
        ];

        return $roles[$currentUserRole[0]] > $roles[$subjectRole[0]];
    }

}
