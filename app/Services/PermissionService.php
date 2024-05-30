<?php

namespace App\Services;

use Exception;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Permission;


class PermissionService
{

    protected $permission;
    protected $uuidGenerator;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function createPermission(array $data)
    {
        try {
            $data['uuid'] = Uuid::uuid4();
            $permission = $this->permission->create($data);

            return [
                'success' => true,
                'permission' => $permission
            ];
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating permission'], 500);
        }
    }

    public function updatePermission(Permission $permission, array $data)
    {
        try {
            $permission->update($data);
            return [
                'success' => true,
                'permission' => $permission
            ];
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating permission'], 500);
        }
    }

    public function deletePermission(Permission $permission)
    {
        try {
            $permission->delete();

            return [
                'success' => true,
                'message' => 'Permission removed successfully'
            ];
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting permission'], 500);
        }
    }

    public function isPermissionUnassigned($permission)
    {
        try {
            if (!$permission) {
                throw new Exception("Permission not found");
            }

            // Check if permission is not assigned to any role
            $rolesCount = $permission->roles()->count();
            $usersCount = $permission->users()->count();

            return $rolesCount === 0 && $usersCount === 0;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while checking if permission is unassigned'], 500);
        }
    }
}
