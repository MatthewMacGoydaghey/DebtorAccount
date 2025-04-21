<?php

namespace App\Http\Services;

use App\Http\DTO\Auth\RegUserDTO;
use App\Http\Services\SMSService;
use App\Http\Services\UserService;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthService
{
    public function __construct(
        private UserService $userService,
        private SMSService $smsService
    ) {}

    public function RegisterUser(RegUserDTO $dto): array
    {
        if ($dto->sms_code != null)
        return $this->HandleUnverifiedUser($dto->phone);

        if (!$this->smsService->VerifyCode($dto->phone, $dto->sms_code))
        throw new BadRequestHttpException('SMS код неверный');

        $user = $this->userService->createUser($dto);
        $user->verifyPhone();
        return ['status' => true, 'message' => 'Пользователь зарегистрирован'];
    }


    private function HandleUnverifiedUser(string $phone): array
    {
        $user = $this->userService->FindUserByField('phone', $phone);

        if ($user?->isPhoneVerified())
        return ['status' => false, 'message' => 'Пользователь уже зарегистрирован'];

        return ['status' => true, 'message' => 'Необходимо подтвердить номер телефона'];
    }


    public function SendVerificationCode(string $phone): array
    {
        $user = User::where("phone", $phone)->first();

        if ($user && $user->isPhoneVerified())
        throw new BadRequestHttpException('Номер уже верифицирован');

        $this->smsService->SendVerificationCode($phone);
        return ['status' => true, 'message' => 'SMS код отправлен'];
    }
}