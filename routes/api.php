<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/auth/register', [AuthController::class, 'register']);

    // User
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);
    Route::get('/users', [AuthController::class, 'users']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::delete('/users/{id}', [AuthController::class, 'delete']);

    // Category
    Route::apiResource('/categories', CategoryController::class);
    Route::get('/category/{id}/products', [CategoryController::class, 'indexProducts']);

    // Product
    Route::apiResource('/products', ProductController::class);
});
