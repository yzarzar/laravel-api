<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/auth/register', [AuthController::class, 'register']);

    // Category
    Route::apiResource('/category', CategoryController::class, ['only' => ['index', 'store', 'show']]);
    Route::apiResource('/category/{id}', CategoryController::class, ['only' => ['show', 'update', 'destroy']]);

    // Product
    Route::apiResource('/product', ProductController::class, ['only' => ['index', 'store', 'show']]);
    Route::apiResource('/product/{id}', ProductController::class, ['only' => ['show', 'update', 'destroy']]);
});
