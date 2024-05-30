<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssingRoleToUserRequest;
use App\Http\Requests\RevokeRoleToUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AssignRoleService;
use Exception;
use Illuminate\Http\Request;

class AssignRoleController extends Controller
{
    protected $assignRoleService;
    protected $auth;

    public function __construct(AssignRoleService $assignRoleService)
    {
        $this->assignRoleService = $assignRoleService;
    }

    public function assignRoleToUser(AssingRoleToUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_uuid);
            $this->assignRoleService->assignRole($user, $request->role_uuid, $request->organization_uuid, $request->tool_uuid);
            return response()->json(['message' => 'Role assigned successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unassignRole(RevokeRoleToUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_uuid);
            $this->assignRoleService->revokeRole($user, $request->role_uuid);
            return response()->json(['message' => 'Role unassigned successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addPermissionToRole(Request $request)
    {
        try {
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            $this->assignRoleService->addPermissionToRole($role, $request->permission);

            return response()->json(['message' => 'Permission added to role successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function revokePermissionFromRole(Request $request)
    {
        try {
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            $this->assignRoleService->removePermissionFromRole($role, $request->permission);

            return response()->json(['message' => 'Permission revoked from role successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




}
