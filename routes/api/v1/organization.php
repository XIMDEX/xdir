<?php

use App\Http\Controllers\Organization\OrganizationController;
use App\Http\Controllers\Organization\OrganizationInviteController;
use Illuminate\Support\Facades\Route;

$missingCallback = function () {
    return response()->json(['error' => 'Not found'], 404);
};

// Organization Routes
Route::prefix('organizations')->middleware(['auth:api', 'role:admin|superadmin'])->group(function () use ($missingCallback) {
    Route::get('/', [OrganizationController::class, 'listOrganizations'])->name('api.organizations.list');
    Route::post('/{organization}/invite/{email}', [OrganizationInviteController::class, 'sendInvite'])->name('api.organizations.invite')->missing($missingCallback);
    Route::get('/invitations', [OrganizationInviteController::class, 'invitationList'])->name('api.organizations.invites.list');
    Route::delete('/invitations/{uuid}', [OrganizationInviteController::class, 'delete'])->name('api.organizations.invites.delete')->missing($missingCallback);
});

Route::prefix('organizations')->middleware(['auth:api', 'role:superadmin'])->group(function () use ($missingCallback) {
    Route::post('/', [OrganizationController::class, 'create'])->name('api.organizations.create');
    Route::put('/{organization}', [OrganizationController::class, 'update'])->name('api.organizations.update')->missing($missingCallback);
    Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('api.organizations.delete')->missing($missingCallback);
});