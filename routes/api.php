<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::put('/user/update',[AuthController::class,'update'])->name('api.user.update')->middleware('auth:api');

Route::post('/permission/create',[PermissionController::class,'create'])->name('api.permission.create')->middleware('auth:api');
//Route::post('/permission/remove',[PermissionController::class,'removePermission'])->name('api.permission.remove')->middleware('auth:api');
