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


    private $rolesBitwiseMap = [
        'viewer' => '11100000',
        'creator' => '11110000',
        'editor' => '11111100',
        'admin' => '00000010',
        'superAdmin' => '00000001',
    ];

    /**
     * Constructs a new instance of the class.
     *
     * @param UserService $userService The UserService instance.
     * @param UserPrepareService $userPrepareService The UserPrepareService instance.
     */
    public function __construct(UserService $userService, UserPrepareService $userPrepareService)
    {
        $this->userService = $userService;
        $this->userPrepareService = $userPrepareService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The registration request.
     * @throws \Exception If an error occurs during registration.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the registered user or an error message.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->userPrepareService->prepareUserRegistration($request->validated());
            if ($user) {
                return response()->json(['message' => $user], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => "User could not been created"], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'An error occurred while registering the user. Please try again later.' . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logs in a user with the provided credentials.
     *
     * @param LoginRequest $request The login request containing the email and password.
     * @throws \Exception If an error occurs during the login process.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the logged-in user or an error message.
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userService->getUserByLogin($request->only('email', 'password'));
            if ($user) {
                $user->makeHidden(['created_at', 'updated_at', 'roles']);
                $cookie = cookie('access_token', $user->accessToken, 60); // 60 minutes
                return response()->json(['user' => $user], Response::HTTP_CREATED)->cookie($cookie);
            }
            return response()->json(['error' => 'Login failed'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Server error occurred. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Updates a user's information based on the provided request data.
     *
     * @param Request $request The HTTP request containing the user's updated information.
     * @return JsonResponse The JSON response containing the updated user object or an error message.
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();

            $updatedUser = $this->userService->updateUser($data);
            if ($updatedUser instanceof JsonResponse) {
                return $updatedUser;
            }
            $updatedUser->makeHidden(['created_at', 'updated_at', 'uuid']);

            return response()->json(['user' => $updatedUser]);
        } catch (\Exception $e) {
            // Log the error internally
            \Log::error($e);
            // Return a JSON response with the error message and a 500 status code
            return response()->json(['error' => 'An error occurred while updating the user. Please try again later.'], 500);
        }
    }

    /**
     * Validates the token for the 'api' guard user.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the message 'Token is valid' and the user object, or an error message.
     * @throws \Exception If an error occurs during the validation process.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the error message.
     */
    public function validateToken()
    {
        try {
            $user = Auth::guard('api')->user();

            if ($user) {
                return response()->json(['message' => 'Token is valid', 'user' => $user]);
            } else {
                return response()->json(['message' => 'Token is invalid'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'An error occurred while validating the token. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
