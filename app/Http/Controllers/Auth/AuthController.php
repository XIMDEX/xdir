<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Services\UserService\UserPrepareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    protected $userService;

    protected $userPrepareService;

    public function __construct(UserService $userService,UserPrepareService $userPrepareService)
    {
      $this->userService = $userService;
      $this->userPrepareService = $userPrepareService;
    }
    public function register(RegisterRequest $request)
    {
        try {
           $user = $this->userPrepareService->prepareUserRegistration($request->validated());
           if ($user) {
            return response()->json(['message' => $user], Response::HTTP_CREATED);
           }else{
            return response()->json(['error' => "User could not been created"], Response::HTTP_INTERNAL_SERVER_ERROR);
           }
          
        } catch (\Exception $e) {
            // Log the error internally
            \Log::error($e);
            // Return a JSON response with the error message and a 500 status code
            return response()->json(['error' => 'An error occurred while registering the user. Please try again later.' . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userService->getUser($request->only('email', 'password'));
            if($user){
                $user->makeHidden(['created_at', 'updated_at']);
                return response()->json(['user' => $user], Response::HTTP_CREATED);
            }
            return response()->json(['error' => 'Login failed'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
             // Handle general errors
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Server error occurred. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
           return response()->json(['message' => 'Token is invalid'], Response::HTTP_UNAUTHORIZED);
       }
   }
}
