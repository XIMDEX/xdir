<?php

namespace App\Http\Controllers\verification;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
   // use VerifiesEmails;

    protected $userService;

    public function __construct(UserService $userService)
    {
      $this->userService = $userService;
    }


    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(string $code)
    {
        if (!json_decode(base64_decode($code))) {
            return response()->json(['error' => 'Invalid code.'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $user = $this->userService->registerUser(json_decode(base64_decode($code)));
            if($user->organizations()->count() > 0) {
                $invitation = Invitation::where('email', $user->email)->first();
                if(!$invitation) {
                    $user->delete();
                    return response()->json(['error' => 'Invitation not found.'], Response::HTTP_NOT_FOUND);
                }
                $invitation->status = 'completed';
                $invitation->save();
            }
            return response()->json(["message" => "Email has been verified."], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Handle general errors
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Server error occurred. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(["message" => "Email already verified."], Response::HTTP_OK);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(["message" => "Verification link sent."], Response::HTTP_OK);
    }
}
