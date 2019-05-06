<?php

namespace App\Http\Controllers\Agente;

use App\Http\Controllers\Api\MinhaClasse;
use App\Bolao;
use App\ApostaBolao;
use App\PalpiteBolao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BolaoAgenteController extends Controller{

	public static $apostaBolao = null;


	public function __construct(){
		return $this->middleware('auth');
	}

    public function index(Request $request){
    	$filtro = [
    		['status_id', Bolao::STATUS_DISPONIVEL],    		
    	];

    	$bolaoDisponivel = Bolao::where($filtro)->get();

    	return view( 'agente.bolaoDisponivel', compact('bolaoDisponivel') );
    }

    public function fazerBolaoAgente(Request $request){

    	$request->validate([
    		'bolao_id' => 'required|integer',
    		'eventos' => 'required|array',
    		'nome_cliente' => 'string|nullable'
    	]);

    	$bolao = Bolao::find( $request->bolao_id );
    	if( !isset($bolao) ) return response('Erro');

    	foreach ($request->eventos as $evento_id => $palpite) {
    		if( $palpite <1 && $palpite>3 ) return response('Palpite inválido');
    	}

    	DB::transaction(function () use ( $request, $bolao ) {    	
		    $apostaBolao = new ApostaBolao();
		    $apostaBolao->bolao_id = $request->bolao_id;
		    $apostaBolao->agente_id = $request->user()->id;
		    $apostaBolao->nome = $request->nome_cliente;
		    $apostaBolao->valor_apostado = $bolao->valor_aposta;
		    $apostaBolao->data_criacao = MinhaClasse::timestamp_to_data_mysql( time() );
		    $apostaBolao->data_validacao = MinhaClasse::timestamp_to_data_mysql( time() );
		    $apostaBolao->status_id = ApostaBolao::STATUS_VALIDO;
		    $apostaBolao->save();

		    foreach ($request->eventos as $evento_id => $palpite) {
		    	$palpiteBolao = new PalpiteBolao();
		    	$palpiteBolao->aposta_bolao_id = $apostaBolao->id;
		    	$palpiteBolao->bolao_id = $bolao->id;
		    	$palpiteBolao->evento_id = $evento_id;
		    	$palpiteBolao->tipo_palpite_id = $palpite;
		    	$palpiteBolao->situacao_palpite_id = 3;
		    	$palpiteBolao->save();
		    }
		});


    	return redirect()->route('agente.bolaodisponivel');
    }
}
