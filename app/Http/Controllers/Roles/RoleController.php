<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
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

    
}
