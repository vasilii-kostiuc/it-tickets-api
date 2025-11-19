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

    });
});
