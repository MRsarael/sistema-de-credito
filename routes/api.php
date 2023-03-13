<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => []], function () {
    Route::prefix('/persona')->group(function () {
        Route::get('/', \App\Http\Controllers\PersonaCotroller::class . '@index');
        Route::post('/', \App\Http\Controllers\PersonaCotroller::class . '@store');
        Route::get('/{id_person}', \App\Http\Controllers\PersonaCotroller::class . '@show');
        Route::put('/', \App\Http\Controllers\PersonaCotroller::class . '@update');
        Route::delete('/{id_person}', \App\Http\Controllers\PersonaCotroller::class . '@delete');
    });
    
    Route::prefix('/credit')->group(function () {
        Route::get('/offer/{id_person}', \App\Http\Controllers\GosatCotroller::class . '@creditOffer');
    });
});

