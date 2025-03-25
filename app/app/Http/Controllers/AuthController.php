<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Requests\Requests\Auth\LoginRequest;
use App\Http\Requests\Requests\Auth\RegRequest;
use App\Mail\EmailVerification;
use App\Mail\PasswordVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

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
        $user = $this->FindUserByField("email", $request->email);

        if ($request->phone)
        $user = $this->FindUserByField("phone", $request->phone);

        if (!Hash::check($request->password, $user->password))
        abort(403, "Неверный пароль");

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
        $user = $this->findUserByField('email', $request->email);
        if ($user->email_verified_at) throw new BadRequestHttpException('Почта уже подтверждена');
        $token = Str::random(50);
        Cache::set($request->email, $token, 86400);
        Mail::to($user->email)->send(new EmailVerification($token, $request->emal));    
        return $this->jsonResponse(true, 'Письмо для верификации почты отправлено');
    }

    public function verifyEmail(VerifyEmailRequest $request): RedirectResponse
    {
        $user = $this->findUserByField('email', $request->email);
        $cachedToken = Cache::get($request->email);
        if ($request->token == $cachedToken) {
          $user->email_verified_at = now();
          $user->save();
          return response()->redirectTo("http://localhost:8000/email_verified");
        }
        return response()->redirectTo("http://localhost:8000/email_not_verified");
    }

    public function sendPasswordResetEmail(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = Password::createToken($user);
        Mail::to($user->email)->send(new PasswordVerification($token));    
        return $this->jsonResponse(true, 'Письмо для сброса пароля отправлено');
    }

    public function verifyReset(PasswordResetRequest $request): JsonResponse
    {
        $user = $request->user();
        if (Password::tokenExists($user, $request->token)) {
            $user->password = $request->password;
            $user->save();
            Password::deleteToken($user);
            return $this->jsonResponse(true, 'Пароль успешно изменён');
          }
          return $this->jsonResponse(true, 'Токен неверный');
    }




    private function FindUserByField(string $field, string $value) : User
    {
        $foundUser = User::query()->where($field, $value)->first();
        if (!$foundUser) throw new NotFoundHttpException("Пользователь не найден");
        return $foundUser;
    }
}
