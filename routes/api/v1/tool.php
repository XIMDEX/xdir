<?php

use App\Http\Controllers\Tools\ToolController;
use Illuminate\Support\Facades\Route;

Route::prefix('services')->middleware('auth:api')->group(function () {
    Route::get('/', [ToolController::class, 'getList'])->name('api.tools.getList');
});

Route::prefix('services')->middleware(['auth:api','role:admin|superadmin'])->group(function () {
    Route::get('/create-user-on-service/{user}/{serviceId}', [ToolController::class, 'createUserOnService'])->name('api.tools.createUserOnService');
});