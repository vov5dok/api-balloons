<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hint;
use Illuminate\Http\Request;

class HintController extends Controller
{
    public function all()
    {
        $hints = [];
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'hints' => null,
                ],
                500
            );
        }

        foreach ($user->hints as $hint) {

            $hints[] = [
                'figure' => $hint->figure_id,
                'count'  => $hint->count,
            ];
        }

        return response()->json(
            [
                'success' => true,
                'message' => null,
                'hints'   => $hints,
            ],
            200
        );
    }

    public function updateCount(Request $request)
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'hint'     => null,
                ],
                500
            );
        }

        $hintModel = Hint::where('id', $request->figure)->first();

        if ($hintModel == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Подсказка не найдена',
                    'hint'     => null,
                ],
                500
            );
        }

        if ($hintModel->count == 0) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Подсказки кончились',
                    'hint'     => null,
                ],
                500
            );
        }

        $hintModel->count = $hintModel->count - 1;
        $hintModel->save();

        $hint = [
            'figure' => $hintModel->figure_id,
            'count'  => $hintModel->count,
        ];

        return response()->json(
            [
                'success' => true,
                'message' => null,
                'hint'    => $hint,
            ],
            200
        );
    }
}
