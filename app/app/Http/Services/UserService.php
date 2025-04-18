<?php

namespace App\Http\Services;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{

    public function FindUserByField(string $field, string $value) : User
    {
        $foundUser = User::query()->where($field, $value)->first();

        if (!$foundUser)
        throw new NotFoundHttpException("Пользователь не найден");

        return $foundUser;
    }

    public function CreateUser(array $data): User
    {
        $duplicate = User::query()->where('phone', $data['phone'])->first();

        if ($duplicate)
        throw new BadRequestHttpException("Пользователь уже существует");
    
        return User::create($data);
    }
}
