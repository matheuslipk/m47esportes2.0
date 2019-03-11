<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\MinhaClasse;
use App\Odd;
use App\Evento;
use App\TipoPalpite;
use App\ConfigGlobal;

class SessaoController extends Controller{
	public function salvarPalpite(Request $request, $evento_id, $tipo_palpite_id){		

		if($request->input('acao')=='add'){
			$retorno = $this->addPalpite($request, $evento_id, $tipo_palpite_id);
			

		}elseif ($request->input('acao')=='remove') {
			$retorno = $this->removePalpite($request, $evento_id);
		}	
		return $retorno;
			
	}

	public function meus_palpites(Request $request){
		$palpites = session('palpites');
		return $palpites;
	}

	private function addPalpite(&$request, $evento_id, $tipo_palpite_id){
		$this->removePalpite($request, $evento_id);
		$tempPalpites = $request->session()->get('palpites');

		if($request->session()->has('palpites') && count($request->session()->get('palpites'))>=14){
			return [
					'sucesso' => false,
					'erro' => 'Limite máximo de 14 palpites',
				];
		}

		if($this->eventoIniciado($evento_id)){
			return [
				'sucesso' => false,
				'erro' => 'Evento iniciado ou removido'
			];
		}

		$odd = Odd::where([
			['evento_id',$evento_id],
			['tipo_palpite_id',$tipo_palpite_id],
		])->first();


		if(!isset($odd)) {
			return [
				'sucesso' => false,
				'erro' => 'Odd não encontrada'
			];
		}

		$ultimaAtualizacao = MinhaClasse::date_mysql_to_timestamp($odd->updated_at);
		$horaAtual = time();

		$tempoAtualizacao = ConfigGlobal::where("tipo_config_id", 13)->first()->valor;

		if($horaAtual >= ($ultimaAtualizacao + $tempoAtualizacao * 60)){
			MinhaClasse::atualizarOdds($evento_id);
			$odd = Odd::where([
				['evento_id',$evento_id],
				['tipo_palpite_id',$tipo_palpite_id],
			])->first();
		}

		$evento = Evento::find($evento_id);
		$tipoPalpite = TipoPalpite::find($tipo_palpite_id);

		$evento->time1;
		$evento->time2;
		$tipoPalpite->cat_palpite;

		$palpite = [
			'evento_id' => $evento_id,			
			'tipo_palpite' => $tipoPalpite,
			'valor' => $odd->valor,
			'evento' => $evento,
		];

		$request->session()->push('palpites' , $palpite);
		return ['sucesso' => true];
	}

	private function removePalpite(&$request, $evento_id){
		if($request->session()->has('palpites')){			
			foreach ($request->session()->get('palpites') as $key => $palpite) {
				//If evento ja existe remove o palpite desse evento
				if($palpite['evento_id']==$evento_id){
					$request->session()->forget('palpites.'.$key);
				}
			}
		}
		return ['sucesso' => true];
	}

	private function eventoIniciado($evento_id){
		$evento = Evento::find($evento_id);
		$timestampEvento = MinhaClasse::date_mysql_to_timestamp($evento->data);
		return (time() >= $timestampEvento);
	}
    
}
