<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RulesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('rules')->group(function () {
    Route::get('/params', [RulesController::class, 'getParams']);
    Route::get('/', [RulesController::class, 'index']);
    Route::post('/', [RulesController::class, 'store']);
    Route::get('/{rule}', [RulesController::class, 'show']);
    Route::put('/{rule}', [RulesController::class, 'update']);
});
Route::prefix('ads')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdsController::class, 'index']);
});
Route::prefix('ads-chart')->group(function () {
    Route::get('/{ad}', [\App\Http\Controllers\AdsController::class, 'chart']);
});
Route::get('/rules-log', [\App\Http\Controllers\RulesLogController::class, 'index']);
Route::get('/handle/rules/{rule}', [\App\Http\Controllers\RulesController::class, 'handleRule']);
