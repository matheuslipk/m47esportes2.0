<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Odd;

class SessaoController extends Controller{
	public function salvarPalpite(Request $request, $evento_id, $tipo_palpite_id){
		$odd = Odd::where([
			['evento_id',$evento_id],
			['tipo_palpite_id',$tipo_palpite_id],
		])->first();

		if(!isset($odd))return;

		$palpite = [
			'evento_id' => $evento_id,
			'tipo_palpite_id' => $tipo_palpite_id,
			'valor' => $odd->valor
		];

		if($request->session()->has('palpites')){			
			foreach ($request->session()->get('palpites') as $key => $palpite) {
				//If evento ja existe remove o palpite desse evento
				if($palpite['evento_id']==$evento_id){
					$request->session()->forget('palpites.'.$key);
				}
			}
		}

		$request->session()->push('palpites' , $palpite);
	}

	public function teste(Request $request){
		return $request->session()->get('palpites');
	}
    
}
