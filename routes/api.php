<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post('auth/register', \App\Http\Controllers\Api\V1\Auth\RegisterController::class);
    Route::post('auth/login', \App\Http\Controllers\Api\V1\Auth\LoginController::class);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('auth/profile', [\App\Http\Controllers\Api\V1\Auth\ProfileController::class, 'show'])->name('profile.show');
        Route::post('auth/profile', [\App\Http\Controllers\Api\V1\Auth\ProfileController::class, 'update'])->name('profile.update');

        Route::apiResource('users', \App\Http\Controllers\Api\V1\User\UserController::class);

        Route::delete('permissions/batch-delete', [\App\Http\Controllers\Api\V1\Rbac\PermissionController::class, 'batchDelete']);
        Route::apiResource('permissions', \App\Http\Controllers\Api\V1\Rbac\PermissionController::class);

        Route::delete('roles/batch-delete', [\App\Http\Controllers\Api\V1\Rbac\RoleController::class, 'batchDelete']);
        Route::apiResource('roles', \App\Http\Controllers\Api\V1\Rbac\RoleController::class);
        Route::put('roles/{role}/permissions', [\App\Http\Controllers\Api\V1\Rbac\RoleController::class, 'updatePermissions']);
    });

    // Asterisk API (protected by API token)
    Route::prefix('asterisk')->middleware('api.token')->group(function () {
        Route::post('call', [\App\Http\Controllers\Api\V1\Asterisk\AsteriskController::class, 'call']);
    });
});
