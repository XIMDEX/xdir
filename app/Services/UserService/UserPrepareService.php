<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Services\MailService;
use Exception;
use Illuminate\Contracts\Hashing\Hasher;
use Ramsey\Uuid\Uuid;

class UserCreationService
{
    protected $hasher;
    protected $mailService;

    public function __construct(Hasher $hasher,MailService $mailService)
    {
        $this->hasher = $hasher;
        $this->mailService = $mailService;
    }

    public function prepareUserRegistration(array $userData)
    {
        try {
            $user = $this->buildUserArray($userData);
            $encodedUser = $this->encodeUser($user);
            $this->sendUserDetailsEmail($userData['email'], $encodedUser);
            return $encodedUser;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    protected function buildUserArray(array $userData): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $userData['name'],
            'surname' => $userData['surname'],
            'birthdate' => $userData['birthdate'] ?? null,
            'email' => $userData['email'],
            'password' => $this->hasher->make($userData['password']),
            'organization_id' => $userData['organization'] ?? null,
        ];
    }

    protected function encodeUser(array $user): string
    {
        return base64_encode(json_encode($user));
    }

    protected function sendUserDetailsEmail(string $email, string $encodedUser): void
    {
        Mail::to($email)->send(new UserDetailMail($encodedUser));
    }
}
