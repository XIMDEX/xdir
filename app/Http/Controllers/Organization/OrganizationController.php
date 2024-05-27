<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function create(Request $request)
    {
        try {
            $result = $this->organizationService->createOrganization($request->all());

            if ($result['success']) {
                return response()->json($result['organization'], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => 'Failed to create organization'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            \Log::error('Error creating organization: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the organization'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Organization $organization){
        try {
            if ($organization) {
                $result = $this->organizationService->updateOrganization($organization, $request->all());
                if ($result['success']) {
                    return response()->json($result['organization'], Response::HTTP_OK);
                } else {
                    return response()->json(['error' => $result['message']], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return response()->json(['error' => 'Organization not found'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating organization: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the organization'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($uuid)
    {
        try {
            $result = $this->organizationService->deleteOrganization($uuid);
            if ($result['success']) {
                return response()->json(['message' => $result['message']], Response::HTTP_OK);
            } else {
                return response()->json(['error' => $result['message']], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting organization: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the organization'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listOrganizations()
    {
        try {
            $organizations = $this->organizationService->getAllOrganizations();
            return response()->json($organizations, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error listing organizations: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while listing organizations'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}