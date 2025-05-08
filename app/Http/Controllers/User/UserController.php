<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPaginationRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $userService;
    protected $auth;

    public function __construct(UserService $userService, Guard $auth)
    {
        $this->userService = $userService;
        $this->auth = $auth;
    }

    public function listUsers(UserPaginationRequest $request)
    {
        try {
            $page = $request->query('page', 1);
            $users = $this->userService->getAllUsersFilterByOrganization($page,$this->auth->user()->organizations->pluck('uuid')->toArray());

            // $users->makeHidden(['password', 'remember_token','email_verified_at','created_at','updated_at']); 
            return response()->json(['users' => $users]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the user list.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            $user->makeHidden(['password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at', 'roles']);
            
            // Check if the authenticated user has the teacher role
            if ($this->auth->user() && $this->auth->user()->roles && $this->auth->user()->roles->contains('name', 'teacher')) {
                // Get the organization IDs of the authenticated user
                $organizationIds = $this->auth->user()->organizations->pluck('uuid')->toArray();
                
                // Get all users from the same organizations (only id and name)
                $organizationUsers = User::whereHas('organizations', function($query) use ($organizationIds) {
                    $query->whereIn('organization_uuid', $organizationIds);
                })->select('uuid', 'name', 'surname')->get();
                
                // Add the organization users to the response
                return response()->json([
                    'user' => $user,
                    'organization_users' => $organizationUsers
                ]);
            }
            
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser($id)
    {
        try {
            if ($this->auth->user()->hasRole('admin|superadmin') || $this->auth->user()->id == $id) {
                $this->userService->deleteUser($id);
                return response()->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['error' => 'Only admin can delete user'], Response::HTTP_UNAUTHORIZED);
            }

            
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserByToken(Request $request)
    {
        $user = Auth::guard('api')->user();
        return response()->json(['user' => $user]);
    }

    /**
     * Get multiple users by their IDs
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersByIds(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'string|exists:users,uuid'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            $userIds = $request->input('ids');
            $users = User::whereIn('uuid', $userIds)
                ->select('uuid', 'name', 'surname')
                ->get();

            return response()->json(['users' => $users]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching users.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
