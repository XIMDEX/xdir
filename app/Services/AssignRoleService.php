<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AssignRoleService
{
    public function assignRole(User $user, array $organizations)
    {
        \DB::beginTransaction();
        try {

            array_map(function ($organization) use ($user, $organizations) {
                array_map(function ($service) use ($user, $organization) {
                    $this->processToolRoles($user, $service['role_uuid'], $organization['organization_uuid'], $service['service_uuid']);
                }, $organization['services']);
            }, $organizations);


            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error synchronizing roles: ' . $e->getMessage());
            throw new Exception('Error synchronizing roles: ' . $e->getMessage());
        }
        return $user;
    }

    public function revokeRole(User $user, $role)
    {
        try {
            $user->removeRole($role);
        } catch (Exception $e) {
            // Handle any exceptions that occur during role revocation
            throw new \Exception($e->getMessage());
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


    private function getRolesForOrganizationAndTool($user, $organizationId, $toolId)
    {
        return $user->roles()
            ->wherePivot('organization_id', $organizationId)
            ->wherePivot('tool_id', $toolId)
            ->pluck('role_id')
            ->toArray();
    }

    private function addRoles($user, $roles, $organizationId, $toolId)
    {
        foreach ($roles as $role) {
            $user->roles()->attach($role, [
                'organization_id' => $organizationId,
                'model_type' => get_class($this),
                'tool_id' => $toolId,
            ]);
        }
    }

    private function removeRoles($user, $roles, $organizationId, $toolId)
    {
        if (!empty($roles)) {
            $pivotTable = $user->roles()->getTable(); // Get the pivot table name
            $pivotForeignKey = $user->roles()->getForeignPivotKeyName(); // Get the foreign key name for User
            $pivotRelatedKey = $user->roles()->getRelatedPivotKeyName(); // Get the related key name for Role

            \DB::table($pivotTable)
                ->where($pivotForeignKey, $user->getKey())
                ->whereIn($pivotRelatedKey, $roles)
                ->where('organization_id', $organizationId)
                ->where('tool_id', $toolId)
                ->delete();
        }
    }

    private function processToolRoles(User $user, $roles, $organizationId, $toolId)
    {
        $currentRoles = $this->getRolesForOrganizationAndTool($user, $organizationId, $toolId);

        $rolesToAdd = array_diff($roles, $currentRoles);
        $rolesToRemove = array_diff($currentRoles, $roles);

        $this->addRoles($user, $rolesToAdd, $organizationId, $toolId);
        $this->removeRoles($user, $rolesToRemove, $organizationId, $toolId);
    }
}
