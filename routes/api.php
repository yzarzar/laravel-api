<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {

    // Admin only routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::delete('/users/{id}', [AuthController::class, 'delete']);

    // Role management routes
    Route::apiResource('/roles', RoleController::class);
    Route::post('users/assign-role', [RoleController::class, 'assignRole']);
    Route::get('/permissions', [RoleController::class, 'permissions']);

    // Manager and Admin routes
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/users', [AuthController::class, 'users']);
    });

    // Routes for all authenticated users
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);
    Route::get('/users/{id}', [AuthController::class, 'show']);

    // Category
    Route::apiResource('/categories', CategoryController::class);
    Route::get('/category/{id}/products', [CategoryController::class, 'indexProducts']);

    // Product routes
    Route::apiResource('/products', ProductController::class);
});
