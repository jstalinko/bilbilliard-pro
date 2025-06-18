<?php

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricingRateController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BilliardTableController;

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
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/create', [UserController::class, 'store'])->name('user.store');
        Route::post('/{id}/edit', [UserController::class, 'update'])->name('user.update');
        Route::get('/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');
    });
      Route::group(['prefix' => 'members'], function () {
        Route::get('/', [MemberController::class, 'index'])->name('member.show');
        Route::get('/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
        Route::get('/create', [MemberController::class, 'create'])->name('member.create');
        Route::post('/create', [MemberController::class, 'store'])->name('member.store');
        Route::post('/{id}/edit', [MemberController::class, 'update'])->name('member.update');
        Route::get('/{id}/delete', [MemberController::class, 'destroy'])->name('member.destroy');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.show');
        Route::get('/create', [ProductController::class,'create'])->name('product.create');
        Route::post('/create',[ProductController::class, 'store'])->name('product.store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/{id}/edit', [ProductController::class, 'update'])->name('product.update');
        Route::get('/{id}/delete', [ProductController::class, 'destroy'])->name('product.destroy');
    });

      Route::group(['prefix' => 'price-rate'], function () {
        Route::get('/', [PricingRateController::class, 'index'])->name('price-rate.show');
        Route::get('/create', [PricingRateController::class,'create'])->name('price-rate.create');
        Route::post('/create',[PricingRateController::class, 'store'])->name('price-rate.store');
        Route::get('/{id}/edit', [PricingRateController::class, 'edit'])->name('price-rate.edit');
        Route::post('/{id}/edit', [PricingRateController::class, 'update'])->name('price-rate.update');
        Route::get('/{id}/delete', [PricingRateController::class, 'destroy'])->name('price-rate.destroy');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
