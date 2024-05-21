<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\ModifyEmailRequest;
use App\Http\Requests\User\ModifyLoginRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Http\Requests\User\UpdateCredentialRequest;
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
                ],
                500
            );
        }

        if ($user->recovery_code != $data['code']) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Неверный код сброса пароля',
                ],
                500
            );
        }

        if ($now->gt($recoveryDate)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Время действия кода вышло',
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => '',
            ],
            200
        );
    }

    public function setPassword(SetPasswordRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь с таким e-mail не найден',
                ],
                500
            );
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        return response()->json(
            [
                'success' => true,
                'message' => '',
            ],
            200
        );
    }

    public function showByJWT()
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь не авторизован',
                    'login'   => null,
                    'email'   => null,
                    'money'   => null,
                    'rating'  => null,
                    'height'  => null,
                ],
                500
            );
        }

        $completedLevels = $user->completedLevels;
        $height = 0;
        foreach ($completedLevels as $completedLevel) {
            if ($completedLevel->level !== null) {
                $height = max($completedLevel->level->height, $height);
            }
        }

        return response()->json(
            [
                'success' => true,
                'message' => '',
                'login'   => $user->login,
                'email'   => $user->email,
                'money'   => $user->money,
                'rating'  => $user->countStar,
                'height'  => $height,
            ],
            200
        );
    }

    public function modifyLogin(ModifyLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь не авторизован',
                ],
                500
            );
        }

        $user->login = $request['login'];
        $user->save();

        return response()->json(
            [
                'success' => true,
                'message' => '',
            ],
            200
        );
    }

    public function modifyEmail(ModifyEmailRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь не авторизован',
                ],
                500
            );
        }

        $user->email = $request['email'];
        $user->save();

        return response()->json(
            [
                'success' => true,
                'message' => '',
            ],
            200
        );
    }

    public function updateCredential(UpdateCredentialRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пользователь не авторизован',
                ],
                500
            );
        }

        $user->update([
            'login'         => $data['login'],
            'password'      => bcrypt($data['password']),
            'email'         => $data['email'] ?? $user->email,
            'created_at'    => Carbon::now(),
            'is_registered' => true
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => '',
            ],
            200
        );
    }
}
