<?php

namespace App\Http\Controllers\Api;

use App\Enums\FigureTypes;
use App\Http\Controllers\Controller;
use App\Models\CompletedLevel;
use App\Models\Figure;
use App\Models\Hint;
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
        $level['steps'] = -1;
        $level['time'] = -1;
        $figure = null;
        foreach ($levelModel->goals as $goal) {
            $figure = Figure::where('id', $goal->figure_id)->first();

			if ($figure->figureType->name == FigureTypes::Step->value) {
				$level['steps'] = $goal->count;
			} elseif ($figure->figureType->name == FigureTypes::Time->value) {
                $level['time'] = $goal->count;
            } else {
				$level['goals'][] = [
					'figure' => $goal->figure_id,
					'count'  => $goal->count,
				];
			}
        }

        $level['awards'] = [];
        foreach ($levelModel->awards->sortByDesc('count_star') as $award) {
            $level['awards'][] = [
                'figure'     => $award->figure_id,
                'count'      => $award->count,
                'countStar'  => $award->count_star,
            ];
        }

        $level['cells'] = [];

        $levelModelSorted = $levelModel->cells->sortBy(['x', 'y']);
        foreach ($levelModelSorted as $cell) {
            $level['cells'][] = [
                'x'           => $cell->x,
                'y'           => $cell->y,
                'isExist'     => $cell->is_exist,
                'figureId'    => $cell->figure_id,
                'countShoot'  => $cell->count_shoot,
            ];
        }

        $level['free'] = $level['steps'] == -1 && $level['time'] == -1 ? 1 : -1;

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'level'    => $level,
            ],
            200
        );
    }

    public function complete(Request $request)
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
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
                ],
                500
            );
        }

		$completedLevel = CompletedLevel::where('user_id', $user->id)->where('level_id', $levelModel->id)->first();

		if ($completedLevel == null) {
			CompletedLevel::created([
				'level_id' => $levelModel->id,
				'user_id' => $user->id,
				'count_star' => $request->countStar
			]);
		} else {
			if ($request->countStar > $completedLevel->count_star) {
				$completedLevel = CompletedLevel::updateOrCreate(
					['level_id' => $levelModel->id, 'user_id' => $user->id],
					['count_star' => $request->countStar]
				);

				$awards = $levelModel->awards;
				foreach ($awards as $award) {
					$figure = $award->figure;

					if ($award->count_star == $request->countStar) {
						if ($figure->figureType->name == FigureTypes::Hint->value) {
							$hint = Hint::where('user_id', $user->id)->where('figure_id', $figure->id)->first();
							if ($hint == null) {
								Hint::create([
									'user_id' => $user->id,
									'figure_id' => $figure->id,
									'count' => $award->count,
								]);
							} else {
								$hint->count += $award->count;
								$hint->save();
							}
						}

						if ($figure->figureType->name == FigureTypes::Coins->value) {
							$user->money += $award->count;
							$user->save();
						}
					}
				}
			}
		}


        return response()->json(
            [
                'success'  => true,
                'message'  => null,
            ],
            200
        );
    }
}
