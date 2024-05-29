<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;


// User Routes
Route::middleware('auth:api')->group(function () {
    Route::put('/user/update', [UserUpdateController::class, 'update'])->name('api.user.update');
    Route::get('/users', [UserController::class, 'listUsers'])->name('api.users.list');
});
