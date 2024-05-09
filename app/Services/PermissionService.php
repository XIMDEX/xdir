<?php

namespace App\Services;

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
        $data['uuid'] = Uuid::uuid4();
        $permission = $this->permission->create($data);

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