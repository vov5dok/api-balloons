<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function setRecoveryCode(RecoveryCodeRequest $request)
    {
        $data = $request->validated();
        $randomCode = rand(100000, 999999);

        $now = Carbon::now();
        $futureTime = $now->addMinutes(10);

        $user = User::where('email', $data['email'])->update(['recovery_code' => $randomCode, 'recovery_date' => $futureTime]);

        Mail::to($data['email'])->send(new RecoveryCodeMail($randomCode));

        return response()->json(
            [
                'success' => true,
                'message' => null,
                'token'   => null,
            ],
            500
        );
    }
}
