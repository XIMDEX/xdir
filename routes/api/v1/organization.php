<?php

use App\Http\Controllers\Organization\OrganizationController;
use App\Http\Controllers\Organization\OrganizationInviteController;
use Illuminate\Support\Facades\Route;


// Organization Routes
Route::prefix('organizations')->middleware('auth:api')->group(function () {
    Route::post('/', [OrganizationController::class, 'create'])->name('api.organizations.create');
    Route::put('/{organization}', [OrganizationController::class, 'update'])->name('api.organizations.update');
    Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('api.organizations.delete');
    Route::get('/', [OrganizationController::class, 'listOrganizations'])->name('api.organizations.list');
    Route::post('/{organization}/invite/{email}', [OrganizationInviteController::class, 'sendInvite'])->name('api.organizations.invite');
    Route::get('/invitations', [OrganizationInviteController::class, 'invitationList'])->name('api.organizations.invites.list');
});
