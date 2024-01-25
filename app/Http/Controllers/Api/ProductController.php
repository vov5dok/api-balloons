<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckRecoveryCodeRequest;
use App\Http\Requests\User\RecoveryCodeRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Mail\User\RecoveryCodeMail;
use App\Models\MoneyType;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
                'productId' => $productModel->id,
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

    public function buy(Request $request)
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

        $product = Product::where('id', $request->productId)->first();

        if ($product == null) {
            return response()->json(
                [
                    'success'  => false,
                    'message'  => 'Продукт не найдена',
                ],
                500
            );
        }

//        if ($product->money_type_id == 'efd5e420-d9f0-4984-9f68-58bdee87b8d1') {
//            if ($user->money >= $product->price) {
//                if ($product->figure_id == '271c8561-09a2-4e57-9ccb-9f62d7cd9253') {
//                    $user->money -= $product->price;
//                    $user->save();
//
//                    $hint = $user->hints()->where('figure_id', $product->figure_id)->first();
//                    $hint->count += 1;
//                    $hint->save();
//                } else {
//                    $user->money -= $product->price;
//                    $user->save();
//                }
//            } else {
//                return response()->json(
//                    [
//                        'success'  => false,
//                        'message'  => 'Недостаточно денег',
//                    ],
//                    500
//                );
//            }
//        }


        if ($product->productToCoins()) {
            if ($user->money >= $product->price) {
                if ($product->productTypeHint()) {
                    $user->money -= $product->price;
                    $user->save();

                    $hint = $user->hints()->where('figure_id', $product->figure_id)->first();
                    $hint->count += 1;
                    $hint->save();
                } else {
                    $user->money -= $product->price;
                    $user->save();
                }
            } else {
                return response()->json(
                    [
                        'success'  => false,
                        'message'  => 'Недостаточно денег',
                    ],
                    500
                );
            }
        } else {
            // работа с кассой
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
