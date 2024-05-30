<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInviteRequest;
use App\Mail\OrganizationInviteMail;
use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class OrganizationInviteController extends Controller
{
    public function sendInvite(SendInviteRequest $request)
       {
           try {
               $email = $request->route('email');
               $organization = Organization::where('uuid', $request->route('organization'))->firstOrFail();
               $inviteLink = "organization={$organization->uuid}&email=$email";

               Invitation::create([
                'uuid' => Uuid::uuid4(),
                'email' => $email,
                'organization_id' => $organization->uuid,
                'status' => 'pending'
                ]);

               Mail::to($email)->send(new OrganizationInviteMail($organization->name, $inviteLink));

               return response()->json(['message' => 'Invitation sent successfully!'], Response::HTTP_OK);
           } catch (\Exception $e) {
               \Log::error('Error sending invitation: ' . $e->getMessage());
               return response()->json(['error' => 'An error occurred while sending the invitation'], Response::HTTP_INTERNAL_SERVER_ERROR);
           }
       }
}
