<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('users', [UserController::class, 'users']);
    Route::post('user-detail', [UserController::class, 'user']);
    Route::delete('delete-user', [UserController::class, 'UserDelete']);
    Route::patch('update-user', [UserController::class, 'UpdateUser']);
    Route::post('logout', [UserController::class, 'logout']);


    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('products', ProductController::class);

    Route::apiResource('orders', OrderController::class);

    Route::apiResource('tags', TagController::class);
});



Route::post('login', [UserController::class, 'login']);
Route::post('signup', [UserController::class, 'signup']);
