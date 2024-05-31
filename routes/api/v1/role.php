<?php

use App\Http\Controllers\Roles\AssignRoleController;
use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;


// Role Routes
Route::prefix('roles')->middleware('auth:api')->group(function () {
    Route::post('/', [RoleController::class, 'create'])->name('api.roles.create');
    Route::put('/{roleId}', [RoleController::class, 'update'])->name('api.roles.update');
    Route::delete('/{roleId}', [RoleController::class, 'remove'])->name('api.roles.delete');
    Route::get('/', [RoleController::class, 'getList'])->name('api.roles.list');
    Route::post('/assign', [AssignRoleController::class, 'assignRoleToUser'])->name('api.roles.assign');
    Route::post('/unassign', [AssignRoleController::class, 'unassignRole'])->name('api.roles.unassign');
    Route::post('/assign/permission', [AssignRoleController::class, 'addPermissionToRole'])->name('api.roles.add.permission');
    Route::post('/unassign/permission', [AssignRoleController::class, 'revokePermissionFromRole'])->name('api.roles.remove.permission');
});
