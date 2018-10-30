<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MinhaClasse;

class EventosApi extends Controller
{
    function eventos_futuros(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/upcoming";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $variaveis["sport_id"] = 1;
	   $variaveis["league_id"] = $request->input('league_id');
	   $variaveis["day"] = $request->input('day');
	   $variaveis["page"] = $request->input('page');
	   
	   $resultado = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   
	   return $resultado;
	}

	function pre_math_odds(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/start_sp";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $variaveis["FI"] = $request->input('FI');
	   $variaveis["event_id"] = $request->input('event_id');
	   
	   $resultado = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   
	   return $resultado;
	}

	function resultado(Request $request){
	   $url = "https://api.betsapi.com/v1/bet365/result";
	   $metodo = "GET";
	   $variaveis["token"] = MinhaClasse::get_token();
	   $variaveis["FI"] = $request->input('FI');
	   $variaveis["event_id"] = $request->input('event_id');
	   
	   $resultado = MinhaClasse::fazer_requisicao($url, $variaveis, $metodo); 
	   
	   return $resultado;
	}
}
