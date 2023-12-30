<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function index()
    {
        $products = [];
        $user = auth()->user();

        if ($user == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Пользователь не авторизован',
                    'products' => null,
                ],
                500
            );
        }

        $productsModel = Product::all();

        foreach ($productsModel as $productModel) {
            $products[] = [
                'figure'    => $productModel->figure_id,
                'count'     => $productModel->count,
                'price'     => $productModel->price,
                'moneyType' => $productModel->money_type_id,
            ];
        }

        return response()->json(
            [
                'success'  => true,
                'message'  => null,
                'products' => $products,
            ],
            200
        );
    }
}
