<?php

use App\Http\Controllers\WalletController;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::middleware(['auth', ValidateSessionWithWorkOS::class,])->group(function () {

    Route::controller(WalletController::class)->name('wallet.')->group(function () {
        Route::get('wallet', 'show')->name('show');
    });

});
