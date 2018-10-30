<?php


Route::get('/', function () {
    return view('index');
});

Route::prefix('api365')->group(function () {
    Route::get('upcoming', 'Api\EventosApi@eventos_futuros');
    Route::get('premath', 'Api\EventosApi@pre_math_odds');
    Route::get('resultado', 'Api\EventosApi@resultado');
});