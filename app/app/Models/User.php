<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\EmailVerification;
use App\Mail\PasswordVerification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, CanResetPassword;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        "name",
        "surname",
        "patronymic",
        "date_of_birth",
        "password",
        "email",
        "phone"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public static function FindByField(string $field, string $value) : User
    {
        $foundUser = User::query()->where($field, $value)->first();
        if (!$foundUser) throw new NotFoundHttpException("Пользователь не найден");
        return $foundUser;
    }



    public function ValidatePassword(string $password)
    {
        if (!Hash::check($password, $this->password))
        abort(403, "Неверный пароль");
    }


    public function SentVerificationEmail() : void
    {
        if ($this->email_verified_at) throw new BadRequestException("Почта уже подтверждена");
        $token = Str::random(50);
        Cache::set($this->email, $token, 86400);
        Mail::to($this->email)->send(new EmailVerification($token, $this->email));
    }


    public function VerifyEmail(string $token) : bool
    {
        if ($token != Cache::get($this->email))
        return false;

        $this->email_verified_at = now();
        $this->save();
        return true;
    }


    public function SendPasswordResetEmail() : void
    {
        $token = Password::createToken($this);
        Mail::to($this->email)->send(new PasswordVerification($token));
    }

    public function VerifyAndResetPassword(string $token, string $newPassword) : bool
    {
        if (!Password::tokenExists($this, $token))
        return false;

        $this->password = $newPassword;
        $this->save();
        Password::deleteToken($this);
        return true;
    }
}
