<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Ramsey\Uuid\Uuid;

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
        // Generate a UUID
        $uuid = Uuid::uuid4();

        // Create the user with the UUID
        $user = User::create([
            'uuid' => $uuid,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hasher->make($data['password'])
        ]);

        $user->token = $user->createToken('ximdex')->accessToken;
        return $user;
    }

    public function getUser(array $data)
    {
        $this->auth->attempt($data);
        $user = $this->auth->user();
        $user->token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;
        return $user;
    }

    public function updateUser(array $data)
    {
        try {

            $user = $this->auth->user();

            if (isset($data['email']) && $this->checkEmail($data, $user->email)) {
                $user->email = $data['email'];
            } elseif (isset($data['email'])) {
                return response()->json(['error' => 'Email already in use'], 409);
            }

            // Update other user properties if they are included in the update request
            if (isset($data['name'])) {
                $user->name = $data['name'];
            }
            if (isset($data['password'])) {
                $user->password = $this->hasher->make($data['password']);
            }

            // Save the updated user
            $user->save();

            // Generate a new access token for the user
            $user->token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;

            return $user;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating user'], 500);
        }
    }

    protected function checkEmail(array $data, string $email)
    {
        // Check if the email is included in the update request
        if (isset($data['email'])) {
            // Check if the new email is different from the current email
            if ($data['email'] !== $email) {
                // Check if the new email already exists in the database
                $existingUser = User::where('email', $data['email'])->first();
                if ($existingUser) {
                    // Handle the case where the new email already exists
                    return false;
                }
                // Update the email if it is different and not already in use
                return true;
            }
        }
        return false;
    }
}
