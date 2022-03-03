<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerControllerr;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

// Pulic routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('productsList', [ProductController::class, 'productsList']);
Route::get('products/search/{keyword}', [ProductController::class, 'search']);
Route::get('productsFind/{id}', [ProductController::class, 'show']);
// Protected routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('products', ProductController::class);
    Route::resource('users', AuthController::class);
    Route::post('users/verified/{id}', [AuthController::class, 'verified']);
    Route::post('users/updateProfile/{id}', [AuthController::class, 'updateProfile']);

    Route::resource('orders', OrderController::class);
    Route::get('orders/search/{keyword}', [OrderController::class, 'search']);

    Route::resource('carts', CartController::class);
    Route::get('carts/search/{keyword}', [CartController::class, 'search']);

    Route::resource('customers', CustomerControllerr::class);
    Route::get('customers/search/{keyword}', [CustomerControllerr::class, 'search']);

    Route::post('logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
