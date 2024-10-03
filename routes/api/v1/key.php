<?php

use App\Http\Controllers\keyController;
use Illuminate\Support\Facades\Route;

Route::prefix('key')->middleware(['auth:api', 'role:admin|superadmin'])->group(function () {
    Route::get('/public', [keyController::class, 'getPublicKey']);
});