<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Services\OrganizationUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class OrganizationUserController extends Controller
{
    private $organizationUserService;
    public function __construct(OrganizationUserService $organizationUserService){
        $this->organizationUserService = $organizationUserService;
    }
    public function getUserListByOrganization($uuid){
        try {
            $organization = Organization::find($uuid);
            if (!$organization) {
                return response()->json(['error' => 'Organization not found'], Response::HTTP_NOT_FOUND);
            }
            $users = $this->organizationUserService->getUsersByOrganization($organization);
            $users = $users->map(function($user) {
                return [
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'uuid' => $user->uuid
                ];
            });
            return response()->json($users, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error getting user list by organization: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while getting user list by organization'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
