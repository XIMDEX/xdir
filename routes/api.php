<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\Roles\AssignRoleController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::put('/user/update',[UserUpdateController::class,'update'])->name('api.user.update')->middleware('auth:api');

Route::post('/permission/create',[PermissionController::class,'create'])->name('api.permission.create')->middleware('auth:api');
Route::delete('/permission/remove/{permissionId}',[PermissionController::class,'delete'])->name('api.permission.delete')->middleware('auth:api');
Route::post('/permission/update/{permissionId}',[PermissionController::class,'update'])->name('api.permission.update')->middleware('auth:api');
Route::get('/permissions', [PermissionController::class, 'getList'])->name('api.permissions.list')->middleware('auth:api');

Route::post('/role/create',[RoleController::class,'create'])->name('api.roles.create')->middleware('auth:api');
Route::put('/role/update/{roleId}',[RoleController::class,'update'])->name('api.roles.update')->middleware('auth:api');
Route::delete('/role/remove/{roleId}', [RoleController::class, 'remove'])->name('api.roles.remove')->middleware('auth:api');
Route::get('/roles', [RoleController::class, 'getList'])->name('api.roles.list')->middleware('auth:api');
Route::post( '/role/assign', [AssignRoleController::class, 'assignRoleToUser'])->name('api.roles.assign')->middleware('auth:api');
Route::post( '/role/unassign', [AssignRoleController::class, 'unassignRole'])->name('api.roles.unassign')->middleware('auth:api');
Route::post('/role/assign/permission', [AssignRoleController::class, 'addPermissionToRole'])->name('api.roles.add.permission')->middleware('auth:api');
Route::post('/role/unassign/permission', [AssignRoleController::class, 'removePermissionFromRole'])->name('api.roles.remove.permission')->middleware('auth:api');

