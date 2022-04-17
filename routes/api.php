<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('/V1/user/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/V1/user/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/V1/cart', [\App\Http\Controllers\CartController::class, 'create']);
    Route::delete('/V1/cart', [\App\Http\Controllers\CartController::class, 'delete']);
    Route::get('/V1/cart/{cart}', [\App\Http\Controllers\CartController::class, 'show']);
    Route::get('/V1/carts', [\App\Http\Controllers\CartController::class, 'getList']);

    Route::post('/V1/cart/products', [\App\Http\Controllers\Cart\ProductController::class, 'addProducts']);
    Route::delete('/V1/cart/products', [\App\Http\Controllers\Cart\ProductController::class, 'removeProducts']);

    Route::post('/V1/user/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});
