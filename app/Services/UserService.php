<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;

class UserService
{

    protected $auth;
    protected $hasher;

    public function __construct(Guard $auth, Hasher $hasher)
    {
        $this->auth = $auth;
        $this->hasher = $hasher;
    }
    
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hasher->make($data['password'])
        ]);

        $user->token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;
        return $user;
    }

    public function getUser(array $data)
    {
        $this->auth->attempt($data);;
        $user = $this->auth->user();
        $user->token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;
        return $user;
    }
}
