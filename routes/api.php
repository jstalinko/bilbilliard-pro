<?php

use App\Http\Controllers\BilliardTableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::post('/session-billiard/create' , [BilliardTableController::class , 'sessionCreate'])->name('session-billiard.create');
Route::post('/session-billiard/close',[BilliardTableController::class,'sessionClose'])->name('session-billiard.close');