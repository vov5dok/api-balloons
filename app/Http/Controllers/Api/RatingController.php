<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\LevelsRequest;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\ModifyEmailRequest;
use App\Http\Requests\User\ModifyLoginRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RatingController extends Controller
{
    public function top(): \Illuminate\Http\JsonResponse
    {
        $rating = null;
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'rating'   => $rating,
                ],
                500
            );
        }


        $ratingUsers = DB::table('users')
            ->select(DB::raw('ROW_NUMBER() OVER(ORDER BY sum_count_star DESC) AS number'), 'user_id', 'login', 'sum_count_star')
            ->leftJoin(DB::raw('(SELECT user_id, SUM(count_star) as sum_count_star FROM api_balloons.completed_levels GROUP BY user_id) as group_completed_levels'), 'group_completed_levels.user_id', '=', 'users.id')
            
            ->get();




        $thisUserInRating = $ratingUsers->where('user_id', $user->id)->first();
        $topUserInRating = $ratingUsers->whereIn('number', [1,2,3]);

        foreach ($topUserInRating as $ratingUser) {
            $rating[] = [
                'login' => $ratingUser->login,
                'count' => $ratingUser->sum_count_star,
                'sequenceNumber' => $ratingUser->number,
            ];
        }

        $rating[] = [
            'login' => $thisUserInRating->login,
            'count' => $thisUserInRating->sum_count_star,
            'sequenceNumber' => $thisUserInRating->number,
        ];

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'rating'   => $rating,
            ],
            200
        );
    }
}
