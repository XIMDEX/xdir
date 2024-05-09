<?php 

namespace App\Services;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RoleService{

    public function createRole(Request $request){
        $data['uuid'] = Uuid::uuid4();

        $permission = Role::create($data);

        return [
            'success' => true,
            'permission' => $permission
        ];
    }
}