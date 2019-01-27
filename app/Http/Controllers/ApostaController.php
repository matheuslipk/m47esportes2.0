<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CodigoErro;
use App\ConfigAgente;
use App\ConfigGlobal;
use App\Time;
use App\Evento;
use App\Aposta;
use App\Palpite;
use App\CatPalpite;
use App\TipoPalpite;
use App\SituacaoPalpite;
use App\Score;
use App\ScoreT1;
use App\ScoreT2;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\Agente\ApostaAgenteController;
use Illuminate\Support\Facades\Auth;

class ApostaController extends Controller{

	public function get(Request $request, $aposta_id){
		$aposta = Aposta::find($aposta_id);
        if(!isset($aposta) || $aposta->agente_id == ''){
            return redirect('/');
        }
        $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
        $aposta->data_validacao = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_validacao);

		
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
                $palpite->evento->data = MinhaClasse::data_mysql_to_datahora_formatada($palpite->evento->data);
				$palpite->evento->time1 = $times->where('id', $palpite->evento->time1_id)->first();
				$palpite->evento->time2 = $times->where('id', $palpite->evento->time2_id)->first();
				$palpite->situacao_palpite = $situacaoPalpites->where('id', $palpite->situacao_palpite_id)->first();
                if($this->isPalpiteTempoCompleto($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = Score::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'completo';
                }elseif($this->isPalpiteTempo1($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = ScoreT1::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'tempo1';
                }elseif($this->isPalpiteTempo2($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = ScoreT2::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'tempo2';
                }
			}
            // return $aposta;
			return view('public.aposta', compact('aposta'));
		}else{
			return 'Aposta não encontrada';
		}
	}

    public function getComprovante(Request $request, $controle){
        $aposta = Aposta::where("controle", $controle)->first();
        if(!isset($aposta) || $aposta->agente_id == ''){
            return redirect('/');
        }
        $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
        $aposta->data_validacao = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_validacao);

        
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
                $palpite->evento->data = MinhaClasse::data_mysql_to_datahora_formatada($palpite->evento->data);
                $palpite->evento->time1 = $times->where('id', $palpite->evento->time1_id)->first();
                $palpite->evento->time2 = $times->where('id', $palpite->evento->time2_id)->first();
                $palpite->situacao_palpite = $situacaoPalpites->where('id', $palpite->situacao_palpite_id)->first();
                if($this->isPalpiteTempoCompleto($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = Score::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'completo';
                }elseif($this->isPalpiteTempo1($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = ScoreT1::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'tempo1';
                }elseif($this->isPalpiteTempo2($palpite->tipo_palpite->cat_palpite_id)){
                    $palpite->evento->scores = ScoreT2::where('evento_id', $palpite->evento_id)->first();
                    $palpite->tempoJogo = 'tempo2';
                }
            }
            // return $aposta;
            return view('public.aposta', compact('aposta'));
        }else{
            return 'Aposta não encontrada';
        }
    }

    public function geJSON($aposta_id){
        $aposta = Aposta::find($aposta_id);
        if(!isset($aposta)){
            return NULL;
        }
        $tipoPalpites = TipoPalpite::all();
        $catPalpites = CatPalpite::all();
        $situacaoPalpites = SituacaoPalpite::all();
        $eventos = Evento::find($this->getIndexEventos($aposta->palpites));
        $times = Time::find($this->getIndexTimes($eventos));

        foreach ($aposta->palpites as $palpite) {
            $palpite->tipo_palpite = $tipoPalpites->where('id', $palpite->tipo_palpite_id)->first();
            $palpite->tipo_palpite->cat_palpite = $catPalpites->where('id', $palpite->tipo_palpite->cat_palpite_id)->first();
            $palpite->evento = $eventos->where('id', $palpite->evento_id)->first();
            $palpite->evento->time1 = $times->where('id', $palpite->evento->time1_id)->first();
            $palpite->evento->time2 = $times->where('id', $palpite->evento->time2_id)->first();
            $palpite->situacao_palpite = $situacaoPalpites->where('id', $palpite->situacao_palpite_id)->first();
        }
        return $aposta;
    }

    public function fazerAposta(Request $request){    	
    	if($request->session()->has('palpites') && count($request->session()->get('palpites'))>0 ){
    		if(Auth::check()){
                $apostaAgente = new ApostaAgenteController();

                $limiteLiberado = $apostaAgente->isLimitesLiberado(Auth::user()->id, $request->input('valorAposta'));

                if($limiteLiberado!==true){
                    return $limiteLiberado;
                }

                $aposta = $apostaAgente->apostaAgente($request);

                if(is_integer($aposta)){
                    $erro = CodigoErro::find($aposta);
                    return redirect()->route('erro', $erro);
                }

                

                return redirect('/aposta/'.$aposta->id);
    			
	    	}else{
                
                $aposta = $this->apostaPublica($request);
                if(is_integer($aposta)){
                    $erro = CodigoErro::find($aposta);
                    return redirect()->route('erro', $erro);
                }
	    		return view('public.solicitacao_aposta', compact('aposta'));
	    	}
    	}
    }
    private function apostaPublica(Request $request){
        $valorMaxApostaPadrao = ConfigGlobal::where('tipo_config_id', 4)->first()->valor;
		$cotaTotal = 1;
		$quantPalpites = 0;

		foreach ($request->session()->get('palpites') as $palpite) {
    		$cotaTotal *= $palpite['valor'];
    		$quantPalpites++;
    	}
    	if($cotaTotal>800){
    		$cotaTotal=800;
    	}

        $valorApostado = $request->input('valorAposta');

        if($valorApostado > $valorMaxApostaPadrao){
            if($cotaTotal < 3.5){
                return CodigoErro::ValorMinCota;
            }            
        }

        if($valorApostado >= 500 ){
            $valorApostado = 500;
        }

    	$premiacao = $valorApostado*$cotaTotal;
    	if($premiacao>8000){
    		$premiacao=8000;
    	}
        

    	$aposta = new Aposta();
    	$aposta->nome = $request->input('nomeAposta');
    	$aposta->data_aposta = MinhaClasse::timestamp_to_data_mysql(time());
    	$aposta->cotacao_total = $cotaTotal;
    	$aposta->valor_apostado = $valorApostado;
    	$aposta->premiacao = $premiacao;
    	$aposta->ganhou = 0;
    	$aposta->save();

        Aposta::where('id', $aposta->id)->update(['controle'=>Aposta::getControle($aposta->id)]);

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

    private function isPalpiteTempo1($catPalpite){
        if($catPalpite >=101 && $catPalpite <=108){
            return true;
        }
        return false;
    }
    private function isPalpiteTempo2($catPalpite){
        if($catPalpite >=201){
            return true;
        }
        return false;
    }
    private function isPalpiteTempoCompleto($catPalpite){
        if($catPalpite <=17){
            return true;
        }
        return false;
    }
}
