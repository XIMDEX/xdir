<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Send verification email
            //$user->sendEmailVerificationNotification(); 
           //$user->makeHidden(['created_at', 'updated_at'])
           
           return response()->json(['message' => "Emial sent"], 201);
        } catch (\Exception $e) {
            $user->delete();
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
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
             // Handle general errors
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Server error occurred. Please try again later.'], 500);
        }
        
    }

    public function update(Request $request)
    {
        $data = $request->all();
        
        $updatedUser = $this->userService->updateUser($data);
        if ($updatedUser instanceof JsonResponse) {
            return $updatedUser;
        }
        $updatedUser->makeHidden(['created_at', 'updated_at','uuid']);

        return response()->json(['user' => $updatedUser]);
    }

    public function validateToken(Request $request)
   {
       $user = Auth::guard('api')->user();
       
       if ($user) {
           return response()->json(['message' => 'Token is valid', 'user' => $user]);
       } else {
           return response()->json(['message' => 'Token is invalid'], 401);
       }
   }
}
