<?php

use App\Http\Controllers\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;


// Permission Routes
Route::prefix('permissions')->middleware('auth:api')->group(function () {
    Route::post('/', [PermissionController::class, 'create'])->name('api.permissions.create');
    Route::put('/{permissionId}', [PermissionController::class, 'update'])->name('api.permissions.update');
    Route::delete('/{permissionId}', [PermissionController::class, 'delete'])->name('api.permissions.delete');
    Route::get('/', [PermissionController::class, 'getList'])->name('api.permissions.list');
});
