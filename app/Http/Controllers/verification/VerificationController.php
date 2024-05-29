<?php

namespace App\Http\Controllers\verification;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
   // use VerifiesEmails;

    protected $userService;

    public function __construct(UserService $userService)
    {
      $this->userService = $userService;
    }

    /**
     * Show the email verification notice.
     *
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(string $code)
    {
        $this->userService->registerUser(json_decode(base64_decode($code)));
        

        return response()->json(["message" => "Email has been verified."], 200);
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
            return response()->json(["message" => "Email already verified."], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(["message" => "Verification link sent."], 200);
    }
}
