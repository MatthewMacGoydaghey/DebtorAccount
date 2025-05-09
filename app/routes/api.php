<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function ()
{
    Route::post("/register", [AuthController::class, 'Register']);
    Route::post("/login", [AuthController::class, 'Login']);
    Route::middleware('throttle:1,1')->post("/phone_verification", [AuthController::class, 'SendPhoneVerificationCode']);
    Route::middleware('auth:sanctum')->group(function ()
    {
        Route::post("/logout", [AuthController::class, 'Logout']);
        Route::get("/me", [AuthController::class, 'GetUserInfo']);
        Route::post('/change_password', [AuthController::class, 'ChangePassword']);

        Route::prefix('reset_password')->group(function () {
            Route::get('/', [AuthController::class, 'SendPasswordResetEmail']);
            Route::post('/', [AuthController::class, 'VerifyPasswordReset']);
        });

        Route::prefix("loans")->group(function () {
            Route::get("/", [LoanController::class, 'GetLoans']);
            Route::get("/statuses", [LoanController::class, 'GetStatuses']);
            Route::get("/event_types", [LoanController::class, 'GetEventTypes']);
            Route::get("/{loanId}", [LoanController::class, 'GetLoan']);
            Route::get("/{loanId}/events", [LoanController::class, 'GetEvents']);
            Route::get("/{loanId}/events/{eventId}", [LoanController::class, 'GetEvent']);
            Route::get("/{loanId}/payments", [LoanController::class, 'GetPayments']);
            Route::get("/{loanId}/payments/{paymentId}", [LoanController::class, 'GetPayment']);
        });
    });
    
    Route::prefix('email_verify')->group(function () {
        Route::get('/', [AuthController::class, 'SendEmailVerification']);
        Route::get('/{token}', [AuthController::class, 'VerifyEmail']);
    });
});

