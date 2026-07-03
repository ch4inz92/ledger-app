<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/accounts/{account}/balance', [AccountController::class, 'balance']);
});