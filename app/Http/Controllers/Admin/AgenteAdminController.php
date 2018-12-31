<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Agente;
use App\Gerente;
use App\StatusConta;
use App\ConfigAgente;
use App\ConfigGlobal;

class AgenteAdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:web-admin');
    }

    public function index(Request $request){
    	$status_contas = StatusConta::all();    	
    	$agentes = Agente::all();

    	foreach ($agentes as $agente) {
    		$agente->status_conta = $status_contas->find($agente->status_conta_id);
    	}
    	return view('admin.agentes.listaagentes', compact('agentes'));
    }

    public function editar(Request $request, $id){
        $agente = Agente::find($id);
        $configAgente = ConfigAgente::where('agente_id', $id)->get();

        if(count($configAgente)==0){
            $configAgente = null;
        }

        if(isset($agente)){
            return view('admin.agentes.veragente', compact('agente', 'configAgente'));
        }else{
            return [];
        }
        
    }

    public function showRegistroForm(){
        $gerentes = Gerente::where('status_conta_id', 1)
        ->orderBy('name')
        ->get();
        return view('admin.agentes.agenteregistro', compact('gerentes'));
    }

    public function registrar(Request $request){
        $agente = new Agente();
        $agente->name = $request->input('name');
        $agente->nickname = $request->input('nickname');
        $agente->telefone = $request->input('telefone');
        $agente->email = $request->input('email');
        $agente->gerente_id = $request->input('gerente');
        $agente->status_conta_id = $request->input('status_conta');
        $agente->password = Hash::make($request->input('password'));
        $agente->save();

        $this->salvarConfigAgente($agente);

        return redirect()->route('editaragente', ['id' => $agente->id]);
    }

    public function salvar(Request $request, $id){
        if($id != $request->input('agente_id')){
            return "Erro de id diferente";
        }

        $agente = Agente::find($id);
        if(isset($agente)){
            $agente->name = $request->input('name');
            $agente->telefone = $request->input('telefone');
            $agente->email = $request->input('email');
            $agente->status_conta_id = $request->input('status_conta');
            $agente->save();

            return redirect()->route('editaragente', ['id' => $id]);
        }else{
            return [];
        }
        
    }

    public function getAgentesByGerente(Request $request){
        $agentes = Agente::where('gerente_id', $request->input('gerente'))->get();
        return $agentes;
    }

    public function editarConfigAgente(Request $request, $id){
        ConfigAgente::where('agente_id', $id)->where('tipo_config_id', 9)->update(['valor' => ($request->input('cota1')/100)]);
        ConfigAgente::where('agente_id', $id)->where('tipo_config_id', 10)->update(['valor' => ($request->input('cota2')/100)]);
        ConfigAgente::where('agente_id', $id)->where('tipo_config_id', 11)->update(['valor' => ($request->input('cota3')/100)]);
        ConfigAgente::where('agente_id', $id)->where('tipo_config_id', 12)->update(['valor' => ($request->input('cota4')/100)]);

        return redirect()->route('editaragente', ['id'=>$id]);
    }

    private function salvarConfigAgente($agente){        
        $configAdmin = ConfigGlobal::where([
            ['tipo_config_id', '>=', '9'],
            ['tipo_config_id', '<=', '12'],
        ])->get();

        foreach ($configAdmin as $config) {
            $configAgente = new ConfigAgente();
            $configAgente->tipo_config_id = $config->tipo_config_id;
            $configAgente->agente_id = $agente->id;
            $configAgente->valor = $config->valor;
            $configAgente->save();
        }        

    }

}
