<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PermissionService
{
    public function createPermission(array $data)
    {
        $data['uuid'] = Uuid::uuid4();

        $permission = Permission::create($data);

        return [
            'success' => true,
            'permission' => $permission
        ];
    }

    public function updatePermission(Permission $permission, array $data)
    {
        $permission->update($data);
        return [
            'success' => true,
            'permission' => $permission
        ];
    }

    public function deletePermission(Permission $permission)
    {
        $permission->delete();

        return [
            'success' => true,
            'message' => 'Permission removed successfully'
        ];
    }
}