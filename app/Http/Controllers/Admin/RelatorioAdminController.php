<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Aposta;
use App\Agente;
use App\User;
use App\Gerente;
use App\Palpite;

class RelatorioAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){    	
    	return view('admin.relatorio');
    }

    public function relatorio(Request $request){
        $gerentes = Gerente::all();
        $agentes = Agente::all();

        $dataInicio = $request->input('data_inicio')."T00:00:00";
        $dataFim = $request->input('data_fim')."T23:59:59";

        $filtro = [
            ['agente_id', "<>", ''],
            ['data_aposta', '>=', $dataInicio],
            ['agente_id', '<=', $dataFim],
        ];

        $apostas = Aposta::where($filtro)->get();

        $apostasComStatus = Aposta::getApostasComStatusJSON2($apostas);

        foreach ($gerentes as $gerente) {
            $gerente->agentes = $agentes->where("gerente_id", $gerente->id);        
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
        }        

        return compact('gerentes');
    }

}
