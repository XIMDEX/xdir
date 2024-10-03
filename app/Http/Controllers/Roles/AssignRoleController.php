<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleToUserRequest;
use App\Http\Requests\RevokeRoleToUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AssignRoleService;
use App\Services\ToolService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignRoleController extends Controller
{
    protected $assignRoleService;
    protected $toolService;
    protected $auth;

    public function __construct(AssignRoleService $assignRoleService, ToolService $toolService)
    {
        $this->assignRoleService = $assignRoleService;
        $this->toolService = $toolService;
    }

    public function assignRoleToUser(AssignRoleToUserRequest $request)
    {
            try {
                $user = User::findOrFail($request->user_uuid);
                $this->assignRoleService->assignRole($user,$request->organizations);
                $tools = $this->assignRoleService->getRolesForOrganization($user,$request->organizations); 
                foreach ($request->organizations as  $organization) {
                    foreach ($organization['services'] as $service) {
                        $isXdam = $this->checkIfXdamService($service['service_uuid']);
                        if ($isXdam) {
                            $this->toolService->createUserOnService($user, $service['service_uuid']);
                        }
                    }
                }
                return response()->json(['message' => 'Role assigned successfully'], Response::HTTP_OK);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred while assigning the role'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    public function unassignRole(RevokeRoleToUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_uuid);
            $this->assignRoleService->revokeRole($user, $request->role_uuid, $request->organization_uuid, $request->tool_uuid);
            return response()->json(['message' => 'Role unassigned successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addPermissionToRole(Request $request)
    {
        try {
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], Response::HTTP_NOT_FOUND);
            }

            $this->assignRoleService->addPermissionToRole($role, $request->permission);

            return response()->json(['message' => 'Permission added to role successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function revokePermissionFromRole(Request $request)
    {
        try {
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], Response::HTTP_NOT_FOUND);
            }

            $this->assignRoleService->removePermissionFromRole($role, $request->permission);

            return response()->json(['message' => 'Permission revoked from role successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param array $userRequest
     * @return bool
     */
    protected function checkIfXdamService(string $serviceUUID): bool
    {
            $service = $this->toolService->findServiceById($serviceUUID);
            if ($service->type === 'xdam') {
                return true;
            }
            return false;
    }




}
