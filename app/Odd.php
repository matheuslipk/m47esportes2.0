<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Api\ConverterApi;
use App\Http\Controllers\Api\MinhaClasse;

class Odd extends Model
{


	public function cat_palpite(){
		return $this->belongsTo('App\CatPalpite');
	}
	public function tipo_palpite(){
		return $this->belongsTo('App\TipoPalpite');
	}

    public static function inserir_odds($odds, $event_id){
		$oddsConvertidas = ConverterApi::converterOdds($odds);
		foreach ($oddsConvertidas->cat_palpites as $cat_palpite) {
			foreach ($cat_palpite->odds as $odd) {
				$oddBanco = Odd::where([
					['evento_id', $oddsConvertidas->evento_id],
					['tipo_palpite_id', $odd['tipo_palpite_id']],
				])->first();

				if (isset($oddBanco)) {
					$oddBanco->valor = $odd['taxa'];
					$oddBanco->updated_at = MinhaClasse::timestamp_to_data_mysql(time());
					$oddBanco->save();					
				}else{
					$o = new Odd();
					$o->evento_id = $oddsConvertidas->evento_id;
					$o->cat_palpite_id = $cat_palpite->categoria_id;
					$o->tipo_palpite_id = $odd['tipo_palpite_id'];
					$o->valor = $odd['taxa'];
					$o->save();
				}
					
			}
		}
		return $oddsConvertidas;
	}
}
