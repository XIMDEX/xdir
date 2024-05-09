<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::put('/user/update',[UserUpdateController::class,'update'])->name('api.user.update')->middleware('auth:api');

Route::post('/permission/create',[PermissionController::class,'create'])->name('api.permission.create')->middleware('auth:api');
Route::post('/permission/update/{permissionId}',[PermissionController::class,'update'])->name('api.permission.update')->middleware('auth:api');
Route::get('/permissions', [PermissionController::class, 'getList'])->name('api.permissions.list')->middleware('auth:api');
