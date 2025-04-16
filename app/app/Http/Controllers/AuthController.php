<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\RegRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Services\EmailService;
use App\Http\Services\PasswordService;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(
    protected UserService $userService,
    protected PasswordService $passwordService,
    protected EmailService $emailService,
    ) {}


    public function Register(RegRequest $request): JsonResponse
    {
        $this->userService->CreateUser($request->validated());
        return $this->JsonResponse(true, "Регистрация успешна");
    }


    public function Login(LoginRequest $request) : JsonResponse
    {
        $field = $request->email ? 'email' : 'phone';
        $user = $this->userService->FindUserByField($field, $request->email ?? $request->phone);
        $user->ValidatePassword($request->password);
        return $this->JsonResponse(true, "Аутентификация успешна", $user->createToken("T")->plainTextToken);
    }


    public function GetUserInfo(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(new UserResource($user));
    }


    public function Logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->JsonResponse(true, "Вы успешно вышли");
    }


    public function ChangePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->passwordService->ChangePassword($request->user(), $request->password);
        return $this->JsonResponse(true, 'Пароль изменён, авторизуйтесь заново');
    }


    public function SentEmailVerification(VerifyEmailRequest $request): JsonResponse
    {
        $user = $this->userService->FindUserByField("email", $request->email);
        $this->emailService->SentVerificationEmail($user); 
        return $this->JsonResponse(true, 'Письмо для верификации почты отправлено');
    }


    public function VerifyEmail(VerifyEmailRequest $request): RedirectResponse
    {
        $user = $this->userService->FindUserByField("email", $request->email);

        if ($this->emailService->TryVerifyEmail($user, $request->token))
        return response()->redirectTo(config("app.url") . "/email_verified");

        return response()->redirectTo(config("app.url") . "/email_not_verified");
    }


    public function SendPasswordResetEmail(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->emailService->SendPasswordResetEmail($user);  
        return $this->JsonResponse(true, 'Письмо для сброса пароля отправлено');
    }


    public function VerifyPasswordReset(PasswordResetRequest $request): JsonResponse
    {
        $user = $request->user();
        $this->passwordService->VerifyAndResetPassword($user, $request->token, $request->password);
        return $this->JsonResponse(true, 'Пароль успешно изменён');
    }
}

