<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::controller(UserController::class)->group(function(){
    
    Route::post('token','login');
    Route::post('register','store');
    
});

Route::controller(ProductController::class)->group(function(){

    Route::post('product','store');
    Route::put('product','update');
    Route::get('product','index');
    Route::get('product/{id}','show');
    Route::delete('product','destroy');

})->middleware("auth:api");

Route::fallback(function(){
    return response()->json(['errors' => "Invalid route"], 404);
});