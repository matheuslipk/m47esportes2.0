<?php

namespace App\Http\Controllers\Gerente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\Agente;
use App\Palpite;

class ApostaGerenteController extends Controller
{
    function __construct(){
		$this->middleware('auth:gerente');
	}

    function index(){
    	return view('gerente');
    }

    public function apostas(Request $request){
        $gerente = Auth::guard('gerente')->user();
        $agentes = Agente::where('gerente_id', $gerente->id)->get();
        $indexAgentes = $this->getIndexes($agentes);

    	$apostas = Aposta::whereIn('agente_id', $indexAgentes)
    	->take(50)
    	->orderBy('id', 'desc')
    	->get();

        $indexApostas = $this->getIndexes($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

    	return view('gerente.apostasgerente', compact('apostas', 'apostasComStatus', 'gerente'));
    }

    private function getIndexes($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }

    
}
