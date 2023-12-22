<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => 'required|string|exists:users',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Поле Логин обязательно для заполнения',
            'login.string' => 'Поле Логин должно быть строковым значением',
            'login.exists' => 'Пользователь с логином `:input` не существует',
            'password.required' => 'Поле Пароль обязательно для заполнения',
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
