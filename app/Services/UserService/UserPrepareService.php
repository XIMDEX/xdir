<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Services\MailService;
use App\Services\UuidService;
use Exception;
use Illuminate\Contracts\Hashing\Hasher;
use Ramsey\Uuid\Uuid;

class UserPrepareService
{
    protected $hasher;
    protected $mailService;
    protected $uuidService;

    public function __construct(Hasher $hasher,MailService $mailService,UuidService $uuidService)
    {
        $this->hasher = $hasher;
        $this->mailService = $mailService;
        $this->uuidService = $uuidService;
    }

    public function prepareUserRegistration(array $userData)
    {
        try {
            $user = $this->buildUserArray($userData);
            $encodedUser = $this->encodeUser($user);
            $this->mailService->sendUserDetails($userData['email'], env('APP_NAME') .':5173/email_verification/register/'. $encodedUser);
            return $encodedUser;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    protected function buildUserArray(array $userData): array
    {
        return [
            'uuid' => $this->uuidService->generateUuid(),
            'name' => $userData['name'],
            'surname' => $userData['surname'],
            'birthdate' => $userData['birthdate'] ?? null,
            'email' => $userData['email'],
            'password' => $this->hasher->make($userData['password']),
            'organization_id' => $userData['organization_id'] ?? null,
        ];
    }

    protected function encodeUser(array $user): string
    {
        return base64_encode(json_encode($user));
    }
}
