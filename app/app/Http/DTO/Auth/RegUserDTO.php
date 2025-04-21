<?php

namespace App\Http\DTO\Auth;

final class RegUserDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $name,
        public readonly string $surname,
        public readonly ?string $patronymic = null,
        public readonly string $date_of_birth,
        public readonly string $password,
        public readonly ?int $sms_code = null,
    ) {}
}