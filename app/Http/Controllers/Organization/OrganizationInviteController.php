<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInviteRequest;
use App\Mail\OrganizationInviteMail;
use App\Models\Invitation;
use App\Models\Organization;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class OrganizationInviteController extends Controller
{

    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }
    
    public function sendInvite(SendInviteRequest $request)
       {
           try {
            $email = $request->route('email');
            $organizationUuid = $request->route('organization');
            
            $result = $this->invitationService->sendInvitation($email, $organizationUuid);
    
            return response()->json($result, $result['status']);
           } catch (\Exception $e) {
               \Log::error('Error sending invitation: ' . $e->getMessage());
               return response()->json(['error' => 'An error occurred while sending the invitation'], Response::HTTP_INTERNAL_SERVER_ERROR);
           }
       }

    public function invitationList(){
        return response()->json(['invitations' => Invitation::all()]);
    }
}
