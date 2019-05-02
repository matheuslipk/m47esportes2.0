<?php

Route::prefix('admin')->group(function () {

	//Inicio BOLÃO
	Route::get('/bolao', 'Admin\BolaoAdminController@index')->name('admin_listabolaos');
	Route::get('/bolao/{id}', 'Admin\BolaoAdminController@show')->where(['id' => '[0-9]+'])->name('admin_showbolao');
	Route::put('/bolao/atualizar/{id}', 'Admin\BolaoAdminController@atualizar')->where(['id' => '[0-9]+'])->name('admin_atualizarbolao');
	Route::get('/bolao/listar', 'Admin\BolaoAdminController@listar');
	Route::get('/bolao/novo', 'Admin\BolaoAdminController@novo')->name('admin_novobolao');
	Route::post('/bolao/novo', 'Admin\BolaoAdminController@create');

	Route::get('/evento_bolaos', 'Admin\EventoBolaoAdminController@getEventosBolaoJson')->name('admin.evento_bolaos');
	Route::get('/evento_bolaos/novo', 'Admin\EventoBolaoAdminController@showFormCadastro')->name('admin.evento_bolaos.novo');
	Route::post('/evento_bolaos/store', 'Admin\EventoBolaoAdminController@store')->name('admin.evento_bolaos.store');
	//FIM BOLÃO

	Route::get('/', "AdminController@index")->name('adminhome');
	Route::get('/apostas', "Admin\ApostaAdminController@apostas")->name('adminapostas');
	Route::get('/apostasJSON', "Admin\ApostaAdminController@apostasJSON");

    Route::get('/login', "AuthAdmin\LoginAdminController@showLoginForm")->name('adminlogin');
	Route::post('/login', "AuthAdmin\LoginAdminController@login");

    Route::get('/register', "AuthAdmin\RegistroAdminController@showRegistroForm")->name('adminregistro');
    Route::post('/register', "AuthAdmin\RegistroAdminController@registrar");

    Route::get('/eventos', "Admin\EventoAdminController@index")->name('admineventos');
    Route::get('/eventos/editar', "Admin\EventoAdminController@showEditarEventos")->name('admin_editarevento');
    Route::post('/eventos/editar', "Admin\EventoAdminController@editarEventos");
    Route::get('/eventos/atualizar', "Admin\EventoAdminController@showAtualizarResultadoEventos")->name('atualizareventos');
    Route::get('/eventos/cadastrar', "Admin\EventoAdminController@showCadastrarEventos")->name('cadastroeventos');
    Route::get('/eventos/getJSON', "Admin\EventoAdminController@getEventosJSON")->name('getEventosJSONAdmin');

    Route::get('/evento/anular/{id}', "Admin\EventoAdminController@anularevento")->name('anularevento')->where(['id' => '[0-9]+']);

    Route::get('/evento/atualizarNaApi', "Admin\EventoAdminController@atualizarEventoApi");

    Route::get('/ligas', "Admin\LigaAdminController@index")->name('adminligas');
    Route::get('/liga/{id}', "Admin\LigaAdminController@getLiga")->name('adminliga');
    Route::post('/liga/{id}', "Admin\LigaAdminController@update")->name('adminliga');

    Route::get('/relatorio', "Admin\RelatorioAdminController@index")->name("relatorio_admin");
    Route::post('/relatorio', "Admin\RelatorioAdminController@relatorio");

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

		Route::post('/{id}/editar_config', "Admin\AgenteAdminController@editarConfigAgente")->name('editarconfig_agente')
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

	Route::prefix('dashboard')->group(function () {
		Route::get('/', "Admin\DashboardAdminController@index")->name('admin_dashboard');
	});

});

Route::prefix('agente')->group(function () {
	Route::get('/bolao_disponiveis', 'Agente\BolaoAgenteController@index')->name('agente.bolaodisponivel');


    Route::get('/apostas', 'Agente\ApostaAgenteController@apostas')->name('agenteapostas');
    Route::get('/apostasJSON', "Agente\ApostaAgenteController@apostasJSON");

    Route::get('/aposta/validar', "Agente\ApostaAgenteController@showValidar")->name('agentevalidar');
    Route::post('/aposta/validar', "Agente\ApostaAgenteController@validarAposta");

    Route::get('/apostaJSON/{id}', "Agente\ApostaAgenteController@apostaJSON")
    	->where([
			'id' => '[0-9]+',
		]);

	Route::get('/relatorio', "Agente\RelatorioAgenteController@showRelatorio")->name('relatorio_agente');
	Route::post('/relatorio', "Agente\RelatorioAgenteController@relatorio");

	Route::get('/conta', "Agente\ContaAgenteController@showConta")->name('agenteconta');
	Route::post('/conta', "Agente\ContaAgenteController@atualizarConta");
	Route::post('/atualizarsenha', "Agente\ContaAgenteController@atualizarsenha")->name("agente-atualizarsenha");
});

Route::prefix('gerente')->group(function () {
	Route::get('/', "GerenteController@index")->name('gerentehome');
	Route::get('/apostas', "Gerente\ApostaGerenteController@apostas")->name('gerenteapostas');
	Route::get('/apostasJSON', "Gerente\ApostaGerenteController@apostasJSON");

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
    Route::get('/relatorio', "Gerente\RelatorioGerenteController@index")->name("relatorio_gerente");
	Route::post('/relatorio', "Gerente\RelatorioGerenteController@relatorio");
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
	Route::get('/comprovante/{controle}', 'ApostaController@getComprovante')->name('viewcomprovante');
	Route::get('{id}', 'ApostaController@get')->name('viewaposta');
});

Route::get('/', 'EventoController@index')->name('index');

Route::get('/regras', function(){
	return view('regras');
})->name('regras');


//Ajax
Route::prefix('ajax/admin')->group(function(){
	Route::get('/dashboard/getApostasPorSemana', "Admin\DashboardAdminController@getApostasPorSemana")->name('ajax_admin_getApostasPorSemana');
	Route::get('/dashboard/getApostasPorAgente', "Admin\DashboardAdminController@getApostasPorAgente")->name('ajax_admin_getApostasPorAgente');
	Route::post('/bolao/{id}/addEventos', 'Admin\BolaoAdminController@addEventos')->where(['id' => '[0-9]+'])->name('admin_bolaoaddeventos');
	Route::post('/bolao/removeEventos', 'Admin\BolaoAdminController@removeEventos')->name('admin_bolaoremoveeventos');
});

Route::get('evento/{id}/odds', 'EventoController@getOdds');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/erro/{id}', 'CodigoErroController@index')->name('erro');

Route::post("logout", "SessaoController@logout")->name('logout');