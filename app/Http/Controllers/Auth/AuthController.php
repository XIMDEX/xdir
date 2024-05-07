<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;


class AuthController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
      $this->userService = $userService;
    }
    public function register(RegisterRequest $request)
    {
        try {
           $user = $this->userService->createUser($request->validated());
           $user->makeHidden(['created_at', 'updated_at']);
           return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            // Log the error internally
            \Log::error($e);
            // Return a JSON response with the error message and a 500 status code
            return response()->json(['error' => 'An error occurred while registering the user. Please try again later.' . $e], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userService->getUser($request->only('email', 'password'));
            if($user){
                $user->makeHidden(['created_at', 'updated_at']);
                return response()->json(['user' => $user], 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
             // Handle general errors
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Server error occurred. Please try again later.'], 500);
        }
        
    }
}
