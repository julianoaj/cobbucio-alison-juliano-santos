<?php

use App\Http\Controllers\Api\TransactionController;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::middleware(['web', ValidateSessionWithWorkOS::class,])->group(function () {

    Route::controller(TransactionController::class)->name('api.transaction.')->group(function () {
        Route::get('transaction/index', 'index')->name('index');
    });

});
