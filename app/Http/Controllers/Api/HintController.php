<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                    'token'    => null,
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
                'token'   => null,
            ],
            200
        );
    }
}
