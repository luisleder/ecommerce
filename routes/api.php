<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


/**
 * @group Authentication
 *
 * APIs for user authentication.
 */
Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'namespace' => '\Laravel\Passport\Http\Controllers',
], function () {
    
    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);

});

Route::controller(UserController::class)->group(function(){
    
    Route::post('register','store');
    
});

Route::middleware('auth:api')->group(function () {
    
    Route::controller(ProductController::class)->group(function(){
        
        Route::post('product','store');
        Route::get('product','index');
        Route::put('product/{id}','update');
        Route::get('product/{id}','show');
        Route::delete('product/{id}','destroy');

    });

});
