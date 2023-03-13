<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {
    Route::get('/', 'GosatCotroller@index');
});
