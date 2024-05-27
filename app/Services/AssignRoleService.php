<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Exception;

class AssignRoleService
{
    public function assignRole(User $user, string $role_uuid,string $organization_uuid)
    {
        try {
            $user->assignRoleWithOrganization($role_uuid,$organization_uuid);
        } catch (Exception $e) {
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

    public function addPermissionToRole(Role $role, $permission)
    {
        if ($role->hasPermissionTo($permission)) {
            throw new Exception('Role already has the specified permission');
        }

        try {
            $role->givePermissionTo($permission);
        } catch (Exception $e) {
            // Handle any exceptions that occur during permission assignment
            throw new Exception('Error assigning permission to role: ' . $e->getMessage());
        }
    }

    public function removePermissionFromRole(Role $role, $permission)
    {
        if (!$role->hasPermissionTo($permission)) {
            throw new Exception('Role does not have the specified permission');
        }
        try {
            $role->revokePermissionTo($permission);
        } catch (Exception $e) {
            // Handle any exceptions that occur during permission revocation 
            throw new Exception('Error revoking permission from role: ' . $e->getMessage());
        }
    }

    
}
