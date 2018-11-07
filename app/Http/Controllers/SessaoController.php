<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Odd;
use App\Evento;
use App\TipoPalpite;

class SessaoController extends Controller{
	public function salvarPalpite(Request $request, $evento_id, $tipo_palpite_id){		

		if($request->input('acao')=='add'){
			$this->addPalpite($request, $evento_id, $tipo_palpite_id);

		}elseif ($request->input('acao')=='remove') {
			$this->removePalpite($request, $evento_id);
		}	
			
	}

	public function meus_palpites(Request $request){
		$palpites = session('palpites');
		return $palpites;
	}

	private function addPalpite(&$request, $evento_id, $tipo_palpite_id){
		$this->removePalpite($request, $evento_id);
		$odd = Odd::where([
			['evento_id',$evento_id],
			['tipo_palpite_id',$tipo_palpite_id],
		])->first();

		if(!isset($odd))return;

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
	}
    
}
