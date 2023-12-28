<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $level = [];
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'level' => null,
                    'token'    => null,
                ],
                500
            );
        }

        $levelModel = Level::where('id', $request->levelId)->first();

        if ($levelModel == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Уровень не найден',
                    'level'   => null,
                    'token'    => null,
                ],
                500
            );
        }

        $level['star'] = [
            'first'  => $levelModel->point_first_star,
            'second' => $levelModel->point_second_star,
            'third'  => $levelModel->point_third_star,
        ];

        $level['goals'] = [];
        foreach ($levelModel->goals as $goal) {
            $level['goals'][] = [
                'figure' => $goal->figure_id,
                'count'  => $goal->count,
            ];
        }

        $level['awards'] = [];
        foreach ($levelModel->awards as $award) {
            $level['awards'][] = [
                'figure'     => $award->figure_id,
                'count'      => $award->count,
                'countStar'  => $award->count_star,
            ];
        }

        $level['cells'] = [];
        foreach ($levelModel->cells as $cell) {
            $level['cells'][] = [
                'x'           => $cell->x,
                'y'           => $cell->y,
                'isExist'     => $cell->is_exist,
                'figureId'    => $cell->figure_id,
                'countShoot'  => $cell->count_shoot,
            ];
        }

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'level'    => $level,
                'token'    => null,
            ],
            200
        );
    }
}
