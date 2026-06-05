<?php

use App\Http\Controllers\MoonShine\TransactionExportController;
use App\Http\Controllers\MoonShine\TurnoverBalanceReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/transactions/export/{format}', [TransactionExportController::class, 'download'])
    ->name('moonshine.transactions.export');

Route::get('/reports/turnover-balance', [TurnoverBalanceReportController::class, 'index'])
    ->name('moonshine.reports.turnover-balance');
