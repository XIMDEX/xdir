<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;


// User Routes
Route::group(['middleware' => ['auth:api', 'role:admin|superadmin'], 'prefix' => 'users'], function () {
    Route::put('/{id}', [UserUpdateController::class, 'update'])->name('api.users.update');
    Route::get('/{id}', [UserController::class, 'getUser'])->name('api.users.get');
    Route::get('/', [UserController::class, 'listUsers'])->name('api.users.list');
    Route::delete('/{id}', [UserController::class, 'deleteUser'])->name('api.users.delete');
});
