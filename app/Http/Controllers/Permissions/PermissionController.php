<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    // Method to create a new permission
    public function create(PermissionRequest $request)
    {

        try {
            $result = $this->permissionService->createPermission($request->all());

            if (!$result['success']) {
                return response()->json(['errors' => $result['errors']], 422);
            }

            return response()->json(['message' => 'Permission created successfully', 'permission' => $result['permission']], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating user'], 500);
        }
    }

    // Method to update an existing permission
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'guard_name' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $permission->update($validator->validated());

        return response()->json(['message' => 'Permission updated successfully', 'permission' => $permission]);
    }

    // Method to remove an existing permission
    public function remove(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permission removed successfully']);
    }
}
