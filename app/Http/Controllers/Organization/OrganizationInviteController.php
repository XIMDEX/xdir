<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInviteRequest;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use App\Services\InvitationService;
use App\Services\OrganizationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class OrganizationInviteController extends Controller
{

    protected $invitationService;
    protected $organizationService;

    public function __construct(InvitationService $invitationService, OrganizationService $organizationService)
    {
        $this->invitationService = $invitationService;
        $this->organizationService = $organizationService;
    }

    public function sendInvite(SendInviteRequest $request, Organization $organization)
    {
        try {
            $email = $request->route('email');
            $user = User::where('email', $email)->first();

            if ($user) {
                $result = $this->organizationService->addUserToOrganization($organization->uuid, $user->uuid);
                return response()->json($result, $result['status']);
            }
            $result = $this->invitationService->sendInvitation($email, $organization->uuid, $organization->name);

            return response()->json($result, $result['status']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Organization not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Error sending invitation: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending the invitation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function invitationList()
    {
        return response()->json(['invitations' => Invitation::all()]);
    }

    public function delete ($uuid)
    {
        try {
          $this->invitationService->delete($uuid);
          return response()->json(['message' => 'Invitation deleted successfully!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error deleting invitation: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the invitation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
