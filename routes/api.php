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

use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController; // PurchaseController をインポート

// 既存のコード
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Sales APIルート
Route::apiResource('sales', SalesController::class);

// Purchase APIルート
Route::post('/purchase', [PurchaseController::class, 'purchase']);
