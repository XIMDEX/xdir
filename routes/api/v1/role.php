<?php

use App\Http\Controllers\Roles\AssignRoleController;
use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;


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
