<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ConfigController extends Controller
{
    public function formReg()
    {
        $config = [
            'login' => [
                ['regex' => '^.{3,30}$', 'message' => 'Длина логина должна быть от 3 до 30 символов'],
                ['regex' => '^[A-Za-z0-9_]+$', 'message' => 'Логин должен содержать только буквы или цифры'],
            ],
            'password' => [
                ['regex' => '^.{8,30}$', 'message' => 'Длина пароля должна быть от 8 до 30 символов'],
                ['regex' => '^.*[a-z]*$', 'message' => 'В пароле должна быть 1 строчная буква'],
                ['regex' => '^.*[A-Z]*$', 'message' => 'В пароле должна быть 1 заглавная буква'],
                ['regex' => '^*\d*$', 'message' => 'В пароле должна быть 1 цифра'],
                ['regex' => '[!#$%&? {}[\]_\-():;.,<>\"]+', 'message' => 'В пароле должен быть 1 специальный символ'],
            ],
            'email' => [
                ['regex' => '^([\w\.\-]+)@([\w\-]+)((\.(\w){2,})+)$', 'message' => 'Некорректный формат'],
            ],

        ];

        return response()->json(
            [
                'success' => true,
                'message' => null,
                'config'  => $config,
            ],
            200
        );
    }
}
