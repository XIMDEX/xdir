<?php

use App\Http\Controllers\ResetPass\ForgotPasswordController;
use App\Http\Controllers\ResetPass\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Password Reset Routes
Route::prefix('password')->group(function () {
    Route::post('email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
});
