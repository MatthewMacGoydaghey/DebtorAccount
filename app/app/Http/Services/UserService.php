<?php

namespace App\Http\Services;

use App\Models\User;
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
        return User::create($data);
    }
}
