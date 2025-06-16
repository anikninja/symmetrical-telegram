<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/auth/guest/login', [LoginController::class, 'guestLogin']);
Route::post('/auth/login', [LoginController::class, 'appleLogin'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
});
