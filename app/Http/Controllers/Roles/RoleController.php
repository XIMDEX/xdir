<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $result = $this->roleService->createRole($data);

        if (!$result['success']) {
            return response()->json(['errors' => $result['errors']], 422);
        }

        return response()->json(['message' => 'Role created successfully', 'role' => $result['role']], 201);
    }

    public function update(Request $request, $roleId)
    {
        $data = $request->all();
        $result = $this->roleService->updateRole($data, $roleId);

        if (!$result['success']) {
            return response()->json(['errors' => $result['errors']], 422);
        }

        return response()->json(['message' => 'Role update successfully', 'role' => $result['role']], 201);
    }

    public function getList(){
        $roles = Role::all();
        $roles->makeHidden(['created_at', 'updated_at', 'guard_name']);
        return response()->json(['roles' => $roles]);
    }
}