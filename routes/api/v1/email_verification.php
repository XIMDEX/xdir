<?php

use App\Http\Controllers\verification\VerificationController;
use Illuminate\Support\Facades\Route;


// Email Verification Routes
Route::get('/email/verify/{code}', [VerificationController::class, 'verify'])->name('api.verify');
