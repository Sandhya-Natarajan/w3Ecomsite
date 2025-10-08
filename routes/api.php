<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function ()  {

    Route ::get('users',[UserController::class,'users']);
    Route ::post('user-detail',[UserController::class,'user']);
    Route::delete('delete-user',[UserController::class,'UserDelete']);
    Route::patch('update-user',[UserController::class,'UpdateUser']);
    Route::post('logout',[UserController::class,'logout']);


/*     Route ::get('categories',[CategoryController::class,'categories']);
    Route ::get('category-detail/{id}',[CategoryController::class,'category']);
    Route::delete('delete-category/{id}',[CategoryController::class,'DeleteCategory']);
    Route::patch('update-category/{id}',[CategoryController::class,'UpdateCategory']);
    Route::post('add-category',[CategoryController::class,'AddCategory']);
 */


    Route::get('categories', [CategoryController::class,'index']);
    Route::get('categories/{category}',[CategoryController::class,'show']);
    Route::post('categories',[CategoryController::class,'store']);
    Route::put('categories/{category}',[CategoryController::class,'update']);
    Route::delete('categories/{category}',[CategoryController::class,'destroy']);


    Route ::get('tags',[TagController::class,'tags']);
    Route ::get('tag-detail/{id}',[TagController::class,'tag']);
    Route::delete('delete-tag/{id}',[TagController::class,'DeleteTag']);
    Route::patch('update-tag/{id}',[TagController::class,'UpdateTag']);
    Route::post('add-tag',[TagController::class,'AddTag']);




    Route ::get('products',[ProductController::class,'products']);
    Route ::get('product-detail/{id}',[ProductController::class,'product']);
    Route::delete('delete-product/{id}',[ProductController::class,'DeleteProduct']);
    Route::patch('update-product/{id}',[ProductController::class,'UpdateProduct']);
    Route::post('add-product',[ProductController::class,'AddProduct']);



    Route::post('add-order',[OrderController::class,'AddOrder']);
    Route ::get('orders',[OrderController::class,'orders']);
    Route ::get('order-detail/{id}',[OrderController::class,'order']);
    Route::patch('update-order/{id}',[OrderController::class,'updateOrder']);
    Route::delete('delete-order/{id}',[OrderController::class,'DeleteOrder']);

});



Route::post('login',[UserController::class,'login']);
Route::post('signup',[UserController::class,'signup']);



