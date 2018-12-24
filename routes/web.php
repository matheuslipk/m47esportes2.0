<?php

Route::prefix('admin')->group(function () {
	Route::get('/', "AdminController@index")->name('adminhome');
	Route::get('/apostas', "Admin\ApostaAdminController@apostas")->name('adminapostas');
	Route::get('/apostasJSON', "Admin\ApostaAdminController@apostasJSON");

    Route::get('/login', "AuthAdmin\LoginAdminController@showLoginForm")->name('adminlogin');
	Route::post('/login', "AuthAdmin\LoginAdminController@login");

    Route::get('/register', "AuthAdmin\RegistroAdminController@showRegistroForm")->name('adminregistro');
    Route::post('/register', "AuthAdmin\RegistroAdminController@registrar");

    Route::get('/eventos', "Admin\EventoAdminController@index")->name('admineventos');
    Route::get('/eventos/atualizar', "Admin\EventoAdminController@showAtualizarResultadoEventos")->name('atualizareventos');
    Route::get('/eventos/cadastrar', "Admin\EventoAdminController@showCadastrarEventos")->name('cadastroeventos');
    Route::get('/eventos/getJSON', "Admin\EventoAdminController@getEventosJSON")->name('getEventosJSONAdmin');

    Route::get('/evento/atualizarNaApi', "Admin\EventoAdminController@atualizarEventoApi");

	Route::post('/odds/remover', "Admin\OddsAdminController@removerOddsByEvento");    

	//('/admin/agentes')
	Route::prefix('agentes')->group(function () {
		Route::get('/', "Admin\AgenteAdminController@index")->name('listaagentes');
		Route::get('/register', "Admin\AgenteAdminController@showRegistroForm")->name('agenteregistro');
		Route::post('/register', "Admin\AgenteAdminController@registrar");

		Route::get('/{id}/editar', "Admin\AgenteAdminController@editar")->name('editaragente')
			->where([
				'id' => '[0-9]+',
			]);

		Route::post('/{id}/salvar', "Admin\AgenteAdminController@salvar")->name('salvaragente')
			->where([
				'id' => '[0-9]+',
			]);

		Route::get('/by_gerente', "Admin\AgenteAdminController@getAgentesByGerente")->name('agentes_by_gerente');

	});

	//('/admin/gerentes')
	Route::prefix('gerentes')->group(function () {
		Route::get('/', "Admin\GerenteAdminController@index")->name('listagerentes');
		Route::get('/{id}/editar', "Admin\GerenteAdminController@editar")->name('editargerente')
			->where([
				'id' => '[0-9]+',
			]);

		Route::get('/{id}/salvar', "Admin\GerenteAdminController@salvar")->name('salvargerente')
			->where([
				'id' => '[0-9]+',
			]);
	});

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

	Route::get('/relarotio', "Agente\RelatorioAgenteController@showRelatorio")->name('relatorio_agente');
	Route::post('/relarotio', "Agente\RelatorioAgenteController@relatorio");

	Route::get('/conta', "Agente\ContaAgenteController@showConta")->name('agenteconta');
	Route::post('/conta', "Agente\ContaAgenteController@atualizarConta");
	Route::post('/atualizarsenha', "Agente\ContaAgenteController@atualizarsenha")->name("agente-atualizarsenha");
});

Route::prefix('gerente')->group(function () {
	Route::get('/', "GerenteController@index")->name('gerentehome');
	Route::get('/apostas', "Gerente\ApostaGerenteController@apostas")->name('gerenteapostas');

    Route::get('/login', "AuthGerente\LoginGerenteController@showLoginForm")->name('gerentelogin');
	Route::post('/login', "AuthGerente\LoginGerenteController@login");


	Route::get('/register', "AuthGerente\RegistroGerenteController@showRegistroForm")->name('gerenteregistro');
    Route::post('/register', "AuthGerente\RegistroGerenteController@registrar");

    Route::prefix('agentes')->group(function () {
    	Route::get('/', "Gerente\AgenteGerenteController@index")->name('listaagentes_gerente');
    	Route::get('/register', "Gerente\AgenteGerenteController@showRegistroForm")->name('agenteregistro_gerente');
		Route::post('/register', "Gerente\AgenteGerenteController@registrar");

		Route::get('/{id}/apostas', "Gerente\AgenteGerenteController@verAgente_gerente")->name('veragente_gerente')
			->where([
				'evento' => '[0-9]+',
				'palpite' => '[0-9]+',
			]);
    });
});

Route::prefix('api365')->group(function () {
    Route::get('upcoming', 'Api\EventosApi@eventos_futuros')->name('upcoming');
    Route::get('prematch', 'Api\EventosApi@pre_math_odds');
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
	Route::get('{id}', 'ApostaController@get')->name('viewaposta');
});

Route::get('/', 'EventoController@index');

Route::get('/teste', function(){
	return session()->all('palpites');
});

Route::get('evento/{id}/odds', 'EventoController@getOdds');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');