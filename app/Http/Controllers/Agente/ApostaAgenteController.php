<?php

namespace App\Http\Controllers\Agente;

date_default_timezone_set('America/Fortaleza');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\ConfigAgente;
use App\ConfigGlobal;
use App\Palpite;
use App\CodigoErro;
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
    	->take(40)
    	->orderBy('id', 'desc')
    	->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
        }

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
        ->take(150)
        ->orderBy('id', 'desc')
        ->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        foreach ($apostas as $aposta) {
            $aposta->data_aposta = MinhaClasse::data_mysql_to_datahora_formatada($aposta->data_aposta);
        }

        return [
            'apostas' => $apostas,
            'apostasComStatus' => $apostasComStatus,
        ];
    }

    public function apostaJSON(Request $request, $id){
        

        $aposta = Aposta::find($id);

        if( isset( $aposta ) ){
            $apController = new ApostaController();

            $limiteLiberado = $this->isLimitesLiberado(Auth::user()->id, $aposta->valor_apostado);

            if($limiteLiberado!==true){
                return $resultado = [
                    'sucesso' => true,
                    'validacao_disponivel' => false,
                    'msg' => $limiteLiberado,
                ];
            }

            if( $aposta->agente_id == null){
                $aposta->palpites;
                if($this->todosEventosDisponiveis($aposta)){
                    return $resultado = [
                        'sucesso' => true,
                        'validacao_disponivel' => true,
                        'aposta' => $apController->geJSON($id)
                    ];
                }else{
                    return $resultado = [
                        'sucesso' => true,
                        'validacao_disponivel' => false,
                        'msg' => '1 ou mais eventos ja iniciaram',
                        'aposta' => $apController->geJSON($id)
                    ];
                }
                    
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
                    'validacao_disponivel' => false,
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
        $valorMaxApostaAgente = $this->getValorMaxApostaAgente(); 

        $aposta = Aposta::find($request->input('aposta_id'));
        if( isset( $aposta ) ){
            if(!$this->todosEventosDisponiveis($aposta)){
                return $resultado = [
                    'sucesso' => false,
                    'validacao_disponivel' => false,
                    'msg' => '1 ou mais eventos ja iniciaram!'
                ];
            }

            $limiteLiberado = $this->isLimitesLiberado(Auth::user()->id, $aposta->valor_apostado);

            if($limiteLiberado!==true){
                return $resultado = [
                    'sucesso' => false,
                    'validacao_disponivel' => false,
                    'msg' => $limiteLiberado,
                ];
            }

            if($aposta->valor_apostado > $valorMaxApostaAgente){
                return redirect()->route('erro', CodigoErro::ValorMaxAposta);
            }

            if( $aposta->agente_id == null){
                $comissaoAgente = $this->getComissaoApostaAgente($aposta->cotacao_total);
                $aposta->agente_id = Auth::user()->id;
                $aposta->comissao_agente = $comissaoAgente;
                $aposta->data_validacao = MinhaClasse::timestamp_to_data_mysql(time());
                $aposta->save();

                $resultado = [
                    'sucesso' => true,
                    'msg' => 'Aposta Validada com sucesso!'
                ];

                return redirect()->route("viewaposta", ["id" => $aposta->id]);

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
        $valorMaxApostaAgente = $this->getValorMaxApostaAgente();        
        $valorMaxApostaPadrao = $this->getValorMaxApostaPadrao();

        foreach ($request->session()->get('palpites') as $palpite) {
            $cotaTotal *= $palpite['valor'];
            $quantPalpites++;
        }
        if($cotaTotal>800){
            $cotaTotal=800;
        }
        
        $valorApostado = $request->input('valorAposta');

        if($valorApostado > $valorMaxApostaAgente ){
            return CodigoErro::ValorMaxAposta;
        }

        if($valorApostado > $valorMaxApostaPadrao){
            if($cotaTotal < 3){
                return CodigoErro::ValorMinCota;
            }
        }

        $premiacao = $valorApostado*$cotaTotal;
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
        $aposta->valor_apostado = $valorApostado;
        $aposta->comissao_agente = $comissaoAgente;
        $aposta->premiacao = $premiacao;
        $aposta->ganhou = 0;
        $aposta->save();

        Aposta::where('id', $aposta->id)->update(['controle'=>Aposta::getControle($aposta->id)]);

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


    public function isLimitesLiberado($agente_id, $valor_aposta){
        $configAgente = $this->getConfigAgente($agente_id);
        $limiteSemanal = $configAgente->where('tipo_config_id', 14)->first();
        $somaApostas = $this->somaApostas();

        if(!$limiteSemanal==null){
            $valorLimite = $limiteSemanal->valor;
            if($somaApostas+$valor_aposta > $valorLimite){
                return "Você já fez R$ ". number_format($somaApostas, 2) ." em apostas \n".
                "Seu limite em 7 dias é de R$ ". number_format($valorLimite, 2);
            }
        }else{
            $limiteSemanal = ConfigGlobal::where('tipo_config_id', 14)->first();
            $valorLimite = $limiteSemanal->valor;
            if($somaApostas+$valor_aposta > $valorLimite){
                return "Você já fez R$ ". number_format($somaApostas, 2) ." em apostas \n".
                "Seu limite em 7 dias é de R$ ". number_format($valorLimite, 2);
            }
        }

        return true;
    }

    public function somaApostas(){
        $dataInicio = date("Y-m-d", strtotime("-6 days"))."T00:00:00";
        $dataFim = date("Y-m-d", strtotime("now"))."T23:59:59";

        $filtro = [
            ['agente_id', Auth::user()->id],
            ['data_aposta', '>=', $dataInicio],
            ['data_aposta', '<=', $dataFim],
        ];

        $apostas = Aposta::where($filtro)->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        $soma_apostas = $apostas->sum("valor_apostado");
        $somaPremios = 0;
        $somaComissao = 0;
        foreach ($apostas as $aposta) {
            $somaComissao += ($aposta->valor_apostado * $aposta->comissao_agente);
            if($apostasComStatus[$aposta->id]['status']==1){
                $somaPremios += $aposta->premiacao;
            }
            
        }

        return $soma_apostas;
    }

    public function getConfigAgente($agente_id){
        $configs = ConfigAgente::where('agente_id', $agente_id)->get();
        return $configs;
    }

    public function getConfigGlobal(){
        $configs = ConfigGlobal::all();
        return $configs;
    }

    public function getIndexApostas($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }

    private function getValorMaxApostaAgente(){
        $agente = Auth::user();
        $config = ConfigAgente::where([
            ['tipo_config_id', 4],
            ['agente_id', $agente->id],
        ])->first();
        if(isset($config)){
            $valorMax = $config->valor;
        }else{
            $valorMax = ConfigGlobal::where('tipo_config_id', 4)->first()->valor;
        }
        return $valorMax;
    }

    private function getValorMaxApostaPadrao(){        
        $valorMax = ConfigGlobal::where('tipo_config_id', 4)->first()->valor;
        return $valorMax;
    }

    private function todosEventosDisponiveis($aposta){
        foreach ($aposta->palpites as $palpite) {
            $dataEvento = MinhaClasse::date_mysql_to_timestamp($palpite->evento->data);
            if(time() >= $dataEvento){
                return false;
            }
        }
        return true;
    }


}
