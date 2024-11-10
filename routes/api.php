<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;

Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::patch('/{id}', [OrderController::class, 'updateStatus']);
})->middleware(JwtMiddleware::class);
