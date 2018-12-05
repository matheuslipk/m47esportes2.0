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

class ApostaAgenteController extends Controller{
	public function __construct(){
		return $this->middleware('auth');
	}

    public function apostas(Request $request){
    	$apostas = Aposta::where([
    		['agente_id', Auth::user()->id]
    	])
    	->take(30)
    	->orderBy('id', 'desc')
    	->get();

        $indexApostas = $this->getIndexApostas($apostas);
        $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

        $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

    	return view('agente.apostas', compact('apostas', 'apostasComStatus'));
    }

    public function apostasJSON(Request $request){
        $dataInico = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');

        $apostas = Aposta::where([
            ['agente_id', Auth::user()->id],
            ['data_aposta', ">=", $dataInico."T00:00:00"],
            ['data_aposta', "<=", $dataFinal."T23:59:59"],
        ])
        ->take(50)
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

    public function apostaJSON(Request $request, $id){
        $aposta = Aposta::find($id);
        if( isset( $aposta ) ){
            $apController = new ApostaController();

            if( $aposta->agente_id == null){
                $aposta->palpites;
                return $resultado = [
                    'sucesso' => true,
                    'validacao_disponivel' => true,
                    'aposta' => $apController->geJSON($id)
                ];
            }
            if( $aposta->agente_id == Auth::user()->id ){
                $aposta->palpites;
                return $resultado = [
                    'sucesso' => true,
                    'validacao_disponivel' => false,
                    'msg' => 'Você já validou essa aposta',
                    'aposta' => $apController->geJSON($id)
                ];
            }
            if( $aposta->agente_id != Auth::user()->id ){
                return $resultado = [
                    'sucesso'=> false,
                    'msg' => 'Aposta validada por outro agente',
                ];
            }
                
        }
        else{
            return $resultado = [
                'sucesso'=> false,
                'msg' => 'Aposta não encontrada'
            ];
        }
    }

    public function showValidar(Request $request){
        return view('agente.validar');
    }

    public function validarAposta(Request $request){
        $aposta = Aposta::find($request->input('aposta_id'));
        if( isset( $aposta ) ){
            if( $aposta->agente_id == null){
                $comissaoAgente = $this->getComissaoApostaAgente($aposta->cotacao_total);
                $aposta->agente_id = Auth::user()->id;
                $aposta->comissao_agente = $comissaoAgente;
                $aposta->data_validacao = MinhaClasse::timestamp_to_data_mysql(time());
                $aposta->save();

                return $resultado = [
                    'sucesso' => true,
                    'msg' => 'Aposta Validada com sucesso!'
                ];
            }else{
                return $resultado = [
                    'sucesso' => false,
                    'msg' => 'Aposta já foi validada'
                ];
            }
        }else{
            return $resultado = [
                'sucesso' => false,
                'msg' => 'Aposta não encontrada'
            ];
        }
    }


    public function apostaAgente(Request $request){
        $cotaTotal = 1;
        $quantPalpites = 0;
        foreach ($request->session()->get('palpites') as $palpite) {
            $cotaTotal *= $palpite['valor'];
            $quantPalpites++;
        }
        if($cotaTotal>800){
            $cotaTotal=800;
        }

        $premiacao = $request->input('valorAposta')*$cotaTotal;
        if($premiacao>8000){
            $premiacao=8000;
        }

        $comissaoAgente = $this->getComissaoApostaAgente($cotaTotal);

        $aposta = new Aposta();
        $aposta->agente_id = Auth::user()->id;
        $aposta->nome = $request->input('nomeAposta');
        $aposta->data_aposta = MinhaClasse::timestamp_to_data_mysql(time());
        $aposta->data_validacao = MinhaClasse::timestamp_to_data_mysql(time());
        $aposta->cotacao_total = $cotaTotal;
        $aposta->valor_apostado = $request->input('valorAposta');
        $aposta->comissao_agente = $comissaoAgente;
        $aposta->premiacao = $premiacao;
        $aposta->ganhou = 0;
        $aposta->save();

        foreach ($request->session()->get('palpites') as $palpite) {
            $p = new Palpite();
            $p->aposta_id = $aposta->id;
            $p->evento_id = $palpite['evento_id'];
            $p->tipo_palpite_id = $palpite['tipo_palpite']['id'];
            $p->cotacao = $palpite['valor'];
            $p->situacao_palpite_id = 3;
            $p->save();
        }
        $request->session()->forget('palpites');
        return $aposta;
    }

    public function getComissaoApostaAgente($cotaTotal){
        $confAgente = ConfigAgente::where('agente_id', Auth::user()->id)->get();
        if(count($confAgente)>=1){
            if($cotaTotal<6){
                $linhaConfig = $confAgente->where('tipo_config_id', 9)->first();
            }elseif($cotaTotal>=6 && $cotaTotal<12){
                $linhaConfig = $confAgente->where('tipo_config_id', 10)->first();
            }elseif($cotaTotal>=12 && $cotaTotal<20){
                $linhaConfig = $confAgente->where('tipo_config_id', 11)->first();
            }elseif($cotaTotal>=20){
                $linhaConfig = $confAgente->where('tipo_config_id', 12)->first();
            }
        }else{
            $confGlobal = ConfigGlobal::all();
            if($cotaTotal<6){
                $linhaConfig = $confGlobal->where('tipo_config_id', 9)->first();
            }elseif($cotaTotal>=6 && $cotaTotal<12){
                $linhaConfig = $confGlobal->where('tipo_config_id', 10)->first();
            }elseif($cotaTotal>=12 && $cotaTotal<20){
                $linhaConfig = $confGlobal->where('tipo_config_id', 11)->first();
            }elseif($cotaTotal>=20){
                $linhaConfig = $confGlobal->where('tipo_config_id', 12)->first();
            }            
        }
        $comissaoAgente = $linhaConfig['valor'];

        return $comissaoAgente;
    }

    public function getIndexApostas($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }


}
