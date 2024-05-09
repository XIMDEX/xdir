<?php 

namespace App\Services;

use App\Models\Role;
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
}