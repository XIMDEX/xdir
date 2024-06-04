<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPaginationRequest;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listUsers(UserPaginationRequest $request)
    {
        try {
            $page = $request->query('page', 1);
            $users = $this->userService->getAllUsers($page); 
           
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
            $user->makeHidden(['password', 'remember_token','email_verified_at','created_at','updated_at']); 
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->userService->deleteUser($id); 
            return response()->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
