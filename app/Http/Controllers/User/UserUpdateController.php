<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserUpdateController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateUser(Request $request)
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
}
