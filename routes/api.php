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
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/getUserByJWT', [\App\Http\Controllers\Api\UserController::class, 'showByJWT']);
    Route::patch('/modifyLogin', [\App\Http\Controllers\Api\UserController::class, 'modifyLogin']);
    Route::patch('/modifyEmail', [\App\Http\Controllers\Api\UserController::class, 'modifyEmail']);
    Route::get('/category/all', [\App\Http\Controllers\Api\CategoryController::class, 'all']);
});
