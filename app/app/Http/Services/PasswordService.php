<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PasswordService
{

    public function ValidatePassword(User $user, string $password): void
    {
        if (!Hash::check($password, $user->password))
        abort(403, "Неверный пароль");
    }

    public function ChangePassword(User $user, string $newPassword): void
    {
        if (!$user->email_verified_at) throw new UnauthorizedException('Email не подтверждён');
        $user->update(['password' => $newPassword]);
        $user->tokens()->delete();
    }

    public function VerifyAndResetPassword(User $user, string $token, string $newPassword): void
    {
        if (!Password::tokenExists($user, $token))
        throw new BadRequestException("Токен неверный");

        $user->password = $newPassword;
        $user->save();
        Password::deleteToken($user);
    }
}
