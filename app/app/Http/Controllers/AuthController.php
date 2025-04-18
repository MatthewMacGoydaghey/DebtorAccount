<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\PhoneVerificationRequest;
use App\Http\Requests\Auth\RegRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Services\EmailService;
use App\Http\Services\PasswordService;
use App\Http\Services\UserService;
use App\Http\Services\SMSService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{

    public function __construct(
    protected UserService $userService,
    protected PasswordService $passwordService,
    protected EmailService $emailService,
    protected SMSService $smsService,
    ) {}


    public function Register(RegRequest $request): JsonResponse
    {
        if (!$request->sms_code)
        {
            $user = $this->userService->FindUserByField("phone", $request->phone);
            return $user->isPhoneVerified()
            ? $this->jsonResponse(true, "Пользователь уже зарегистрирован")
            : $this->jsonResponse(true, "Пользователь найден, необходимо подтвердить номер телефона");
        }

        if ((int)$request->sms_code !== Cache::get($request->phone))
        return $this->jsonResponse(false, 'SMS код неверный');

        $user = $this->userService->CreateUser($request->validated());
        $user->VerifyPhone();
        return $this->JsonResponse(true, "Пользователь зарегистрирован");
    }

    public function SendPhoneVerificationCode(PhoneVerificationRequest $request): JsonResponse
    {
        $user = User::where("phone", $request->phone)->first();

        if ($user && $user->isPhoneVerified())
        return $this->jsonResponse(false, 'Этот номер телефона уже верифицирован');
    
        //$code = $this->smsService->GenerateCode();
        //$this->smsService->SendSMS($request->phone, "Ваш код подтверждения: $code");
        $code = 0000;
        Cache::put($request->phone, $code, 300);
        return $this->jsonResponse(true, 'SMS код отправлен');
    }


    public function Login(LoginRequest $request) : JsonResponse
    {
        $field = $request->email ? 'email' : 'phone';
        $user = $this->userService->FindUserByField($field, $request->email ?? $request->phone);
        $this->passwordService->ValidatePassword($user, $request->password);
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


    public function SendEmailVerification(VerifyEmailRequest $request): JsonResponse
    {
        $user = $this->userService->FindUserByField("email", $request->email);
        $this->emailService->SendVerificationEmail($user); 
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

