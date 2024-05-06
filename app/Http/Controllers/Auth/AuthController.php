<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);


    $user = User::create([
        'name' => $request['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($request['password']),
    ]);

    $token = $user->createToken(env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))->accessToken;

    \Log::info('Token generated', ['token' => $token]);

    return response()->json(['token' => $token]);
    }
}
