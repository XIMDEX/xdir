<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AssignRoleService;
use Exception;
use Illuminate\Http\Request;

class AssignRoleController extends Controller
{
    protected $assignRoleService;

    public function __construct(AssignRoleService $assignRoleService)
    {
        $this->assignRoleService = $assignRoleService;
    }

    public function assignRoleToUser(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $role = $request->role;
            if (!Role::where('name', $role)->exists()) {
                return response()->json(['error' => 'Role does not exist'], 404);
            }

            $this->assignRoleService->assignRole($user, $request->role);
            
            return response()->json(['message' => 'Role assigned successfully'], 200);
        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unassignRole(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $role = $request->role;
            if (!Role::where('name', $role)->exists()) {
                return response()->json(['error' => 'Role does not exist'], 404);
            }

            if (!$user->hasRole($role)) {
                return response()->json(['error' => 'User does not have the specified role'], 400);
            }

            $this->assignRoleService->revokeRole($user, $request->role);
            
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
