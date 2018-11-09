<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aposta;
use App\Palpite;

class ApostaController extends Controller{

	public function get(Request $request, $aposta_id){
		$aposta = Aposta::find($aposta_id);
		if(isset($aposta)){
			foreach ($aposta->palpites as $palpite) {
				$palpite->tipo_palpite->cat_palpite;
				$palpite->evento->time1;
				$palpite->evento->time2;
				$palpite->situacao_palpite;
			}
			// return $aposta;
			return view('public.aposta', compact('aposta'));
		}else{
			return 'Aposta não encontrada';
		}
	}

    public function fazerAposta(Request $request){    	
    	if($request->session()->has('palpites') && count($request->session()->get('palpites'))>0 ){
    		$cotaTotal = 1;
    		$quantPalpites = 0;
    		foreach ($request->session()->get('palpites') as $palpite) {
	    		$cotaTotal *= $palpite['valor'];
	    		$quantPalpites++;
	    	}
	    	if($cotaTotal>800){
	    		$cotaTotal=800;
	    	}

	    	$premiacao = $request->input('valorAposta')*$cotaTotal;
	    	if($premiacao>8000){
	    		$premiacao=8000;
	    	}

	    	$aposta = new Aposta();
	    	$aposta->nome = $request->input('nomeAposta');
	    	$aposta->cotacao_total = $cotaTotal;
	    	$aposta->valor_apostado = $request->input('valorAposta');
	    	$aposta->premiacao = $premiacao;
	    	$aposta->ganhou = 0;
	    	$aposta->save();

	    	foreach ($request->session()->get('palpites') as $palpite) {
	    		$p = new Palpite();
	    		$p->aposta_id = $aposta->id;
	    		$p->evento_id = $palpite['evento_id'];
	    		$p->tipo_palpite_id = $palpite['tipo_palpite']['id'];
	    		$p->cotacao = $palpite['valor'];
	    		$p->situacao_palpite_id = 1;
	    		$p->save();
	    	}
	    	$this->limparSessaoPalpites($request);
	    	return redirect('/');
    	}
	    	
    }

    private function limparSessaoPalpites(Request $request){
    	$request->session()->forget('palpites');
    }
}
