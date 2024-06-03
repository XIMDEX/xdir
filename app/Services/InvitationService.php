<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Organization;
use App\Mail\OrganizationInviteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InvitationService
{
    public function sendInvitation($email, $organizationUuid)
    {
        try {
            $organization = Organization::where('uuid', $organizationUuid)->firstOrFail();
            $inviteLink = "organization={$organization->uuid}&email=$email";

            Invitation::create([
                'uuid' => Str::uuid(), 
                'email' => $email,
                'organization_id' => $organization->uuid, 
                'status' => 'pending'
            ]);

            Mail::to($email)->send(new OrganizationInviteMail($organization->name, $inviteLink));

            return ['message' => 'Invitation sent successfully!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            \Log::error('Error sending invitation: ' . $e->getMessage());
            return ['error' => 'An error occurred while sending the invitation', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}