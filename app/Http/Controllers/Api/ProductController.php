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
                    $hint->count += $product->count;
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

    public function buyInKassa(BuyProductRequest $request)
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

        $data = $request->validated();

        $product = Product::where('id', $data['productId'])->first();

        if ($product->productToMoney()) {
            $returnData = '';

            $pay = Pay::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'status_id' => PayStatus::statusCreated()->first()->id
            ]);

            $login = 'balloons_pay';
            $sum = $product->price;
            $description = $product->description;
            $payId = $pay->id;
            $src = md5("balloons_pay:$sum:0:d69cu5rOg8opeezs0EbJ:shp_payId=$payId");
            $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$login&OutSum=$sum&InvId=0&shp_payId=$payId&Description=$description&SignatureValue=$src";
            $urlTest = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$login&OutSum=$sum&Email=ivanov.seryega@ya.ru&InvId=0&shp_payId=$payId&Description=Подсказка&SignatureValue=$src&Culture=ru&IsTest=1";

            $returnData = [
                'success'  => true,
                'message'  => $urlTest,
            ];

            $returnCode = 200;
        } else {
            $returnCode = 500;
            $returnData =[
                'success'  => false,
                'message'  => 'Тип монетизации товара НЕ деньги',
            ];
        }

        return response()->json(
            $returnData,
            $returnCode
        );
    }

    public function successBuy(Request $request)
    {
        $outSum = $request->OutSum;
        $invId = $request->InvId;
        $shp_payId = $request->shp_payId;
        $signatureValue = $request->SignatureValue;

        $hash = md5("$outSum:$invId:d69cu5rOg8opeezs0EbJ:shp_payId=$shp_payId");

        if ($hash === $signatureValue) {
            $pay = Pay::where('id', $shp_payId)->first();
            $pay->status_id = PayStatus::statusPayed()->first()->id;
            $product = $pay->product;
            return view('pay.success', ['product' => $product]);
        }

        $returnCode = 500;
        $returnData =[
            'success'  => false,
            'message'  => 'Во время оплаты произошла ошибка',
        ];

        return response()->json(
            $returnData,
            $returnCode
        );
    }

    public function failBuy(Request $request)
    {
        $shp_payId = $request->shp_payId;

        $pay = Pay::where('id', $shp_payId)->first();
        $pay->status_id = PayStatus::statusCanceled()->first()->id;
        $product = $pay->product;

        return view('pay.fail', ['product' => $product]);
    }
}
