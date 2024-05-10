<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
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

            $this->assignRoleService->assignRole($user, $request->role);
            
            return response()->json(['message' => 'Role assigned successfully'], 200);
        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
