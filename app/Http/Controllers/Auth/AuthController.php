<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
    try {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;
       
        return response()->json(['token' => $token],201);
    } catch (\Exception $e) {
        dd($e);
          // Log the error internally
        \Log::error($e);

        // Return a JSON response with the error message and a 500 status code
        return response()->json(['error' => 'An error occurred while registering the user. Please try again later.'.$e], 500);
    }
}
}
