<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\Palpite;

class ApostaAdminController extends Controller{

	 public function __construct(){
        $this->middleware('auth:web-admin');
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

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

    	return view('admin.apostas', compact('apostas', 'apostasComStatus'));
    }

    public function apostasJSON(Request $request){
        $dataInico = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');
        $premiosApartir = $request->input('premios_apartir');

        $apostas = Aposta::where([
            ['agente_id', "<>", ''],
            ['premiacao', ">=", $premiosApartir],
            ['data_aposta', ">=", $dataInico."T00:00"],
            ['data_aposta', "<=", $dataFinal."T23:59"],
        ])
        ->take(100)
        ->orderBy('id', 'desc')
        ->get();

        $indexApostas = $this->getIndexApostas($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

        return [
            'apostas' => $apostas,
            'apostasComStatus' => $apostasComStatus,
        ];
    }

    private function getIndexApostas($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }

    private function getApostasComStatusAgrupadas($palpitesAgrupados){
        $apostasPerdidas = [];
        $apostasAbertas = [];
        $apostasGanhas = [];

        foreach ($palpitesAgrupados as $indexAposta => $aposta) {
            $quantPalpites = $aposta->count();
            $quantAcertos = 0;
            $quantPalpitesConferidos = 0;

            foreach ($aposta as $palpite) {
                $quantPalpitesConferidos++;

                if($palpite->situacao_palpite_id===2){
                    $apostasPerdidas[$indexAposta] = $aposta;
                    break;
                }

                if($palpite->situacao_palpite_id===1){
                    $quantAcertos++;
                    if($quantPalpites===$quantAcertos){
                        $apostasGanhas[$indexAposta] = $aposta;
                        break;
                    }
                }

                if($palpite->situacao_palpite_id===3){
                    if($quantPalpitesConferidos===$quantPalpites){
                        $apostasAbertas[$indexAposta] = $aposta;
                        break;
                    }
                }
            }
        }
        return $apostas = [
            'apostas_perdidas' => $apostasPerdidas,
            'apostas_abertas' => $apostasAbertas,
            'apostas_ganhas' => $apostasGanhas,
        ];
    }


}
