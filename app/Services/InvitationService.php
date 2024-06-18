<?php

namespace App\Services;

use App\Models\Invitation;
use App\Mail\OrganizationInviteMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class InvitationService
{
    protected $uuidService;
    public function __construct()
    {
        $this->uuidService = new UuidService();
    }
    public function sendInvitation($email, $organizationUuid,$organizationName)
    {
        try {
            $inviteLink = "localhost:5173/register?organization={$organizationUuid}&email=$email";

            Invitation::create([
                'uuid' => $this->uuidService->generateUuid(), 
                'email' => $email,
                'organization_id' => $organizationUuid, 
                'status' => 'pending'
            ]);

            Mail::to($email)->send(new OrganizationInviteMail($organizationName, $inviteLink));

            return ['message' => 'Invitation sent successfully!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            \Log::error('Error sending invitation: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($uuid)
    {
        try {
            $invitation = Invitation::where('id', $uuid)->first();
            $invitation->delete();
            return ['message' => 'Invitation deleted successfully!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            \Log::error('Error deleting invitation: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}

