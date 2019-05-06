<?php

namespace App\Http\Controllers\Admin;

use App\PalpiteBolao;
use App\ApostaBolao;
use App\Bolao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApostaBolaoAdminController extends Controller{

	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function apostas( Request $request ){
    	if( $request->filled('bolao_id') ){
    		$filtroApostas = [
	    		['agente_id', '<>', ''],
	    		['bolao_id', $request->bolao_id],
	    	];
	    	$apostas = ApostaBolao::where($filtroApostas)
	    		->orderBy('quant_acertos', 'desc')
	    		->orderBy('id', 'desc')
	    		->take(100)
	    		->get();
	    	return view('admin.aposta_bolao.ultimas_apostas', compact('apostas'));
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

    	return view('admin.aposta_bolao.ultimas_apostas', compact('boloes'));
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
