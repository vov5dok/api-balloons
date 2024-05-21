<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::group([], function () {
    Route::get('/generate-uuid', [\App\Http\Controllers\DeveloperController::class, 'generateUuid']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/sendEmailForPassword', [\App\Http\Controllers\Api\UserController::class, 'setRecoveryCode']);
    Route::post('/checkRecoveryCode', [\App\Http\Controllers\Api\UserController::class, 'checkRecoveryCode']);
    Route::post('/setPassword', [\App\Http\Controllers\Api\UserController::class, 'setPassword']);
    Route::get('/configuration/formReg', [\App\Http\Controllers\Api\ConfigController::class, 'formReg']);
    Route::get('/checkLogin/{login}', [\App\Http\Controllers\Api\CheckController::class, 'login']);
    Route::get('/checkEmail/{email}', [\App\Http\Controllers\Api\CheckController::class, 'email']);
    Route::get('/configuration/time-unix', [\App\Http\Controllers\Api\ConfigController::class, 'unix']);
    Route::get('/product/successBuy', [\App\Http\Controllers\Api\ProductController::class, 'successBuy']);
    Route::get('/product/failBuy', [\App\Http\Controllers\Api\ProductController::class, 'failBuy']);
    Route::get('/pay/{pay}/status', [\App\Http\Controllers\Api\PayController::class, 'status']);
    Route::get('/stat/statuses', [\App\Http\Controllers\Api\StatisticController::class, 'statuses']);
    Route::post('/stat/add', [\App\Http\Controllers\Api\StatisticController::class, 'create']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/getUserByJWT', [\App\Http\Controllers\Api\UserController::class, 'showByJWT']);
    Route::patch('/modifyLogin', [\App\Http\Controllers\Api\UserController::class, 'modifyLogin']);
    Route::patch('/modifyEmail', [\App\Http\Controllers\Api\UserController::class, 'modifyEmail']);
    Route::post('/updateCredential', [\App\Http\Controllers\Api\UserController::class, 'updateCredential']);
    Route::get('/category/all', [\App\Http\Controllers\Api\CategoryController::class, 'all']);
    Route::get('/category/levels/{category_id}', [\App\Http\Controllers\Api\CategoryController::class, 'levels']);
    Route::get('/level/{levelId}', [\App\Http\Controllers\Api\LevelController::class, 'index']);
    Route::get('/hint/all', [\App\Http\Controllers\Api\HintController::class, 'all']);
    Route::patch('/hint', [\App\Http\Controllers\Api\HintController::class, 'updateCount']);
    Route::post('/level/complete', [\App\Http\Controllers\Api\LevelController::class, 'complete']);
    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/rating', [\App\Http\Controllers\Api\RatingController::class, 'top']);
    Route::post('/product/buy', [\App\Http\Controllers\Api\ProductController::class, 'buy']);
    Route::post('/product/buyInKassa', [\App\Http\Controllers\Api\ProductController::class, 'buyInKassa']);
});
