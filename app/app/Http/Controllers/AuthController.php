<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\RegRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function Register(RegRequest $request): JsonResponse
    {
        User::create($request->validated());
        return $this->JsonResponse(true, "Регистрация успешна");
    }


    public function Login(LoginRequest $request) : JsonResponse
    {
        if ($request->email)
        $user = User::FindByField("email", $request->email);

        if ($request->phone)
        $user = User::FindByField("phone", $request->phone);

        $user->ValidatePassword($request->password);
        return $this->JsonResponse(true, "Аутентификация успешна", $user->createToken("T")->plainTextToken);
    }


    public function GetUserInfo(Request $request)
    {
        $user = $request->user();
        return ["name" => $user->name, "surname" => $user->surname, "patronymic" => $user->patronymic ?? null];
    }


    public function Logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->JsonResponse(true, "Вы успешно вышли");
    }


    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->email_verified_at) abort(403, 'Email не подтверждён');
        $user->update(['password' => $request->password]);
        $user->tokens()->delete();
        return $this->jsonResponse(true, 'Пароль изменён, авторизуйтесь заново');
    }


    public function sendEmailVerification(VerifyEmailRequest $request): JsonResponse
    {
        $user = User::FindByField("email", $request->email);
        $user->SentVerificationEmail(); 
        return $this->jsonResponse(true, 'Письмо для верификации почты отправлено');
    }


    public function verifyEmail(VerifyEmailRequest $request): RedirectResponse
    {
        $user = User::FindByField("email", $request->email);
        if ($user->VerifyEmail($request->token))
        return response()->redirectTo(config("app.url") . "/email_verified");

        return response()->redirectTo(config("app.url") . "/email_not_verified");
    }


    public function sendPasswordResetEmail(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->SendPasswordResetEmail();  
        return $this->jsonResponse(true, 'Письмо для сброса пароля отправлено');
    }

    public function verifyPasswordReset(PasswordResetRequest $request)
    {
        $user = $request->user();
        if ($user->VerifyAndResetPassword($request->token, $request->password))
        return $this->jsonResponse(true, 'Пароль успешно изменён');

        return $this->jsonResponse(true, 'Токен неверный');
    }
}

