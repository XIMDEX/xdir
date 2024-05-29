<?php

namespace App\Services;

use App\Mail\UserDetailMail;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendUserDetails($email, $encodedUser)
    {
        Mail::to($email)->send(new UserDetailMail($encodedUser));
    }
}