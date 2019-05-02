<?php

namespace App\Http\Controllers\Agente;

use App\Bolao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BolaoAgenteController extends Controller{
    public function index(Request $request){
    	$filtro = [
    		['status_id', Bolao::STATUS_DISPONIVEL],    		
    	];

    	$bolaoDisponivel = Bolao::where($filtro)->get();

    	return view( 'agente.bolaoDisponivel', compact('bolaoDisponivel') );
    }
}
