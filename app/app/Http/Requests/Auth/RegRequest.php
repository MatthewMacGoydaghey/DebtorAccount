<?php

namespace App\Http\Requests\Auth;

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
            'sms_code' => "numeric"
        ];
    }


    public function messages()
    {
        return [
            'phone.regex' => 'Неверный формат номера телефона. Верный формат: +7##########'
        ];
    }
}
