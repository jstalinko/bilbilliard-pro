<?php

use App\Http\Controllers\BilliardTableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricingRateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::group([
    'prefix' => 'dashboard',
    'middleware' => ['auth']
],function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/billiards/tables', [BilliardTableController::class, 'monitor'])->name('billiard.tables');
    Route::group(['prefix' => 'billiards'], function () {
        Route::get('/', [BilliardTableController::class, 'index'])->name('billiard.show');
        Route::get('/{id}/edit', [BilliardTableController::class, 'edit'])->name('billiard.edit');
        Route::post('/{id}/edit', [BilliardTableController::class, 'update'])->name('billiard.update');
        Route::get('/{id}/delete', [BilliardTableController::class, 'destroy'])->name('billiard.destroy');
        Route::get('/update-all-tables',[BilliardTableController::class,'updateAll'])->name('billiard.update-all');
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transaction.show');
        Route::get('/create', [TransactionController::class , 'create'])->name('transactions.create');
        Route::post('/create', [TransactionController::class , 'store'])->name('transactions.store');
        Route::get('/{id}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
        Route::post('/{id}/edit', [TransactionController::class, 'update'])->name('transaction.update');
        Route::get('/{id}/delete', [TransactionController::class, 'destroy'])->name('transaction.destroy');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/{id}/edit', [UserController::class, 'update'])->name('user.update');
        Route::get('/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/{id}/edit', [ProductController::class, 'update'])->name('product.update');
        Route::get('/{id}/delete', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    Route::group(['prefix' => 'price-rate'] , function(){
        Route::get('/' , [PricingRateController::class,'index'])->name('pricing-rate.show');
        
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
