<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

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
            return response()->json(['error' => 'An error occurred while creating Permission'], 500);
        }
    }


    public function update(PermissionRequest $request, $permissionId)
    {
        try {
            $permission = Permission::find($permissionId);

            $result = $this->permissionService->updatePermission($permission, $request->all());

            if (!$result['success']) {
                return response()->json(['errors' => $result['errors']], 422);
            }

            $permission = $result['permission'];

            return response()->json(['message' => 'Permission updated successfully', 'permission' => $permission]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating Permission'], 500);
        }
    }

    // Method to remove an existing permission
    public function remove($permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            
            if ($this->permissionService->isPermissionUnassigned($permissionId)) {
                $this->permissionService->deletePermission($permission);
                return response()->json(['message' => 'Permission removed successfully']);
            } else {
                return response()->json(['error' => 'Permission is assigned to a role'], 422);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }
    }

    public function getList()
    {
        try {
            $permissions = Permission::all();
            $permissions->makeHidden(['created_at', 'updated_at', 'guard_name']);
            return response()->json(['permissions' => $permissions]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching permissions'], 500);
        }
    }
}
