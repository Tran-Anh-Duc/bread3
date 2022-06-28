<?php

use Illuminate\Http\Response;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Modules\Bread\Http\Controllers\CategoryController;
use Modules\Bread\Http\Controllers\OrderController;
use Modules\Bread\Http\Controllers\ProductController;
use Modules\Bread\Http\Controllers\RoleController;
use Modules\Bread\Http\Controllers\StoreController;
use Modules\Bread\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::prefix('/bread')->group(function() {
    Route::prefix("users")->group(function (){
    Route::get("/",[UserController::class,'index']);
    Route::put("/{id}",[UserController::class,'edit']);
    Route::delete("/{id}",[UserController::class,'delete']);
    Route::post('/change',[UserController::class,'changePassword']);

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
    Route::post('/',[CategoryController::class,'index']);
    Route::post('update/{id}',[CategoryController::class,'update']);
    Route::post('/create',[CategoryController::class,'store']);
    Route::post('/delete/{id}',[CategoryController::class,'destroy']);
    Route::post('/show/{id}',[CategoryController::class,'show']);
});

Route::prefix('stores')->group(function (){
    Route::get('/',[StoreController::class,'index']);
    Route::post('/create',[StoreController::class,'store']);
    Route::put('/{id}',[StoreController::class,'update']);
    Route::delete('/{id}',[StoreController::class,'destroy']);
});

Route::prefix('products')->group(function (){
    Route::post('/',[ProductController::class,'list']);
    Route::post('/create',[ProductController::class,'store']);
    Route::post('/show/{id}',[ProductController::class,'show']);
    Route::post('/update/{id}',[ProductController::class,'update']);
    Route::post('/delete/{id}',[ProductController::class,'destroy']);
    Route::post('/get_five_product',[ProductController::class,'get_five_products_new']);
    Route::post('/topView',[ProductController::class,'topView']);
});

Route::prefix('/orders')->group(function (){
    Route::get('/cart',[OrderController::class,'cart']);
    Route::get('/create/{id}',[OrderController::class,'addOrder']);
    Route::patch('/update-cart',[OrderController::class,'updateOrder']);
    Route::delete('/remove-cart',[OrderController::class,'removeOrder']);
});

Route::get('/search/{name}',[ProductController::class,'searchProduct']);
Route::post('/filter',[ProductController::class,'searchFilter']);

});





