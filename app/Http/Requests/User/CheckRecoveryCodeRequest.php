<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class CheckRecoveryCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле Email обязательно для заполнения',
            'email.email' => 'Поле Email не валидно (введите в формате email@domain.ru)',
            'email.exists' => 'Пользователь с email `:input` не существует',
            'code.required' => 'Поле Код обязательно для заполнения',
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
