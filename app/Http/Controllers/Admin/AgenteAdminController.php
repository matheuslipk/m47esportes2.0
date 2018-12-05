<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agente;
use App\Gerente;
use Illuminate\Support\Facades\Hash;
use App\StatusConta;

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
        if(isset($agente)){
            return view('admin.agentes.veragente', compact('agente'));
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
        $agente->telefone = $request->input('telefone');
        $agente->email = $request->input('email');
        $agente->gerente_id = $request->input('gerente');
        $agente->status_conta_id = $request->input('status_conta');
        $agente->password = Hash::make($request->input('password'));
        $agente->save();

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

}
