<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listUsers()
    {
        try {
            $users = $this->userService->getAllUsers()->load('roles'); 
            $users->each(function ($user) {
                $user->roles->each(function ($role) use ($user) {
                    
                    $role->organization_id = $role->pivot->organization_id;
                    $role->makeHidden('pivot');
                });
            });
            $users->makeHidden(['password', 'remember_token','email_verified_at','created_at','updated_at']); 
            return response()->json(['users' => $users]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the user list.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
