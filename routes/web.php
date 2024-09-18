<?php

use App\Http\Controllers\ApparelsController;
use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\WalletsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('apparels', ApparelsController::class);
    Route::resource('wallets', WalletsController::class);
    Route::resource('budgets', BudgetsController::class);
    Route::resource('events', EventsController::class);
    Route::resource('userInfo', UserInfoController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('/getCalendarData', [CalendarController::class, 'getCalendarData'])->name('getCalendar');
    Route::get('/getBudgetByCategory', [BudgetsController::class, 'getBudgetByCategory'])->name('getBudget');
    Route::get('/getTransactionsByEvent', [EventsController::class, 'getTransactionsByEvent'])->name('getTransactionsByEvent');
    Route::get('/getTransactionByBudget', [BudgetsController::class, 'getTransactionByBudget'])->name('getTransactionByBudget');
    Route::get('/getTransactionByWallet', [WalletsController::class, 'getTransactionByWallet'])->name('getTransactionByWallet');
    Route::get('/getRecentTransaction', [TransactionController::class, 'getRecentTransaction'])->name('getRecentTransaction');
    Route::get('/getTransactionTypeDist', [TransactionController::class, 'getTransactionTypeDist'])->name('getTransactionTypeDist');
    Route::get('/getBudgetBreakdown', [BudgetsController::class, 'getBudgetBreakdown'])->name('getBudgetBreakdown');
    Route::get('/getMonthlySpendingTrend', [BudgetsController::class, 'getMonthlySpendingTrend'])->name('getMonthlySpendingTrend');
    Route::get('/getExpenseBreakdown', [WalletsController::class, 'getExpenseBreakdown'])->name('getExpenseBreakdown');
    Route::get('/getMonthlyIncomeTrend', [WalletsController::class, 'getMonthlyIncomeTrend'])->name('getMonthlyIncomeTrend');
    Route::get('/getBudgetVsActual', [BudgetsController::class, 'getBudgetVsActual'])->name('getBudgetVsActual');
    Route::get('/getOverBudgetAlerts', [BudgetsController::class, 'getOverBudgetAlerts'])->name('getOverBudgetAlerts');
    Route::get('/getNetWorthOverTime', [TransactionController::class, 'getNetWorthOverTime'])->name('getNetWorthOverTime');
    Route::get('/checkEvent', [EventsController::class, 'checkEvent'])->name('checkEvent');
    Route::get('/checkBudget', [BudgetsController::class, 'checkBudget'])->name('checkBudget');
});

require __DIR__.'/auth.php';
