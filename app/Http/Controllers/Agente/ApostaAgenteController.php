<?php

namespace App\Http\Controllers\Agente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;

class ApostaAgenteController extends Controller{
	public function __construct(){
		return $this->middleware('auth');
	}

    public function apostas(Request $request){
    	$apostas = Aposta::where([
    		['agente_id', Auth::user()->id]
    	])
    	->take(20)
    	->orderBy('id', 'desc')
    	->get();
    	return view('agente.apostas', compact('apostas'));
    }
}
