<?php

namespace App\Services;

use App\Mail\UserDetailMail;
use App\Mail\UserDetailMailInterface;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class MailService
{

    protected $mailer;
    protected $userDetailMail;

    // Inject the Mailer contract (interface) into the service.
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendUserDetails($email, $encodedUser)
    {
        $this->mailer->to($email)->send(new UserDetailMail($encodedUser));
    }
}