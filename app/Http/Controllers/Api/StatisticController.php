<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Status\StatusCreateRequest;
use App\Models\Statistic;
use App\Models\StatisticStatus;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function statuses()
    {
        $statisticStatuses = StatisticStatus::orderBy('number_step', 'asc')->get();
        $data = [];

        foreach ($statisticStatuses as $item) {
            $data[] = [
                'id' => $item->id,
                'numberStep' => $item->number_step,
            ];
        }

        return response()->json(
            [
                'success' => true,
                'message' => '',
                'statuses' => $data
            ],
            200
        );
    }

    public function create(StatusCreateRequest $request)
    {
        $data = $request->validated();

        try {
            Statistic::create([
                'user_id'   => $data['user_id'] ?? null,
                'device_id' => $data['device_id'],
                'statistic_status_id' => $data['status_id'],
            ]);

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'На сервере произошла ошибка',
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
}
