<?php

namespace App\Http\Controllers\Gerente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\Agente;
use App\Palpite;
use App\Http\Controllers\Api\MinhaClasse;

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

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
            $aposta->agente = $agentes->where('id', $aposta->agente_id)->first();
        }

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        $gerente->agentes = $agentes;

        // return $gerente;

    	return view('gerente.apostasgerente', compact('apostas', 'apostasComStatus', 'gerente'));
    }

    public function apostasJSON(Request $request){
        $gerente = Auth::guard('gerente')->user();
        $dataInico = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');
        $agente = $request->input('agente');

        if($agente == 0){
            $agentes = Agente::where('gerente_id', $gerente->id)->get();
            $apostas = Aposta::whereIn('agente_id', $this->getIndexes($agentes))
            ->where([
                ['data_aposta', ">=", $dataInico."T00:00:00"],
                ['data_aposta', "<=", $dataFinal."T23:59:59"],
            ])
            ->take(150)
            ->orderBy('id', 'desc')
            ->get();
        }
        
        else{
            $a =  Agente::find($agente);
            if((!isset($a)) || ($gerente->id != $a->gerente_id)){
                return null;
            }

            $filtros = [
                ['agente_id', $agente],
                ['data_aposta', ">=", $dataInico."T00:00:00"],
                ['data_aposta', "<=", $dataFinal."T23:59:59"],
            ];

            if($agente != 0){
                $filtros[] = ['agente_id', $agente];
            }

            $apostas = Aposta::where($filtros)
            ->take(250)
            ->orderBy('id', 'desc')
            ->get();

            $agentes = Agente::all();
        }
            

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
            $aposta->agente = $agentes->where('id', $aposta->agente_id)->first();
        }


        $indexApostas = Aposta::getIndexApostas($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

        return [
            'apostas' => $apostas,
            'apostasComStatus' => $apostasComStatus,
        ];
    }

    private function getIndexes($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }

    
}
