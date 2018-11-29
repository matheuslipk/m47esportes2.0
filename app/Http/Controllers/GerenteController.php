<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GerenteController extends Controller
{
    function __construct(){
		$this->middleware('auth:gerente');
	}

    function index(){
    	return view('gerente');
    }

    public function apostas(Request $request){
    	$apostas = Aposta::where([
    		['agente_id', "<>", '']
    	])
    	->take(50)
    	->orderBy('id', 'desc')
    	->get();

        $indexApostas = $this->getIndexApostas($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = $this->getApostasComStatusJSON($palpitesAgrupados);

    	return view('gerente.apostasgerente', compact('apostas', 'apostasComStatus'));
    }
}
