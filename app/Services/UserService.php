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
    private $rolesBitwiseMap = [
        'viewer' => '11100000',
        'creator' => '11110000',
        'editor' => '11111100',
        'admin' => '11111110',
        'superadmin' => '11111111',
    ];

    /**
     * Constructs a new instance of the class.
     *
     * @param Guard $auth The authentication service.
     * @param Hasher $hasher The hashing service.
     * @param User $user The user model.
     */
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


    public function registerUser($data)
    {
        $user = $this->user->create(get_object_vars($data));
        $user->markEmailAsVerified();
        $user->access_token = $user->createToken('ximdex')->accessToken;
        if (isset($data->organization_id)) {
            $user->organizations()->attach($data->organization_id);
        }
        return $user;
    }

    /**
     * Retrieves a user by their login credentials.
     *
     * @param array $data The login credentials, including email and password.
     * @throws Exception If an error occurs while retrieving the user.
     * @return User|null The user object if found, or null if not found.
     */
    public function getUserByLogin(array $data)
    {
        try {
            $this->auth->attempt($data);
            $user = $this->auth->user();
            if ($user) {
                $user->access_token = $user->createToken('ximdex')->accessToken;
                $user->p = $this->getUserToolRoles($user);
                $user->organizations = $user->organizations()->pluck('name', 'uuid')->toArray();
                return $user;
            }
            return null;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get user by id.
     *
     * @param string $id User id
     * @return User|null
     */
    public function getUserById(string $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->p = $this->getUserToolRoles($user);
            $user->organizations = $user->organizations()->pluck('name', 'uuid')->toArray();
            return $user;
        } catch (Exception $e) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Updates the user's information based on the provided data.
     *
     * @param array $data The data containing the updated user information.
     *                    The following keys are supported:
     *                    - email: The new email address.
     *                    - name: The new name.
     *                    - surname: The new surname.
     *                    - password: The new password.
     *                    - birthdate: The new birthdate.
     * @throws \Exception If an error occurs while updating the user.
     * @return \Illuminate\Http\JsonResponse|User The updated user object or a JSON response with an error message.
     */
    public function updateUser(array $data)
    {
        try {

            $user = $this->auth->user();

            if (isset($data['email']) && $this->checkEmail($data, $user->email)) {
                $user->email = $data['email'];
            }

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
            return response()->json(['error' => 'An error occurred while updating user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Deletes a user by their ID.
     *
     * @param int $id The ID of the user to be deleted.
     * @throws \Exception If an error occurs while deleting the user.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse The deleted user, or a JSON response with an error message.
     */
    public function deleteUser($id)
    {
        try {
            $user = $this->user->find($id);
            $user->delete();
            return $user;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting user'], Response::HTTP_INTERNAL_SERVER_ERROR);
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

    /**
     * Retrieves all users filtered by the given organizations.
     *
     * @param int $page The page number to retrieve (default: 1).
     * @param array $organizations The array of organization UUIDs to filter by.
     * @return array The custom result containing the total, last item, current page, and data.
     */
    public function getAllUsersFilterByOrganization($page = 1, $organizations)
    {
        $paginationResult = User::whereHas('organizations', function ($query) use ($organizations) {
            $query->whereIn('organization_uuid', $organizations);
        })->paginate(20, ['*'], 'page', $page);

        $customResult = [
            'total'        => $paginationResult->total(),
            'to'           => $paginationResult->lastItem(),
            'current_page' => $paginationResult->currentPage(),
            'data'         => $paginationResult->items(),
        ];

        return $customResult;
    }

    /**
     * Adds a user to an organization.
     *
     * @param mixed $user The user object to be added to the organization.
     * @param mixed $organization The organization object to which the user will be added.
     * @throws \Exception If there is an error attaching the user to the organization.
     * @return void
     */
    public function addUserToOrganization($user, $organization)
    {
        $user->organizations()->attach($organization->id);
    }


    /**
     * Retrieves the user's tool roles.
     *
     * @param User $user The user object.
     * @return array|null Returns an array of user tool roles, or null if the user has no roles.
     */
    private function getUserToolRoles($user)
    {
        if ($user->roles()->exists()) {
            $userToolRoles = [];
            $roles = $user->roles->load('tools');
            foreach ($roles as $role) {
                $userToolRoles[$role->tools->first()->hash] = [
                    'organization' => $role->pivot->organization_id,
                    'permission' => $this->rolesBitwiseMap[strtolower($role->name)],
                    'role' => $role->name,
                    'tool' => ['name' => $role->tools->first()->name, 'type' => $role->tools->first()->type]
                ];
            }
            return $userToolRoles;
        }
        return null;
    }
}
