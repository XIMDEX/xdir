<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInviteRequest;
use App\Mail\OrganizationInviteMail;
use App\Models\Invitation;
use App\Models\Organization;
use App\Services\InvitationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class OrganizationInviteController extends Controller
{

    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function sendInvite(SendInviteRequest $request, Organization $organization)
    {
        $validator = Validator::make(['uuid' => $organization->uuid], [
            'uuid' => 'required|exists:organizations,uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $email = $request->route('email');

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
}
