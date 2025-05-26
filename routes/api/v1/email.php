<?php

use App\Http\Controllers\Email\SendEmailController;
use App\Http\Controllers\Email\TemplateController;
use Illuminate\Support\Facades\Route;

// Email Routes
Route::prefix('email')->middleware('auth:api')->group(function () {
    // Routes for sending emails
    Route::post('/send', [SendEmailController::class, 'sendEmail'])->name('api.email.send');
    Route::post('/send-bulk', [SendEmailController::class, 'sendBulkEmail'])->name('api.email.send.bulk');
    
    // Routes for email templates
    Route::get('/templates', [TemplateController::class, 'getTemplates'])->name('api.email.templates');
    Route::get('/templates/{template}', [TemplateController::class, 'getTemplate'])->name('api.email.template');
});
