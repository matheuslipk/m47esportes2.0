<?php

namespace App\Http\Controllers\Gerente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Aposta;
use App\User;
use App\Agente;
use App\Palpite;
use App\StatusConta;

class AgenteGerenteController extends Controller
{
    function __construct(){
		$this->middleware('auth:gerente');
	}

    public function index(Request $request){
        $status_contas = StatusConta::all();        
        $agentes = Agente::where('gerente_id', Auth::guard('gerente')->user()->id)->get();

        foreach ($agentes as $agente) {
            $agente->status_conta = $status_contas->find($agente->status_conta_id);
        }
        return view('gerente.agentes.listaagentes', compact('agentes'));
    }

    public function showRegistroForm(){
        return view('gerente.agentes.agenteregistro');
    }

    public function registrar(Request $request){
        $usuario = new User();
        $usuario->name = $request->input('name');
        $usuario->nickname = $request->input('nickname')."_gerente";
        $usuario->password = ($request->input('password'));
        $usuario->save();

        $agente = new Agente();
        $agente->id = $usuario->id;
        $agente->name = $request->input('name');
        $agente->nickname = $request->input('nickname');
        $agente->telefone = $request->input('telefone');
        $agente->email = $request->input('email');
        $agente->gerente_id = Auth::guard('gerente')->user()->id;
        $agente->status_conta_id = 2;
        $agente->password = Hash::make($request->input('password'));
        $agente->save();

        return redirect()->route('listaagentes_gerente');
    }

    public function verAgente_gerente(Request $request, $id){
        $agente = Agente::find($id);
        if(isset($agente)){
            if($agente->gerente_id == Auth::guard('gerente')->user()->id){
                $apostas = Aposta::where('agente_id', $agente->id)
                ->take(20)
                ->orderBy('id', 'desc')
                ->get();

                $indexApostas = $this->getIndexes($apostas);
                $palpitesAgrupados = Palpite::whereIn('aposta_id', $indexApostas)->get()->groupBy('aposta_id');

                $apostasComStatus = Aposta::getApostasComStatusJSON($palpitesAgrupados);

                return view('gerente.agentes.veragente', compact('apostas', 'apostasComStatus'));
            }else{
                return "Agente inexistente ou não faz parte da sua gerência";
            }
        }
    }

    private function getIndexes($arrayApostas){
        $index = [];
        foreach ($arrayApostas as $aposta) {
            $index[] = $aposta->id;
        }
        return $index;
    }
    
}
