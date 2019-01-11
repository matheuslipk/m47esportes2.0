<?php
namespace App\Http\Controllers\Gerente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Aposta;
use App\Agente;
use App\User;
use App\Gerente;
use App\Palpite;

class RelatorioGerenteController extends Controller
{
	public function __construct(){
        $this->middleware('auth:gerente');
    }

    public function index(Request $request){    	
    	return view('gerente.relatorio');
    }

    public function relatorio(Request $request){
        // return "ok";
        $gerente = Gerente::find(Auth::guard('gerente')->user()->id);
        $agentes = Agente::where('gerente_id', $gerente->id)->get();
        $gerente->agentes = $agentes;

        $dataInicio = $request->input('data_inicio')."T00:00:00";
        $dataFim = $request->input('data_fim')."T23:59:59";

        $filtro = [
            ['data_aposta', '>=', $dataInicio],
            ['data_aposta', '<=', $dataFim],
        ];

        $apostas = Aposta::whereIn('agente_id', $this->getIndexes($agentes))
        ->where($filtro)
        ->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        

        foreach ($gerente->agentes as $agente) {
            $ap = $apostas->where("agente_id", $agente->id);
            $agente->soma_apostas = $ap->sum("valor_apostado");
            $somaPremios = 0;
            $somaComissao = 0;
            foreach ($ap as $apostaDoAgente) {
                $somaComissao += ($apostaDoAgente->valor_apostado * $apostaDoAgente->comissao_agente);
                if($apostasComStatus[$apostaDoAgente->id]['status']==1){
                    $somaPremios += $apostaDoAgente->premiacao;
                }
                
            }
            $agente->premiacao = $somaPremios;
            $agente->comissao = $somaComissao;
        }    

        return compact('gerente');
    }

    private function getIndexes($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }

}
