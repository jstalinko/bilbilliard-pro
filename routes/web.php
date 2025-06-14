<?php

use App\Http\Controllers\BilliardTableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::prefix('dashboard')->group(function () {
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/billiards/tables' ,[BilliardTableController::class,'monitor'])->name('billiard.tables');
    Route::resource('billiards',BilliardTableController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('users',UserController::class);
    Route::resource('products',ProductController::class);
    
})->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
