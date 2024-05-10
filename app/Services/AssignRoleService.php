<?php

namespace App\Services;

use App\Models\User;
use Exception;

class AssignRoleService
{
    public function assignRole(User $user, $role)
    {
        try {
            $user->assignRole($role);
        } catch (Exception $e) {
            // Handle any exceptions that occur during role assignment
            echo 'Error assigning role: ' . $e->getMessage();
        }
    }

    public function revokeRole(User $user, $role)
    {
        try {
            $user->removeRole($role);
        } catch (Exception $e) {
            // Handle any exceptions that occur during role revocation
            echo 'Error revoking role: ' . $e->getMessage();
        }
    }
}
