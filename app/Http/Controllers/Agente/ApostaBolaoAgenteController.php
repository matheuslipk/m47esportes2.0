<?php

namespace App\Http\Controllers\Agente;

use App\Bolao;
use App\ApostaBolao;
use App\PalpiteBolao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApostaBolaoAgenteController extends Controller{

	public function __construct(){
		return $this->middleware('auth');
	}

    public function apostas( Request $request ){
    	if( $request->filled('bolao_id') ){
    		$filtroApostas = [
	    		['agente_id', $request->user()->id],
	    		['bolao_id', $request->bolao_id],
	    	];
	    	$apostas = ApostaBolao::where($filtroApostas)
	    		->orderBy('quant_acertos', 'desc')
	    		->orderBy('id', 'desc')
	    		->take(100)
	    		->get();
	    	return view('agente.aposta_bolao.ultimas_apostas', compact('apostas'));
    	}

    	$filtroBolao = [
    		['status_id', Bolao::STATUS_DISPONIVEL]
    	];

    	$boloes = Bolao::where($filtroBolao)
    		->orderBy('id', 'desc')
    		->get();

    	foreach ($boloes as $bolao) {
    		$bolao->premiacao = $bolao->arrecadado($bolao->id) * (1- ($bolao->comissao_agente + $bolao->comissao_casa) );
    	}

    	return view('agente.aposta_bolao.ultimas_apostas', compact('boloes'));
    }

    public function showAposta( Request $request, $aposta_id ){

    	
    	$aposta = ApostaBolao::find($aposta_id);
    	if(isset($aposta)){
    		$palpites = PalpiteBolao::where('aposta_bolao_id', $aposta->id)->get();
    		foreach ($palpites as $palpite) {
    			$palpite->evento;
    			$palpite->evento->time1;
    			$palpite->evento->time2;
    		}
    	}

    	// return $palpites;
    	
    	return view('public.bolao', compact('aposta', 'palpites'));
    	

    }

}
