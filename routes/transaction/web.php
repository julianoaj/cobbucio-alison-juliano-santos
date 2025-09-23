<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::middleware(['auth', ValidateSessionWithWorkOS::class,])->group(function () {

    Route::controller(TransactionController::class)->name('transaction.')->group(function () {
        Route::post('transaction/store', 'store')->name('store');
    });

});
