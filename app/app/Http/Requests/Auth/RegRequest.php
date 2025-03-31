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
            "email" => "nullable|email|unique:users,email",
            "phone" => ['nullable','string','regex:/^(\+7)?9\d{9}$/', 'unique:users,phone'],
            "date_of_birth" => "required|date"
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator)
        {
        $request = $validator->getData();

        if (empty($request['email']) && empty($request['phone']))
        {
            $validator->errors()->add('email|phone', 'Поле email или phone обязательно к заполнению');
        }

        if (!empty($request['email']) && !empty($request['phone']))
        {
            $validator->errors()->add('email|phone', 'Можно указать только одно из полей: email или phone');
        }
        });
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Неверный формат номера телефона. Верный формат: +7##########'
        ];
    }
}
