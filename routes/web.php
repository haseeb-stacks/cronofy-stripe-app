<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/cronofy', [App\Http\Controllers\CronofyController::class, 'index'])->middleware('auth');
Route::get('/cronofy/callback', [App\Http\Controllers\CronofyController::class, 'callback'])->middleware('auth');
Route::get('/stripe', [App\Http\Controllers\StripeController::class, 'index'])->middleware('auth');

