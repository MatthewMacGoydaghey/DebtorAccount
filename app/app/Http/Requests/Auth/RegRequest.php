<?php

namespace App\Http\Requests\Auth;

use App\Http\DTO\Auth\RegUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "surname" => "required|string",
            "patronymic" => "nullable|string",
            "password" => "required|string|min:8",
            "date_of_birth" => "required|date",
            "phone" => ['required','string','regex:/^(\+7)?9\d{9}$/'],
            'sms_code' => "nullable|numeric"
        ];
    }


    public function messages()
    {
        return [
            'phone.regex' => 'Неверный формат номера телефона. Верный формат: +7##########'
        ];
    }

    public function toDTO(): RegUserDTO
    {
        return new RegUserDTO(
            phone: $this->input('phone'),
            sms_code: $this->input('sms_code'),
            name: $this->input('name'),
            surname: $this->input('surname'),
            patronymic: $this->input('patronymic'),
            date_of_birth: $this->input('date_of_birth'),
            password: $this->input('password'),
        );
    }
}
