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
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
