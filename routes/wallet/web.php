<?php

use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::middleware(['auth', ValidateSessionWithWorkOS::class,])->group(function () {
    //
});
