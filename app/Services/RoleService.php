<?php

namespace App\Services;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RoleService
{

    protected $role;

    public function __construct(Role $role)
    {
        $this->role =  $role;
    }

    public function createRole(array $data)
    {
        try {
            $data['uuid'] = Uuid::uuid4();
            $role = $this->role->create($data);

            return [
                'success' => true,
                'role' => $role
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to create role: ' . $e->getMessage()
            ];
        }
    }

    public function updateRole(array $data, $id)
    {
        try {
            $role = $this->role->find($id);
            $role->update($data);

            return [
                'success' => true,
                'role' => $role
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to update role: ' . $e->getMessage()
            ];
        }
    }

    public function removePermissionFromRole(Role $role, $permission)
    {
        try {
            if (!$role->hasPermissionTo($permission)) {
                throw new Exception('Role does not have the specified permission');
            }
            $role->revokePermissionTo($permission);
        } catch (Exception $e) {
            throw new Exception('Error revoking permission from role: ' . $e->getMessage());
        }
    }

    public function removeRole($roleId)
    {
        try {
            $role = $this->role->find($roleId);
            if ($role) {
                $role->delete();
                return [
                    'success' => true,
                    'message' => 'Role removed successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => 'Role not found'
                ];
            }
        } catch (Exception $e) {
            throw new Exception('Error removing role: ' . $e->getMessage());
        }
    }

    public function checkIfRoleIsNotAssignedToPermission(Role $role)
    {
        return $role->permissions()->count() === 0;
    }

    public function checkIfRoleIsNotAssignedToUser(Role $role)
    {
        return $role->users()->count() === 0;
    }
}
