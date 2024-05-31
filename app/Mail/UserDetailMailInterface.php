<?php

namespace App\Mail;

interface UserDetailMailInterface
{
    public function create($encodedUser);
}