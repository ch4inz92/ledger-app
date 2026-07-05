<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ExportController;

Route::get('/export/transactions', [ExportController::class, 'transactions'])->name('export.transactions');
Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet');
Route::get('/', function () {
    return view('welcome');
});
