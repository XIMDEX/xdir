<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;


class UserUpdateController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateUser($userId, array $userData)
    {
        try {
            // Call the UserService method to update the user
            $user = $this->userService->updateUser($userId, $userData);

            // Return a JSON response with the updated user data
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            // Log the error internally
            \Log::error($e);
            // Return a JSON response with the error message and a 500 status code
            return response()->json(['error' => 'An error occurred while updating the user. Please try again later.'], 500);
        }
    }
}
