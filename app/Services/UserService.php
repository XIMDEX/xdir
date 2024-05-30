<?php

namespace App\Services;

use App\Mail\UserDetailMail;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class UserService
{

    protected $auth;
    protected $hasher;
    protected $uuid;
    protected $user;

    public function __construct(Guard $auth, Hasher $hasher, User $user)
    {
        $this->auth = $auth;
        $this->hasher = $hasher;
        $this->user = $user;
    }

    /**
     * Creates a new user and sends an email with their details
     *
     * @param array $userData
     * @return string|false
     */
    public function createUser(array $userData)
    {
        try {
            $userId = Uuid::uuid4();
            $user = [
                'uuid' => $userId,
                'name' => $userData['name'],
                'surname' => $userData['surname'],
                'birthdate' => $userData['birthdate'] ?? null,
                'email' => $userData['email'],
                'password' => $this->hasher->make($userData['password']),
                'organization_id' => array_key_exists('organization', $userData) ? $userData['organization'] : null,
            ];

            $jsonUser = json_encode($user);
            $base64User = base64_encode($jsonUser);

            Mail::to($userData['email'])->send(new UserDetailMail($base64User));

            return $base64User;
        } catch (Exception $e) {
            return false;
        }
    }


    public function registerUser($data){
        $user = $this->user->create(get_object_vars($data));
        $user->markEmailAsVerified();
        $user->access_token = $user->createToken('ximdex')->accessToken;
        if (isset($data->organization_id)) {
            $user->organizations()->attach($data->organization_id);
        }
        return $user;
    }

    public function getUser(array $data)
    {
        try {
            $this->auth->attempt($data);
            $user = $this->auth->user();
            if ($user) {
                $user->access_token = $user->createToken('ximdex')->accessToken;
                if ($user->roles()->exists()){
                    $rolesGroupedByOrganization = $user->roles()->get()->makeHidden('pivot')
                    ->groupBy('organization_id')
                    ->map(function ($roles) {
                        return $roles->map(function ($role) {  
                            return $role->only('role_id', 'name'); 
                        });
                    });
                
                
                    $user->roles = $rolesGroupedByOrganization;
                }
               
                return $user;
            }
            return null;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

            if (isset($data['surname'])) {
                $user->surname = $data['surname'];
            }
            if (isset($data['password'])) {
                $user->password = $this->hasher->make($data['password']);
            }

            if (isset($data['birthdate'])) {
                $user->birthdate = $data['birthdate'];
            }

            $user->save();

            $user->access_token = $user->createToken(env('PASSPORT_TOKEN_NAME'))->accessToken;

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

    public function getAllUsers()
    {
        return User::all();
    }

    public function addUserToOrganization($user, $organization)
    {
        $user->organizations()->attach($organization->id);
    }
}
