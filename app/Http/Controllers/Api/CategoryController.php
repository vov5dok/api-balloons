<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\ModifyEmailRequest;
use App\Http\Requests\User\ModifyLoginRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CategoryController extends Controller
{
    public function all(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'category' => null,
                    'token'    => null,
                ],
                500
            );
        }

        $categories = [];

        $results = DB::table('categories')
            ->join(DB::raw('
                    (SELECT levels.id, category_id, number
                    FROM levels
                    WHERE number IN (
                        SELECT MAX(number)
                        FROM levels
                        GROUP BY category_id
                    )) AS group_levels
                '), function ($join) {
                $join->on('categories.id', '=', 'group_levels.category_id');
            }
            )
            ->leftJoin(DB::raw('(SELECT completed_levels.id, completed_levels.level_id
                         FROM completed_levels
                         WHERE user_id = "' . $user->id . '") AS completed_levels'), function ($join) {
                $join->on('group_levels.id', '=', 'completed_levels.level_id');
            })
            ->leftJoin(DB::raw('(SELECT levels.id, category_id, number
                        FROM levels
                        WHERE number IN (SELECT MIN(number)
                                         FROM levels
                                         GROUP BY category_id)) AS group_levels_to_min'), function ($join) {
                $join->on('group_levels_to_min.category_id', '=', 'categories.id');
            })
            ->selectRaw("categories.id as category_id, categories.name, group_levels.id as level_id, group_levels.number as max_level_number, completed_levels.id as completed_levels_id,
                 CASE WHEN completed_levels.id IS NULL THEN TRUE ELSE FALSE END as is_current,
                 group_levels_to_min.number as min_level_number")
            ->orderBy('group_levels.number')
            ->get();

        foreach ($results as $item) {
            $category = [
                'id'   => $item->category_id,
                'name' => $item->name,
                'minLevel' => $item->min_level_number,
                'maxLevel' => $item->max_level_number,
                'isCurrentCategoryUser' => $item->is_current == 1,
            ];

            $categories[] = $category;
        }

//        foreach ($categoriesModel as $categoryModel) {
//            $minLevel = $categoryModel->levels->min('number');
//            $maxLevel = $categoryModel->levels->max('number');
//
//            foreach ($categoryModel->levels as $categoryLevel) {
////                $minLevel = $categoryLevels->min('number');
////                $maxLevel = $categoryLevels->max('number');
//
//                $completedLevelByUser = $categoryLevel->completedLevelByUser->first();
//
//                dump($completedLevelByUser->id);
//
//            }
//
//
//
//            $category = [
//                'id'   => $categoryModel->id,
//                'name' => $categoryModel->name,
//                'minLevel' => $minLevel,
//                'maxLevel' => $maxLevel,
//                'isCurrentCategoryUser' => null,
//            ];
//
//            $categories[] = $category;
//        }

//        usort($categories, function($v1, $v2) {
//            if ($v1["minLevel"] == $v2["minLevel"]) return 0;
//            return ($v1["minLevel"] < $v2["minLevel"]) ? -1 : 1;
//        });

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'category' => $categories,
                'token'    => null,
            ],
            200
        );
    }
}
