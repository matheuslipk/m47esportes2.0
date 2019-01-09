<?php

namespace App\Http\Controllers\Agente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\ConfigAgente;
use App\ConfigGlobal;
use App\Palpite;
use App\Http\Controllers\Api\MinhaClasse;
use App\Http\Controllers\ApostaController;

class RelatorioAgenteController extends Controller{
	public function __construct(){
		return $this->middleware('auth');
	}

    public function showRelatorio(Request $request){
        return view('agente.relatorio');
    }

    public function relatorio(Request $request){
        $agente = Auth::user();
        $dataInicio = $request->input('data_inicio')."T00:00:00";
        $dataFim = $request->input('data_fim')."T23:59:59";

        $apostas = Aposta::where([
            ['agente_id', $agente->id],
            ['data_aposta', '>=', $dataInicio],
            ['data_aposta', '<=', $dataFim],
        ])->get();

        $indexApostas = $this->getIndexApostas($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

        return compact('apostas', 'apostasComStatus');
    }

    public function getIndexApostas($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }


}
