<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('books', BookController::class);
Route::resource('members', MemberController::class);
Route::resource('transactions', TransactionController::class);

Route::get('/transactions/filter/borrowed', [TransactionController::class, 'borrowed'])->name('transactions.borrowed');
Route::get('/transactions/filter/overdue', [TransactionController::class, 'overdue'])->name('transactions.overdue');
Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
?>