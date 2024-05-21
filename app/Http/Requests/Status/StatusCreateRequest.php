<?php

namespace App\Http\Requests\Status;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StatusCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'   => 'exists:users,id|nullable',
            'device_id' => 'required',
            'status_id' => 'required|exists:statistic_statuses,id',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists'     => 'ID пользователя `:input` не найден',
            'device_id.required' => 'Заполните device_id',
            'status_id.required' => 'Заполните status_id',
            'status_id.exists'   => 'Статус с id `:input` не найден',
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
