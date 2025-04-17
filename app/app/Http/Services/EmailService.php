<?php

namespace App\Http\Services;

use App\Mail\EmailVerification;
use App\Mail\PasswordVerification;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmailService
{

    public function SendVerificationEmail(User $user) : void
    {
        if ($user->email_verified_at)
        throw new BadRequestHttpException("Почта уже подтверждена");
    
        $token = Str::random(50);
        Cache::set($user->email, $token, 86400);
        Mail::to($user->email)->send(new EmailVerification($token, $user->email));
    }


    public function TryVerifyEmail(User $user, string $token) : bool
    {
        if ($token != Cache::get($user->email))
        return false;

        $user->email_verified_at = now();
        $user->save();
        return true;
    }


    public function SendPasswordResetEmail(User $user) : void
    {
        $token = Password::createToken($user);
        Mail::to($user->email)->send(new PasswordVerification($token));
    }
}
