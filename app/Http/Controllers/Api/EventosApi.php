<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\Api\ConverterApi;
use App\Evento;
use App\Odd;

class EventosApi extends Controller
{

	function __construct(){
		$this->middleware('auth:web-admin');
	}

    function eventos_futuros(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/upcoming";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   if($request->filled('sport_id')){
	   	$variaveis["sport_id"] = $request->input('sport_id');
	   }else{
	   	$variaveis["sport_id"] = 1;
	   }
	   

	   $variaveis["league_id"] = $request->input('league_id');
	   $variaveis["day"] = str_replace('-', '', $request->input('data_eventos'));
	   $variaveis["page"] = $request->input('page');
	   
	   $resultado = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   $arrayEventos = json_decode($resultado);


	   	foreach ($arrayEventos->results as $eventoApi) {
	   		$this->cadastrar_evento($eventoApi, $variaveis["sport_id"]);
	  	}

	   return $resultado;
	}

	public function pre_math_odds(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/start_sp";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $evento = Evento::find( $request->input('event_id') );
	   $variaveis["FI"] = $evento->FI_365;
	   
	   $odds = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   $objetoOdds = json_decode($odds);

	   if(isset($objetoOdds->results[0])){
	   		$oddsConvertidas = Odd::inserir_odds( $objetoOdds->results[0], $evento->id );
	   		$oddsConvertidas2 = $this->pre_math_odds2($request);
	   		return json_encode( (array) $oddsConvertidas);
	   }
	   
	   return 'Nenhum evento encontrado';
	   
	}

	public function pre_math_odds2(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/start_sp";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $variaveis["FI"] = $request->input('FI');
	   $variaveis["event_id"] = $request->input('event_id');
	   $variaveis["rawJSON"] = 1;
	   
	   $odds = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   $objetoOdds = json_decode($odds);

	   if( isset($objetoOdds->results[0]) ){
	   		$oddsConvertidas = Odd::inserir_odds2( $objetoOdds->results[0], $request->input('event_id') );
	   		return json_encode( (array) $oddsConvertidas);
	   }
	   
	   return 'Nenhum evento encontrado';
	   
	}

	public function resultado(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/result";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $variaveis["FI"] = $request->input('FI');
	   $variaveis["event_id"] = $request->input('FI');
	   
	   $resultado = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   
	   return $resultado;
	}

	private function cadastrar_evento($eventoApi, $esporte_id){
		try{
			$evento = new Evento();
		   	$evento->id = $eventoApi->our_event_id;
		   	$evento->esporte_id = $esporte_id;
		   	$evento->data =  MinhaClasse::timestamp_to_data_mysql($eventoApi->time);
		   	$evento->status_evento_id = $eventoApi->time_status;
		   	$evento->liga_id = $eventoApi->league->id;
		   	$evento->time1_id = $eventoApi->home->id;
		   	$evento->time2_id = $eventoApi->away->id;
		   	$evento->FI_365 = $eventoApi->id;
		   	$evento->save();
		}catch(QueryException $e){
			return "Deu errom heim";
		}		
	}	
}
