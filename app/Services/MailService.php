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
    public function __construct(Mailer $mailer,UserDetailMailInterface $userDetailMail)
    {
        $this->mailer = $mailer;
        $this->userDetailMail = $userDetailMail;
    }

    public function sendUserDetails($email, $encodedUser)
    {
        $mailable = $this->userDetailMail->create($encodedUser);
        $this->mailer->to($email)->send($mailable);
    }
}