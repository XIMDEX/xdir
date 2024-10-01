<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class OrganizationUserService
{


    public function getUserOrganizations(User $user): Collection
    {
        try {
            $organizations = $user->organizations;
            return $organizations;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getUsersByOrganization(Organization $organization): Collection
    {
        try {
            $users = $organization->users;
            return $users;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param Organization $organization
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function addUserToOrganization(Organization $organization, User $user): JsonResponse
    {
        try {
            $organization->users()->attach($user->uuid);
            return response()->json(['message' => 'User added to organization successfully'], 200);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param Organization $organization
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function removeUserFromOrganization(Organization $organization, User $user): JsonResponse
    {
        try {
            $organization->users()->detach($user->id);
            return response()->json(['message' => 'User removed from organization successfully'], 200);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }
}
