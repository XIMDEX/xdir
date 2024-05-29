<?php

use App\Http\Controllers\ResetPass\ForgotPasswordController;
use App\Http\Controllers\ResetPass\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Password Reset Routes
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
