<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\TableApiController;
use App\Http\Controllers\Api\CustomerApiController;

// Public API routes
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Public menu
    Route::get('/menu', [MenuApiController::class, 'index']);
    Route::get('/menu/{id}', [MenuApiController::class, 'show']);
    Route::get('/categories', [MenuApiController::class, 'categories']);

    // Authenticated API routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Orders
        Route::apiResource('orders', OrderApiController::class);
        Route::patch('/orders/{order}/status', [OrderApiController::class, 'updateStatus']);

        // Tables
        Route::get('/tables', [TableApiController::class, 'index']);
        Route::patch('/tables/{table}/status', [TableApiController::class, 'updateStatus']);

        // Customers
        Route::apiResource('customers', CustomerApiController::class);
        Route::get('/customers/search', [CustomerApiController::class, 'search']);
    });
});
