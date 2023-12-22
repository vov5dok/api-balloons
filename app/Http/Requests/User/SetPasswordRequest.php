<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class SetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле Email обязательно для заполнения',
            'email.email' => 'Поле Email не валидно (введите в формате email@domain.ru)',
            'email.exists' => 'Пользователь с email `:input` не существует',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.min' => 'Минимальная длина пароля - :min символов',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => $errors,
                    'token'   => null,
                ],
                500
            )
        );
    }
}
