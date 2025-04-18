<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SMSService {

    protected $password;
    protected $login;

    public function __construct()
    {
        $this->password = env("SMSC_PASSWORD");
        $this->login = env('SMSC_LOGIN');
    }

    public function SendSMS($phone, $message)
    {
        $password = $this->password;
        $login = $this->login;
        $response = Http::withOptions(['verify' => false])->get('https://smsc.ru/sys/send.php', [
            'fmt' => 3,
            'login' => $login,
            'psw' => $password,
            'phones' => $phone,
            'mes' => $message
        ]);
        $body = json_decode($response->body(), true);
        Log::channel('info')->debug($body);
        if (array_key_exists('error', $body)) {
            $error = $body['error'];
            throw new HttpException(400, "Произошла ошибка при отправке SMS: $error");
        }
    }

    public function GenerateCode(): int
    {
        return rand(0000, 9999);
    }
}