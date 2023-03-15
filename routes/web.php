<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home', 301);
Route::get('/home', \App\Http\Controllers\PersonalCreditSystemController::class . '@index')->name('home');

