<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Organization\OrganizationController;
use App\Http\Controllers\Organization\OrganizationInviteController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\ResetPass\ForgotPasswordController;
use App\Http\Controllers\ResetPass\ResetPasswordController;
use App\Http\Controllers\Roles\AssignRoleController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserUpdateController;
use App\Http\Controllers\verification\VerificationController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::get( '/email/verify/{code}',[VerificationController::class,'verify'])->name('api.verify');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::put('/user/update',[UserUpdateController::class,'update'])->name('api.user.update')->middleware('auth:api');
Route::get('/users', [UserController::class, 'listUsers'])->name('api.users.list')->middleware('auth:api');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');


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
Route::post('/role/unassign/permission', [AssignRoleController::class, 'revokePermissionFromRole'])->name('api.roles.remove.permission')->middleware('auth:api');

Route::post('/organization/create', [OrganizationController::class, 'create'])->name('api.organization.create')->middleware('auth:api');
Route::post('/organization/update/{organization}',[OrganizationController::class,'update'])->name('api.organization.update')->middleware('auth:api');
//Route::post( '/organization/delete/{organization}', [OrganizationController::class, 'delete'])->name('api.organization.delete')->middleware('auth:api');
Route::get('/organizations', [OrganizationController::class, 'listOrganizations'])->name('api.organizations.list')->middleware('auth:api');
Route::post('organization/invite/{organization}/{email}',[OrganizationInviteController::class,'sendInvite'])->name('api.organization.invite')->middleware('auth:api');

//Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
//Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

/**
 * // Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Email Verification Routes
Route::get('/email/verify/{code}', [VerificationController::class, 'verify'])->name('api.verify');

// Password Reset Routes
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

// User Routes
Route::middleware('auth:api')->group(function () {
    Route::put('/user/update', [UserUpdateController::class, 'update'])->name('api.user.update');
    Route::get('/users', [UserController::class, 'listUsers'])->name('api.users.list');
});

// Permission Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/permissions', [PermissionController::class, 'create'])->name('api.permissions.create');
    Route::put('/permissions/{permissionId}', [PermissionController::class, 'update'])->name('api.permissions.update');
    Route::delete('/permissions/{permissionId}', [PermissionController::class, 'delete'])->name('api.permissions.delete');
    Route::get('/permissions', [PermissionController::class, 'getList'])->name('api.permissions.list');
});

// Role Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/roles', [RoleController::class, 'create'])->name('api.roles.create');
    Route::put('/roles/{roleId}', [RoleController::class, 'update'])->name('api.roles.update');
    Route::delete('/roles/{roleId}', [RoleController::class, 'remove'])->name('api.roles.delete');
    Route::get('/roles', [RoleController::class, 'getList'])->name('api.roles.list');
    Route::post('/roles/assign', [AssignRoleController::class, 'assignRoleToUser'])->name('api.roles.assign');
    Route::post('/roles/unassign', [AssignRoleController::class, 'unassignRole'])->name('api.roles.unassign');
    Route::post('/roles/assign/permission', [AssignRoleController::class, 'addPermissionToRole'])->name('api.roles.add.permission');
    Route::post('/roles/unassign/permission', [AssignRoleController::class, 'revokePermissionFromRole'])->name('api.roles.remove.permission');
});

// Organization Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/organizations', [OrganizationController::class, 'create'])->name('api.organizations.create');
    Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('api.organizations.update');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('api.organizations.delete');
    Route::get('/organizations', [OrganizationController::class, 'listOrganizations'])->name('api.organizations.list');
    Route::post('/organizations/{organization}/invite/{email}', [OrganizationInviteController::class, 'sendInvite'])->name('api.organizations.invite');
});
 */