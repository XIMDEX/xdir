<?php

use App\Http\Controllers\Organization\OrganizationController;
use App\Http\Controllers\Organization\OrganizationInviteController;
use Illuminate\Support\Facades\Route;


// Organization Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/organizations', [OrganizationController::class, 'create'])->name('api.organizations.create');
    Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('api.organizations.update');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('api.organizations.delete');
    Route::get('/organizations', [OrganizationController::class, 'listOrganizations'])->name('api.organizations.list');
    Route::post('/organizations/{organization}/invite/{email}', [OrganizationInviteController::class, 'sendInvite'])->name('api.organizations.invite');
});
