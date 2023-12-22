<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function setRecoveryCode(RecoveryCodeRequest $request)
    {
        $data = $request->validated();
        $randomCode = rand(100000, 999999);

        $now = Carbon::now();
        $futureTime = $now->addMinutes(10);

        $user = User::where('email', $data['email'])->update(['recovery_code' => $randomCode, 'recovery_date' => $futureTime]);

        Mail::to($data['email'])->send(new RecoveryCodeMail($randomCode));

        return response()->json(
            [
                'success' => true,
                'message' => null,
                'token'   => null,
            ],
            200
        );
    }

    public function checkRecoveryCode(CheckRecoveryCodeRequest $request)
    {
        $now = Carbon::now();
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        $recoveryDate = Carbon::createFromFormat('Y-m-d H:i:s', $user->recovery_date);

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь с таким e-mail не найден',
                    'token'   => null,
                ],
                500
            );
        }

        if ($user->recovery_code != $data['code']) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Неверный код сброса пароля',
                    'token'   => null,
                ],
                500
            );
        }

        if ($now->gt($recoveryDate)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Время действия кода вышло',
                    'token'   => null,
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => '',
                'token'   => null,
            ],
            500
        );
    }
}
