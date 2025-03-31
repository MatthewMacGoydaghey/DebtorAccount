<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function ()
{
    Route::post("/register", [AuthController::class, 'Register']);
    Route::post("/login", [AuthController::class, 'Login']);
    Route::middleware('auth:sanctum')->group(function ()
    {
        Route::post("/logout", [AuthController::class, 'Logout']);
        Route::get("/me", [AuthController::class, 'GetUserInfo']);
        Route::post('/change_password', [AuthController::class, 'changePassword']);

        Route::prefix('reset_password')->group(function () {
            Route::get('/', [AuthController::class, 'sendPasswordResetEmail']);
            Route::post('/', [AuthController::class, 'verifyPasswordReset']);
        });
    });
    
    Route::prefix('email_verify')->group(function () {
        Route::get('/', [AuthController::class, 'sendEmailVerification']);
        Route::get('/{token}', [AuthController::class, 'verifyEmail']);
    });
});

