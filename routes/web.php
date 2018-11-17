<?php

Route::prefix('admin')->group(function () {
	Route::get('/', "AdminController@index")->name('adminhome');
	Route::get('/apostas', "Admin\ApostaAdminController@apostas")->name('adminapostas');
	Route::get('/apostasJSON', "Admin\ApostaAdminController@apostasJSON");

    Route::get('/login', "AuthAdmin\LoginAdminController@showLoginForm")->name('adminlogin');
	Route::post('/login', "AuthAdmin\LoginAdminController@login");

    Route::get('/register', "AuthAdmin\RegistroAdminController@showRegistroForm")->name('adminregistro');
    Route::post('/register', "AuthAdmin\RegistroAdminController@registrar");

    Route::get('/eventos', "Admin\EventoAdminController@index")->name('admin-evento');
    Route::get('/eventos/atualizar', "Admin\EventoAdminController@showAtualizarResultadoEventos");
    Route::get('/eventos/cadastrar', "Admin\EventoAdminController@showAtualizarResultadoEventos");
    Route::get('/eventos/getJSON', "Admin\EventoAdminController@getEventosJSON")->name('getEventosJSONAdmin');

    Route::get('/evento/atualizarNaApi', "Admin\EventoAdminController@atualizarEventoApi");
});

Route::prefix('agente')->group(function () {
    Route::get('/apostas', 'Agente\ApostaAgenteController@apostas')->name('agenteapostas');
    Route::get('/apostasJSON', "Agente\ApostaAgenteController@apostasJSON");

    Route::get('/aposta/validar', "Agente\ApostaAgenteController@showValidar")->name('agentevalidar');
    Route::post('/aposta/validar', "Agente\ApostaAgenteController@validarAposta");

    Route::get('/apostaJSON/{id}', "Agente\ApostaAgenteController@apostaJSON")
    	->where([
			'id' => '[0-9]+',
		]);
});

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
	Route::get('meus_palpites', 'SessaoController@meus_palpites');
});

Route::prefix('aposta')->group(function () {
	Route::post('fazerAposta', 'ApostaController@fazerAposta')->name('fazerAposta');
	Route::get('{id}', 'ApostaController@get')
		->where([
			'id' => '[0-9]+',
		]);
});

Route::get('/', 'EventoController@index');

Route::get('/teste', function(){
	return session()->all('palpites');
});

Route::get('evento/{id}/odds', 'EventoController@getOdds');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');