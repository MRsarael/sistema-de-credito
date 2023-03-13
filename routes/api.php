<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => []], function () {
    Route::prefix('/person')->group(function () {
        Route::get('/', \App\Http\Controllers\PersonaCotroller::class . '@index');
        Route::post('/', \App\Http\Controllers\PersonaCotroller::class . '@store');
        Route::get('/{id_person}', \App\Http\Controllers\PersonaCotroller::class . '@show');
        Route::put('/', \App\Http\Controllers\PersonaCotroller::class . '@update');
        Route::delete('/{id_person}', \App\Http\Controllers\PersonaCotroller::class . '@delete');

        Route::prefix('/credit')->group(function () {
            Route::get('/offer/{id_person?}', \App\Http\Controllers\PersonaCotroller::class . '@offerConsult')->name('consultOffer');
            Route::post('/simulation', \App\Http\Controllers\PersonaCotroller::class . '@simulationOffer')->name('simulationOffer');
        });
    });
    
    Route::prefix('/credit')->group(function () {
        Route::get('/{id_person}', \App\Http\Controllers\GosatCotroller::class . '@creditOffer')->name('consult');
    });
});
