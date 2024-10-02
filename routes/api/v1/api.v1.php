<?php

   use Illuminate\Support\Facades\Route;

   // Include separate route files
   require __DIR__ . '/auth.php';
   require __DIR__ . '/email_verification.php';
   require __DIR__ . '/password_reset.php';
   require __DIR__ . '/user.php';
   require __DIR__ . '/permission.php';
   require __DIR__ . '/role.php';
   require __DIR__ . '/organization.php';
   require __DIR__ . '/tool.php';