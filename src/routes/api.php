<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CourierController;
use App\Http\Controllers\Api\OrderController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::apiResource('/couriers', CourierController::class)->only('store', 'update', 'show');
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/assign', [OrderController::class, 'assign']);
    Route::post('/orders/complete', [OrderController::class, 'complete']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
