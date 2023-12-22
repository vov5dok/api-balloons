<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('login', $request['login'])->first();

        if ($user != null) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Пользователь с таким никнеймом существует',
                    'token'   => null,
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Пользователя с таким никнеймом не существует',
                'token'   => null,
            ],
            200
        );
    }
}
