<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\Palpite;
use App\Agente;
use App\Http\Controllers\Api\MinhaClasse;

class ApostaAdminController extends Controller{

	 public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function apostas(Request $request){
    	$apostas = Aposta::where([
    		['agente_id', "<>", '']
    	])
    	->take(250)
    	->orderBy('id', 'desc')
    	->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        $agentes = Agente::all();

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
            $aposta->agente = $agentes->where('id', $aposta->agente_id)->first();
        }

    	return view('admin.apostas', compact('apostas', 'apostasComStatus'));
    }

    public function apostasJSON(Request $request){
        $dataInico = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');
        $agente = $request->input('agente');
        $premiosApartir = $request->input('premios_apartir');

        $filtros = [
            ['agente_id', "<>", ''],
            ['premiacao', ">=", $premiosApartir],
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

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        $agentes = Agente::all();

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
            $aposta->agente = $agentes->where('id', $aposta->agente_id)->first();
        }

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

    public function atualizarStatusApostas($apostas){
        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        foreach ($apostasComStatus as $aposta_id => $palpites) {
            $filtro = [
                ['id', $aposta_id]
            ];

            Aposta::where($filtro)->update(['status_aposta_id' => $palpites['status']]);
        }
    }

}
