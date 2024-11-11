<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;

Route::prefix('orders')->middleware(JwtMiddleware::class)->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
});
