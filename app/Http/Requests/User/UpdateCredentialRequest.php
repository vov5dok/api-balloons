<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdateCredentialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login'    => 'required|string|unique:users|between:3,30',
            'password' => 'required|min:8',
            'email'    => 'email:rfc,dns|unique:users',
        ];
    }

    public function messages()
    {
        return [
            'login.required'    => 'Поле Логин обязательно для заполнения',
            'login.string'      => 'Поле Логин должно быть строковым значением',
            'login.unique'      => 'Пользователь с логином `:input` уже существует',
            'login.between'     => 'Логин должен быть от :min до :max символов',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.min'      => 'Минимальная длина пароля - :min символов',
            'email.email'       => 'Поле Email не валидно (введите в формате email@domain.ru)',
            'email.unique'      => 'Пользователь с email `:input` уже существует',
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
