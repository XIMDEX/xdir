<?php 

namespace App\Services;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RoleService{

    protected $role; 

    public function __construct(Role $role)
    {
        $this->role =  $role;
    }

    public function createRole(Array $data){
        $data['uuid'] = Uuid::uuid4();
        //$data['guard_name'] = "api";
        $role = $this->role->create($data);

        return [
            'success' => true,
            'role' => $role
        ];
    }

    public function updateRole(Array $data, $id){
        $role = $this->role->find($id);
        $role->update($data);
        
        return [
            'success' => true,
            'role' => $role
        ];
    }

    public function removePermissionFromRole(Role $role, $permission)
    {
        if (!$role->hasPermissionTo($permission)) {
            throw new Exception('Role does not have the specified permission');
        }
        try {
            $role->revokePermissionTo($permission);
        } catch (Exception $e) {
            throw new Exception('Error revoking permission from role: ' . $e->getMessage());
        }
    }
}