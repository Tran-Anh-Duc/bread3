<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix("users")->group(function (){
    Route::get("/",[UserController::class,'index']);
    Route::put("/{id}",[UserController::class,'edit']);
    Route::delete("/{id}",[UserController::class,'delete']);
});

Route::prefix("auth")->group(function (){
    Route::post('/login',[AuthController::class,'loginUser']);
    Route::post('/register',[AuthController::class,'registerUser']);
});

Route::prefix('roles')->group(function (){
    Route::get('/',[RoleController::class,'index']);
    Route::put('/{id}',[RoleController::class,'update']);
    Route::post('/create',[RoleController::class,'store']);
    Route::delete('/{id}',[RoleController::class,'destroy']);
});

Route::prefix('categories')->group(function (){
    Route::get('/',[CategoryController::class,'index']);
    Route::put('/{id}',[CategoryController::class,'update']);
    Route::post('/create',[CategoryController::class,'store']);
    Route::delete('/{id}',[CategoryController::class,'destroy']);
});

Route::prefix('stores')->group(function (){
    Route::get('/',[StoreController::class,'index']);
    Route::post('/create',[StoreController::class,'store']);
    Route::put('/{id}',[StoreController::class,'update']);
    Route::delete('/{id}',[StoreController::class,'destroy']);
});

Route::prefix('products')->group(function (){
    Route::get('/',[ProductController::class,'index']);
    Route::post('/create',[ProductController::class,'store']);
    Route::get('/{id}',[ProductController::class,'show']);
    Route::put('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
});

Route::prefix('/orders')->group(function (){
    Route::get('/cart',[OrderController::class,'cart']);
    Route::get('/create/{id}',[OrderController::class,'addOrder']);
    Route::patch('/update-cart',[OrderController::class,'updateOrder']);
    Route::delete('/remove-cart',[OrderController::class,'removeOrder']);
});

Route::get('/search/{name}',[ProductController::class,'searchProduct']);
Route::get('/filter',[ProductController::class,'searchFilter']);


