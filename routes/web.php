<?php

Route::prefix('admin')->group(function () {
	Route::get('/', "AdminController@index")->name('adminhome');
    Route::get('/login', "AuthAdmin\LoginController@showLoginForm")->name('adminlogin');
	Route::post('/login', "AuthAdmin\LoginController@login");

    Route::get('/register', "AuthAdmin\RegistroController@showRegistroForm")->name('adminregistro');
    Route::post('/register', "AuthAdmin\RegistroController@registrar");

    Route::get('/eventos', "Admin\EventoAdminController@index")->name('admin-evento');
});



Route::get('/', function () {
    return view('index');
});

Route::prefix('api365')->group(function () {
    Route::get('upcoming', 'Api\EventosApi@eventos_futuros');
    Route::get('premath', 'Api\EventosApi@pre_math_odds');
    Route::get('resultado', 'Api\EventosApi@resultado');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');