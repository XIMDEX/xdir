<?php

use App\Http\Controllers\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;


// Permission Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/permissions', [PermissionController::class, 'create'])->name('api.permissions.create');
    Route::put('/permissions/{permissionId}', [PermissionController::class, 'update'])->name('api.permissions.update');
    Route::delete('/permissions/{permissionId}', [PermissionController::class, 'delete'])->name('api.permissions.delete');
    Route::get('/permissions', [PermissionController::class, 'getList'])->name('api.permissions.list');
});
