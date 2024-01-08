<?php

namespace App\Http\Controllers\Api;

use App\Enums\FigureTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Figure;
use App\Models\Hint;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Validators\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        // Валидация полей
        $data = $request->validated();

//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required|string|min:6',
//        ]);
//        if ($validator->fails()) {
//            return response()->json($validator->errors(), 422);
//        }
        if (! $token = auth()->attempt($data)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Пара логин/пароль неверные',
                    'token'   => null,
                ],
                500
            );
        }
        return $this->createNewToken($token);
    }

    public function register(RegisterRequest $request)
    {
        // Валидация полей
        $data = $request->validated();

        // Создание юзера
        $user = User::create(array_merge(
            $data,
            ['password' => bcrypt($data['password']), 'money' => 100]
        ));

        // Добавляем юзеру по 3 подсказки в таблицу hints
        $figures = Figure::hasFigureTypeName(FigureTypes::Hint)->get();
        foreach ($figures as $figure) {
            Hint::create([
                'count' => 3,
                'user_id' => $user->id,
                'figure_id' => $figure->id,
            ]);
        }

        return response()->json(
            [
                'success' => true,
                'message' => '',
                'token'   => null,
            ],
            200
        );
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => '',
            'token' => $token,
        ], 200);
    }

    protected function noAuth()
    {
        dd(23);
        return response()->json([
            'success' => false,
            'message' => 'Необходимо авторизоваться',
            'token' => null,
        ], 200);
    }
}
