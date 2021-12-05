<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DiscountController;

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

// Order Route
Route::get('/order', [OrderController::class, 'index']);
Route::get('/order/{orderId}', [OrderController::class, 'show']);
Route::post('/order/{customerId}', [OrderController::class, 'insert']);
Route::delete('/order/{orderId}', [OrderController::class, 'delete']);

// Discount Route
Route::get('/discount/{orderId}', [DiscountController::class, 'index']);