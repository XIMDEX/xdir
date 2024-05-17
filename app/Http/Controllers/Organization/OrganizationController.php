<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $result = $this->organizationService->createOrganization($request->all());

        if ($result['success']) {
            return response()->json($result['organization'], Response::HTTP_CREATED);
        } else {
            return response()->json(['error' => 'Failed to create organization'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($uuid)
    {
        $result = $this->organizationService->deleteOrganization($uuid);

        if ($result['success']) {
            return response()->json(['message' => $result['message']], Response::HTTP_OK);
        } else {
            return response()->json(['error' => $result['message']], Response::HTTP_NOT_FOUND);
        }
    }
}
