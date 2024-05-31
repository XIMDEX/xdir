<?php

// Role Routes

use App\Http\Controllers\Tools\ToolController;
use Illuminate\Support\Facades\Route;

Route::prefix('tools')->middleware('auth:api')->group(function () {
    Route::get('/', [ToolController::class, 'getList'])->name('api.tools.getList');
});