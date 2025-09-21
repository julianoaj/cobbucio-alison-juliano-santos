<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::middleware(['auth', ValidateSessionWithWorkOS::class])->group(function () {

    Route::get('dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

});
