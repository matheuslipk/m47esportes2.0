<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConfigAgente;
use App\ConfigGlobal;
use App\Time;
use App\Evento;
use App\Aposta;
use App\Palpite;
use App\CatPalpite;
use App\TipoPalpite;
use App\SituacaoPalpite;
use App\Http\Controllers\Api\MinhaClasse;
use Illuminate\Support\Facades\Auth;

class ApostaController extends Controller{

	public function get(Request $request, $aposta_id){
		$aposta = Aposta::find($aposta_id);
		if(!isset($aposta)){
			return "Aposta não encontrada";
		}
    	$tipoPalpites = TipoPalpite::all();
    	$catPalpites = CatPalpite::all();
    	$situacaoPalpites = SituacaoPalpite::all();
    	$eventos = Evento::find($this->getIndexEventos($aposta->palpites));
    	$times = Time::find($this->getIndexTimes($eventos));

    	// return $times;

		if(isset($aposta)){
			foreach ($aposta->palpites as $palpite) {
				$palpite->tipo_palpite = $tipoPalpites->where('id', $palpite->tipo_palpite_id)->first();
				$palpite->tipo_palpite->cat_palpite = $catPalpites->where('id', $palpite->tipo_palpite->cat_palpite_id)->first();
				$palpite->evento = $eventos->where('id', $palpite->evento_id)->first();
				$palpite->evento->time1 = $times->where('id', $palpite->evento->time1_id)->first();
				$palpite->evento->time2 = $times->where('id', $palpite->evento->time2_id)->first();
				$palpite->situacao_palpite = $situacaoPalpites->where('id', $palpite->situacao_palpite_id)->first();
			}
			return view('public.aposta', compact('aposta'));
		}else{
			return 'Aposta não encontrada';
		}
	}

    public function fazerAposta(Request $request){    	
    	if($request->session()->has('palpites') && count($request->session()->get('palpites'))>0 ){
    		if(Auth::check()){
                $aposta = $this->apostaAgente($request);
                return redirect('/aposta/'.$aposta->id);
    			
	    	}else{
                $aposta = $this->apostaPublica($request);
	    		return view('public.solicitacao_aposta', compact('aposta'));
	    	}
    	}
    }


    private function apostaPublica(Request $request){
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
    	$aposta->data_aposta = MinhaClasse::timestamp_to_data_mysql(time());
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
    		$p->situacao_palpite_id = 3;
    		$p->save();
    	}
    	$this->limparSessaoPalpites($request);    	
    	return $aposta;
    }

    private function apostaAgente(Request $request){
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

        $confAgente = ConfigAgente::where('agente_id', Auth::user()->id)->get();
        if(count($confAgente)>=1){
            if($cotaTotal<6){
                $linhaConfig = $confAgente->where('tipo_config_id', 9)->first();
            }elseif($cotaTotal>=6 && $cotaTotal<12){
                $linhaConfig = $confAgente->where('tipo_config_id', 10)->first();
            }elseif($cotaTotal>=12 && $cotaTotal<20){
                $linhaConfig = $confAgente->where('tipo_config_id', 11)->first();
            }elseif($cotaTotal>=20){
                $linhaConfig = $confAgente->where('tipo_config_id', 12)->first();
            }
        }else{
            $confGlobal = ConfigGlobal::all();
            if($cotaTotal<6){
                $linhaConfig = $confGlobal->where('tipo_config_id', 9)->first();
            }elseif($cotaTotal>=6 && $cotaTotal<12){
                $linhaConfig = $confGlobal->where('tipo_config_id', 10)->first();
            }elseif($cotaTotal>=12 && $cotaTotal<20){
                $linhaConfig = $confGlobal->where('tipo_config_id', 11)->first();
            }elseif($cotaTotal>=20){
                $linhaConfig = $confGlobal->where('tipo_config_id', 12)->first();
            }            
        }
        $comissaoAgente = $linhaConfig['valor'];

    	$aposta = new Aposta();
    	$aposta->agente_id = Auth::user()->id;
    	$aposta->nome = $request->input('nomeAposta');
    	$aposta->data_aposta = MinhaClasse::timestamp_to_data_mysql(time());
    	$aposta->cotacao_total = $cotaTotal;
    	$aposta->valor_apostado = $request->input('valorAposta');
        $aposta->comissao_agente = $comissaoAgente;
    	$aposta->premiacao = $premiacao;
    	$aposta->ganhou = 0;
    	$aposta->save();

    	foreach ($request->session()->get('palpites') as $palpite) {
    		$p = new Palpite();
    		$p->aposta_id = $aposta->id;
    		$p->evento_id = $palpite['evento_id'];
    		$p->tipo_palpite_id = $palpite['tipo_palpite']['id'];
    		$p->cotacao = $palpite['valor'];
    		$p->situacao_palpite_id = 3;
    		$p->save();
    	}
    	$this->limparSessaoPalpites($request);
    	return $aposta;
    }

    private function limparSessaoPalpites(Request $request){
    	$request->session()->forget('palpites');
    }

    private function getIndexEventos($palpites){
    	$indexs = [];
    	foreach ($palpites as $palpite) {
    		$indexs[] = $palpite->evento_id;
    	}
    	return $indexs;
    }

    private function getIndexTimes($eventos){
    	$indexs = [];
    	foreach ($eventos as $evento) {
    		$indexs[] = $evento->time1_id;
    		$indexs[] = $evento->time2_id;
    	}
    	return $indexs;
    }
}
