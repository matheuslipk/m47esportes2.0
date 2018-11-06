<?php

Route::prefix('admin')->group(function () {
	Route::get('/', "AdminController@index")->name('adminhome');
    Route::get('/login', "AuthAdmin\LoginController@showLoginForm")->name('adminlogin');
	Route::post('/login', "AuthAdmin\LoginController@login");

    Route::get('/register', "AuthAdmin\RegistroController@showRegistroForm")->name('adminregistro');
    Route::post('/register', "AuthAdmin\RegistroController@registrar");

    Route::get('/eventos', "Admin\EventoAdminController@index")->name('admin-evento');


});



Route::get('/', 'EventoController@index');

Route::prefix('api365')->group(function () {
    Route::get('upcoming', 'Api\EventosApi@eventos_futuros');
    Route::post('prematch', 'Api\EventosApi@pre_math_odds');
    Route::get('resultado', 'Api\EventosApi@resultado');
});


Route::prefix('sessao')->group(function () {
	Route::get('palpite/{evento}/{palpite}', 'SessaoController@salvarPalpite')
		->where([
			'evento' => '[0-9]+',
			'palpite' => '[0-9]+',
		]);
	Route::get('teste', 'SessaoController@teste');
});

Route::get('evento/{id}/odds', 'EventoController@getOdds');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');