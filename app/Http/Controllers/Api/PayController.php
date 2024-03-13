<?php

namespace App\Http\Controllers\Api;

use App\Enums\PayStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\BuyProductRequest;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\MoneyType;
use App\Models\Pay;
use App\Models\PayStatus;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PayController extends Controller
{
    public function status(Pay $pay)
    {
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'status' => null,
                ],
                500
            );
        }

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'status' => $pay->status->name,
            ],
            200
        );
    }
}
